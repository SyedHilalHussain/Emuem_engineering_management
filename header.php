<?php  

echo '<div class="navbar-area style-2" id="navbar">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="index.html">
                    <img class="logo-light" src="assets/img/logo/white-logo.png" alt="logo">
                    <img class="logo-dark" src="assets/img/logo/logo.png" alt="logo">
                </a>
                <div class="other-option d-lg-none">
                    <div class="option-item">
                        <button type="button" class="search-btn" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                </div>
                <a class="navbar-toggler" data-bs-toggle="offcanvas" href="#navbarOffcanvas" role="button" aria-controls="navbarOffcanvas">
                    <i class="bx bx-menu"></i>
                </a>
                <div class="collapse navbar-collapse justify-content-between">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link active">
                                Home
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="index.html" class="nav-link">Home One</a></li>
                                <li class="nav-item"><a href="index2.html" class="nav-link active">Home Two</a></li>
                                <li class="nav-item"><a href="index3.html" class="nav-link">Home Three</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link">
                                Pages
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="about-us.html" class="nav-link">About Us</a></li>
                                <li class="nav-item"><a href="news-and-blog.html" class="nav-link">News and Blog</a></li>
                                <li class="nav-item"><a href="blog-details.html" class="nav-link">Blog Details</a></li>
                                <li class="nav-item"><a href="alumni.html" class="nav-link">Alumni</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link">
                                Academics
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="academics.html" class="nav-link">Academics</a></li>
                                <li class="nav-item"><a href="undergraduate.html" class="nav-link">Undergraduate</a></li>
                                <li class="nav-item"><a href="graduate.html" class="nav-link">Graduate</a></li>
                                <li class="nav-item"><a href="online-education.html" class="nav-link">Online Education</a></li>
                                <li class="nav-item"><a href="faculty.html" class="nav-link">Faculty</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link">
                                Admissions
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="admission.html" class="nav-link">Admissions</a></li>
                                <li class="nav-item"><a href="how-to-apply.html" class="nav-link">How to Apply</a></li>
                                <li class="nav-item"><a href="tuition-fees.html" class="nav-link">Tuition & Fees</a></li>
                                <li class="nav-item"><a href="financial-aid.html" class="nav-link">Financial Aid</a></li>
                                <li class="nav-item"><a href="date-deadlines.html" class="nav-link">Date & Deadlines</a></li>
                                <li class="nav-item"><a href="schedule-tour.html" class="nav-link">Schedule a Tour</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link">
                                Courses
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="courses.html" class="nav-link">Courses Sidebar</a></li>
                                <li class="nav-item"><a href="courses-details.html" class="nav-link">Course Details</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link">
                                University Life
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="university-life.html" class="nav-link">University Life</a></li>
                                <li class="nav-item"><a href="the-campus-experience.html" class="nav-link">The Campus Experience</a></li>
                                <li class="nav-item"><a href="fitness-athletics.html" class="nav-link">Fitness & Athletics</a></li>
                                <li class="nav-item"><a href="support-guidance.html" class="nav-link">Support & Guidance</a></li>
                                <li class="nav-item"><a href="student-activities.html" class="nav-link">Student Activities</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="contact-us.html" class="nav-link">
                                Contact
                            </a>
                        </li>
                    </ul>
                    <div class="others-option d-flex align-items-center">
                        <div class="option-item">
                            <div class="nav-btn">
                                <a href="contact-us.html" class="default-btn">Contact Us</a>
                            </div>
                        </div>
                        <div class="option-item">
                            <div class="nav-search">
                                <a href="#" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop" class="search-button"><i class="bx bx-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
      <div class="responsive-navbar offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="navbarOffcanvas">
        <div class="offcanvas-header">
            <a href="index.html" class="logo d-inline-block">
                <img class="logo-light" src="assets/img/logo/logo.png" alt="logo">
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="accordion" id="navbarAccordion">
                <div class="accordion-item">
                    <button class="accordion-button collapsed active" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Home
                    </button>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion7">
                                <div class="accordion-item">
                                    <a href="index.html" class="accordion-link">
                                        Home One
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="index2.html" class="accordion-link active">
                                        Home Two
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="index3.html" class="accordion-link">
                                        Home Three
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Pages
                    </button>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion8">
                                <div class="accordion-item">
                                    <a href="about-us.html" class="accordion-link">
                                        About Us
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="news-and-blog.html" class="accordion-link">
                                        News and Blog
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="blog-details.html" class="accordion-link">
                                        Blog Details
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="alumni.html" class="accordion-link">
                                        Alumni
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Academics
                    </button>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion30">
                                <div class="accordion-item">
                                    <a href="academics.html" class="accordion-link">
                                        Academics
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="undergraduate.html" class="accordion-link">
                                        Undergraduate
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="graduate.html" class="accordion-link">
                                        Graduate
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="online-education.html" class="accordion-link">
                                        Online Education
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="faculty.html" class="accordion-link">
                                        Faculty
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Admissions
                    </button>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion31">
                                <div class="accordion-item">
                                    <a href="admission.html" class="accordion-link">
                                        Admissions
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="how-to-apply.html" class="accordion-link">
                                        How to Apply
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="tuition-fees.html" class="accordion-link">
                                        Tuition &amp; Fees
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="financial-aid.html" class="accordion-link">
                                        Financial Aid
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="date-deadlines.html" class="accordion-link">
                                        Date &amp; Deadlines
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="schedule-tour.html" class="accordion-link">
                                        Schedule a Tour
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Courses
                    </button>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion32">
                                <div class="accordion-item">
                                    <a href="courses.html" class="accordion-link">
                                        Courses Sidebar
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="courses-details.html" class="accordion-link">
                                        Course Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        University Life
                    </button>
                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion40">
                                <div class="accordion-item">
                                    <a href="university-life.html" class="accordion-link">
                                        University Life
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="the-campus-experience.html" class="accordion-link">
                                        The Campus Experience
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="fitness-athletics.html" class="accordion-link">
                                        Fitness &amp; Athletics
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="support-guidance.html" class="accordion-link">
                                        Support &amp; Guidance
                                    </a>
                                </div>
                                <div class="accordion-item">
                                    <a href="student-activities.html" class="accordion-link">
                                        Student Activities
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <a class="accordion-link without-icon" href="contact-us.html">
                        Contact Us
                    </a>
                </div>
            </div>
            <div class="offcanvas-contact-info">
                <h4>Contact Info</h4>
                <ul class="contact-info list-style">
                    <li>
                        <i class="bx bxs-envelope"></i>
                        <a href="contact%40Clgunme.html">contact@Clgunme.edu</a>
                    </li>
                    <li>
                        <i class="bx bxs-time"></i>
                        <p>Mon - Fri: 9:00 - 18:00</p>
                    </li>
                </ul>
                <ul class="social-profile list-style">
                    <li><a href="https://www.fb.com/" target="_blank"><i class="bx bxl-facebook"></i></a></li>
                    <li><a href="https://www.instagram.com/" target="_blank"><i class="bx bxl-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/" target="_blank"><i class="bx bxl-linkedin"></i></a></li>
                </ul>
            </div>
            <div class="offcanvas-other-options">
                <div class="option-item">
                    <a href="contact-us.html" class="default-btn">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    
    ';
?>