<?php
session_start();
require_once '../database.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the admin is logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}

// Function to extract initials
function getInitials($username) {
    if (empty($username)) return 'NA';
    $parts = preg_split('/[\s_]+/', $username);
    $initials = array_map(fn($part) => strtoupper($part[0] ?? ''), $parts);
    return implode('', $initials);
}

// Handle role deletion
// Handle role deletion
if (isset($_POST['delete_role'])) {
    $role_id = intval($_POST['role_id']);
    $sql = "UPDATE user_roles SET delete_user = 1 WHERE role_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $role_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Preserve search parameter if it exists
    $redirect_url = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'search=') !== false) {
        $parsed_url = parse_url($_SERVER['HTTP_REFERER']);
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $query_params);
            if (isset($query_params['search'])) {
                $redirect_url .= '?search=' . urlencode($query_params['search']);
            }
        }
    }
    
    header("Location: " . $redirect_url);
    exit;
}

// Handle form submission for approving, denying, or updating user status
if (isset($_POST['action'])) {
    $user_id = intval($_POST['user_id']);
    $user_type = $_POST['user_type'];
    $registration_status = $_POST['registration_status'] ?? 'Pending';
    $action = $_POST['action'];

    if ($action === 'approve') {
        $sql = "UPDATE regis SET registration_status = 'Approved', user_type = ? WHERE user_id = ?";
        $params = [$user_type, $user_id];
        $types = "si";
        
        // Check if role already exists before inserting
        $check_sql = "SELECT role_id FROM user_roles WHERE user_id = ? AND role = ? AND delete_user = 0";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "is", $user_id, $user_type);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            $query_insert = "INSERT INTO user_roles (user_id, role) VALUES(?, ?)";
            if ($stmt_insert = mysqli_prepare($conn, $query_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "is", $user_id, $user_type);
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
            }
        }
        mysqli_stmt_close($check_stmt);

        // Insert into applicants if user_type is Applicant
        if ($user_type === 'Applicant') {
            $user_result = mysqli_query($conn, "SELECT usernames, email FROM regis WHERE user_id = $user_id");
            if ($user_data = mysqli_fetch_assoc($user_result)) {
                $name = $user_data['usernames'];
                $email = $user_data['email'];

                $check = mysqli_query($conn, "SELECT * FROM applicants WHERE email = '$email'");
                if (mysqli_num_rows($check) === 0) {
                    $insert_sql = "INSERT INTO applicants (name, user_id, email) VALUES (?, ?, ?)";
                    if ($stmt_insert = mysqli_prepare($conn, $insert_sql)) {
                        mysqli_stmt_bind_param($stmt_insert, "sis", $name, $user_id, $email);
                        mysqli_stmt_execute($stmt_insert);
                        mysqli_stmt_close($stmt_insert);
                    }
                }
            }
        }
    } elseif ($action === 'deny') {
        $sql = "UPDATE regis SET registration_status = 'Denied' WHERE user_id = ?";
        $params = [$user_id];
        $types = "i";
    } elseif ($action === 'update_status') {
        $sql = "UPDATE regis SET registration_status = ?, user_type = ? WHERE user_id = ?";
        $params = [$registration_status, $user_type, $user_id];
        $types = "ssi";
        
        // Check if role already exists before inserting
        $check_sql = "SELECT role_id FROM user_roles WHERE user_id = ? AND role = ? AND delete_user = 0";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "is", $user_id, $user_type);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            $query_insert = "INSERT INTO user_roles (user_id, role) VALUES(?, ?)";
            if ($stmt_insert = mysqli_prepare($conn, $query_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "is", $user_id, $user_type);
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
            }
        }
        mysqli_stmt_close($check_stmt);

        // Insert into applicants if user_type is Applicant
        if ($user_type === 'Applicant') {
            $user_result = mysqli_query($conn, "SELECT usernames, email FROM regis WHERE user_id = $user_id");
            if ($user_data = mysqli_fetch_assoc($user_result)) {
                $name = $user_data['usernames'];
                $email = $user_data['email'];

                $check = mysqli_query($conn, "SELECT * FROM applicants WHERE user_id = '$user_id'");
                if (mysqli_num_rows($check) === 0) {
                    $insert_sql = "INSERT INTO applicants (name, user_id, email) VALUES (?, ?, ?)";
                    if ($stmt_insert = mysqli_prepare($conn, $insert_sql)) {
                        mysqli_stmt_bind_param($stmt_insert, "sis", $name, $user_id, $email);
                        mysqli_stmt_execute($stmt_insert);
                        mysqli_stmt_close($stmt_insert);
                    }
                }
            }
        }
    }

    if (isset($sql) && $stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Dashboard metrics
$recent_activities_sql = "SELECT COUNT(*) AS recent_activities FROM activity_logs WHERE is_read = 0";
$recent_activities_result = mysqli_query($conn, $recent_activities_sql);
$recent_activities_row = mysqli_fetch_assoc($recent_activities_result);
$recent_activities = $recent_activities_row['recent_activities'] ?? 0;

$users_no = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM regis WHERE registration_status = 'Approved'"));
$pending_users = [];
$pending_users_no = 0;
$sql = "SELECT * FROM regis WHERE registration_status = 'Pending'";
if ($result = mysqli_query($conn, $sql)) {
    $pending_users_no = mysqli_num_rows($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $pending_users[] = $row;
    }
    mysqli_free_result($result);
}

// Search functionality
$search_results = [];
if (isset($_GET['search'])) {
    $search_term = "%" . mysqli_real_escape_string($conn, $_GET['search']) . "%";
    $sql = "SELECT * FROM regis WHERE (usernames LIKE ? OR email LIKE ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            // Fetch all active roles for this user
            $roles_sql = "SELECT role_id, role FROM user_roles WHERE user_id = ? AND delete_user = 0";
            $roles_stmt = mysqli_prepare($conn, $roles_sql);
            mysqli_stmt_bind_param($roles_stmt, "i", $row['user_id']);
            mysqli_stmt_execute($roles_stmt);
            $roles_result = mysqli_stmt_get_result($roles_stmt);
            $roles = [];
            while ($role_row = mysqli_fetch_assoc($roles_result)) {
                $roles[] = $role_row;
            }
            mysqli_stmt_close($roles_stmt);
            
            // Add roles to user data
            $row['roles'] = $roles;
            $search_results[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!-- HTML Section -->
<?php include '../header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3e8f3;
            font-family: 'Inter', sans-serif;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            color: #7b2574;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-denied {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .search-bar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }
        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }
        .btn_outline {
            background: linear-gradient(to right, #1e40af, #2563eb);
            transition: all 0.3s ease;
        }
        .btn_outline:hover {
            background: linear-gradient(to right, #1e3a8a, #1e40af);
            transform: translateY(-1px);
        }
        .role-badge {
            transition: all 0.2s ease;
        }
        .role-badge:hover {
            transform: scale(1.05);
        }
        .delete-role-btn {
            transition: all 0.2s ease;
        }
        .delete-role-btn:hover {
            color: #dc2626;
            transform: scale(1.2);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2 text-blue-900">Admin Dashboard</h1>
            <p class="text-gray-600">Manage user registrations and permissions</p>
        </div>

        <!-- Search Form -->
        <center>
            <div class="search-bar w-50 rounded-2xl shadow-lg p-6 mb-8">
                <form method="GET" action="admin.php" class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search by username or email"
                                class="w-full pl-12 pr-4 py-3 rounded-xl border border-purple-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none transition-all">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn_outline px-8 py-3 text-white rounded-xl transition-all shadow-md hover:shadow-lg hover:text-white">
                        Search
                    </button>
                </form>
            </div>
        </center>

        <!-- Main Content Grid -->
        <div class="grid gap-8 mb-8">
            <!-- Search Results -->
            <?php if (!empty($search_results)): ?>
                <div class="dashboard-card rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4" style="background: linear-gradient(to right, #1e40af, #5E8EF6);">
                        <h2 class="text-2xl font-bold text-white">User Management</h2>
                        <p class="text-purple-100">View and manage users</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-purple-100">
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Username</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Email</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">User Type & Roles</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Status</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-100">
                                    <?php foreach ($search_results as $user): ?>
                                        <tr class="hover:bg-purple-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <?php
                                                $username = $user['usernames'];
                                                $initials = getInitials($username);
                                                ?>
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-green-200 flex items-center justify-center mr-3">
                                                        <span class="text-sm font-medium text-green-700"><?php echo htmlspecialchars($initials); ?></span>
                                                    </div>
                                                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($username); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td class="px-6 py-4 text-gray-600">
                                                <?php echo htmlspecialchars($user['user_type'] ?? 'N/A'); ?>
                                                <?php if (!empty($user['roles'])): ?>
                                                    <div class="mt-1 flex flex-wrap gap-1 items-center">
                                                        <?php foreach ($user['roles'] as $role): ?>
                                                            <div class="flex items-center gap-1 role-badge">
                                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                                    <?php echo htmlspecialchars($role['role']); ?>
                                                                </span>
                                                                <form method="POST" class="inline">
                                                                    <input type="hidden" name="role_id" value="<?php echo $role['role_id']; ?>">
                                                                    <button type="submit" name="delete_role" class="delete-role-btn text-red-500 hover:text-red-700 text-xs">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if ($user['registration_status'] == 'Approved'): ?>
                                                    <span class="status-approved px-3 py-1 rounded-full text-sm font-medium">
                                                        <?php echo htmlspecialchars($user['registration_status']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="status-pending px-3 py-1 rounded-full text-sm font-medium">
                                                        <?php echo htmlspecialchars($user['registration_status']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <form method="POST" action="admin.php">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                    <select name="registration_status" class="custom-select rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-purple-500 focus:border-purple-500 mb-2">
                                                        <option value="Pending" <?php if ($user['registration_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="Approved" <?php if ($user['registration_status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                                                        <option value="Denied" <?php if ($user['registration_status'] == 'Denied') echo 'selected'; ?>>Denied</option>
                                                    </select>
                                                    <select name="user_type" class="custom-select rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-purple-500 focus:border-purple-500 mb-2">
                                                        <option value="Student" <?php if ($user['user_type'] == 'Student') echo 'selected'; ?>>Student</option>
                                                        <option value="Mentor" <?php if ($user['user_type'] == 'Mentor') echo 'selected'; ?>>Mentor</option>
                                                        <option value="Interviewer" <?php if ($user['user_type'] == 'Interviewer') echo 'selected'; ?>>Interviewer</option>
                                                        <option value="Applicant" <?php if ($user['user_type'] == 'Applicant') echo 'selected'; ?>>Applicant</option>
                                                        <option value="Admin" <?php if ($user['user_type'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                                        <option value="Graduate" <?php if ($user['user_type'] == 'Graduate') echo 'selected'; ?>>Graduate</option>
                                                        <option value="Pending" <?php if ($user['user_type'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                                    </select>
                                                    <button type="submit" name="action" value="update_status" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors w-full">Update Status</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php elseif (isset($_GET['search'])): ?>
                <p class="text-gray-600 text-center py-4">No users found matching your search.</p>
            <?php endif; ?>

            <!-- Display Pending Registrations -->
            <?php if (!empty($pending_users)): ?>
                <div class="dashboard-card rounded-2xl shadow-lg overflow-hidden">
                    <div style="background: linear-gradient(to right, #1e40af, #5E8EF6);" class="px-6 py-4">
                        <h2 class="text-2xl font-bold text-white">Pending Registrations</h2>
                        <p class="text-purple-100">Approve or deny user requests</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-purple-100">
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Username</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Email</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Requested Role</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">User Type</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-purple-900">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-100">
                                    <?php foreach ($pending_users as $row): ?>
                                        <tr class="hover:bg-purple-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <?php
                                                $username = $row['usernames'];
                                                $initials = getInitials($username);
                                                ?>
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-green-200 flex items-center justify-center mr-3">
                                                        <span class="text-sm font-medium text-green-700"><?php echo htmlspecialchars($initials); ?></span>
                                                    </div>
                                                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($username); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($row['requested_role']); ?></td>
                                            <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($row['user_type']); ?></td>
                                            <td class="px-6 py-4">
                                                <form action="admin.php" method="POST" class="flex flex-col gap-2 md:flex-row">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                    <select name="user_type" class="custom-select rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-purple-500 focus:border-purple-500">
                                                        <option value="Student" <?php if ($row['user_type'] == 'Student') echo 'selected'; ?>>Student</option>
                                                        <option value="Mentor" <?php if ($row['user_type'] == 'Mentor') echo 'selected'; ?>>Mentor</option>
                                                        <option value="Interviewer" <?php if ($row['user_type'] == 'Interviewer') echo 'selected'; ?>>Interviewer</option>
                                                        <option value="Applicant" <?php if ($row['user_type'] == 'Applicant') echo 'selected'; ?>>Applicant</option>
                                                        <option value="Admin" <?php if ($row['user_type'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                                        <option value="Graduate" <?php if ($row['user_type'] == 'Graduate') echo 'selected'; ?>>Graduate</option>
                                                        <option value="Pending" <?php if ($row['user_type'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                                    </select>
                                                    <div class="flex gap-2 mt-2 md:mt-0">
                                                        <button type="submit" name="action" value="approve" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors flex-1">Approve</button>
                                                        <button type="submit" name="action" value="deny" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors flex-1">Deny</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-600 text-center py-4">No pending registrations found.</p>
            <?php endif; ?>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="dashboard-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Pending Approvals</p>
                            <h3 class="text-3xl font-bold text-blue-800"><?php echo $pending_users_no; ?></h3>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <a href="nedsmenu.php">
                    <div class="dashboard-card rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Users</p>
                                <h3 class="text-3xl font-bold text-blue-800"><?php echo $users_no; ?></h3>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="activity_logs.php">
                    <div class="dashboard-card rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-700">Recent Activity</p>
                                <h3 class="text-3xl font-bold text-blue-800"><?php echo $recent_activities; ?></h3>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php include "../footer.php"; ?>
</body>
</html>