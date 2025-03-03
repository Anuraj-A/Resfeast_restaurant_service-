
<?php
// Include necessary files like database connection or functions
// For example: include 'db_connection.php'; or similar.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Validate the email address
    if (empty($email)) {
        echo "<script>alert('Please enter your email address.');</script>";
    } else {
        // Prepare to check if the email exists in the database
        $conn = new mysqli('localhost', 'root', '', 'resfeast');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);  // "s" means it's a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, now proceed with sending a password reset email
            $user = $result->fetch_assoc();

            // Here, you can generate a token and save it in the database for validation later
            $token = bin2hex(random_bytes(50)); // Secure token generation
            $expire_time = date("U") + 3600; // Set expiration time (1 hour)

            // Save the token and expiration time in the database
            $update_sql = "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sis", $token, $expire_time, $email);
            $update_stmt->execute();

            // Send password reset email with the link
            $reset_link = "http://yourdomain.com/reset-password.php?token=$token";  // Adjust domain

            $subject = "Password Reset Request";
            $message = "We received a request to reset your password. Click the link below to reset it: \n\n" . $reset_link;
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>alert('A password reset link has been sent to your email address.');</script>";
            } else {
                echo "<script>alert('Failed to send reset email. Please try again later.');</script>";
            }
        } else {
            echo "<script>alert('No account found with that email address.');</script>";
        }

        // Close database connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/themify/themify-icons.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/lightbox2/css/lightbox.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>

<body class="animsition">

    <!-- Header -->

    <header>
        <!-- Header desktop -->
        <div class="wrap-menu-header gradient1 trans-0-4">
            <div class="container h-full">
                <div class="wrap_header trans-0-3">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="index.php">
                            <img src="images/icons/logo.png" alt="IMG-LOGO" data-logofixed="images/icons/logo2.png">
                        </a>
                    </div>

                    <!-- Menu -->
                    <div class="wrap_menu p-l-45 p-l-0-xl">
                        <nav class="menu">
                            <ul class="main_menu">
                                <li>
                                    <a href="index.php">Home</a>
                                </li>

                                <li>
                                    <a href="menu.php">Menu</a>
                                </li>

                                <li>
                                    <a href="reservation.php">Reservation</a>
                                </li>

                                <li>
                                    <a href="gallery.php">Gallery</a>
                                </li>

                                <li>
                                    <a href="about.php">About</a>
                                </li>

                                <li>
                                    <a href="blog.php">Blog</a>
                                </li>

                                <li>
                                    <a href="contact.php">Contact</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Social -->
                    <div class="social flex-w flex-l-m p-r-20">
                        <a href="login.php"><i class="fa fa-user" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-facebook m-l-21" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-twitter m-l-21" aria-hidden="true"></i></a>

                        <button class="btn-show-sidebar m-l-33 trans-0-4"></button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Sidebar -->
    <aside class="sidebar trans-0-4">
        <!-- Button Hide sidebar -->
        <button class="btn-hide-sidebar ti-close color0-hov trans-0-4"></button>

        <!-- - -->
        <ul class="menu-sidebar p-t-95 p-b-70">
            <li class="t-center m-b-13">
                <a href="index.php" class="txt19">Home</a>
            </li>

            <li class="t-center m-b-13">
                <a href="menu.php" class="txt19">Menu</a>
            </li>

            <li class="t-center m-b-13">
                <a href="gallery.php" class="txt19">Gallery</a>
            </li>

            <li class="t-center m-b-13">
                <a href="about.php" class="txt19">About</a>
            </li>

            <li class="t-center m-b-13">
                <a href="blog.php" class="txt19">Blog</a>
            </li>

            <li class="t-center m-b-33">
                <a href="contact.php" class="txt19">Contact</a>
            </li>

            <li class="t-center">
                <!-- Button3 -->
                <a href="reservation.php" class="btn3 flex-c-m size13 txt11 trans-0-4 m-l-r-auto">
                    Reservation
                </a>
            </li>
        </ul>

        <!-- - -->
        <div class="gallery-sidebar t-center p-l-60 p-r-60 p-b-40">
            <!-- - -->
            <h4 class="txt20 m-b-33">
                Gallery
            </h4>

            <!-- Gallery -->
            <div class="wrap-gallery-sidebar flex-w">
                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-01.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-01.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-02.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-02.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-03.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-03.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-05.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-05.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-06.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-06.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-07.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-07.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-09.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-09.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-10.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-10.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-11.jpg"
                    data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-11.jpg" alt="GALLERY">
                </a>
            </div>
        </div>
    </aside>
    <!-- Welcome -->
    <!-- Our menu -->
    <!-- Event -->

    <!-- Booking -->
    <section class="section-booking bg1-pattern p-t-100 p-b-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 p-b-30">
                    <div class="t-center">
                        <span class="tit2 t-center">
                            Resfeast
                        </span>

                        <h3 class="tit3 t-center m-b-35 m-t-2">
                            Reset Password
                        </h3>
                    </div>

                    <form class="wrap-form-booking" method="POST" action="forgot-password.php">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Email -->
                                <label for="email" class="txt9">Email</label>
                                <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                                    <input id="email" class="bo-rad-10 sizefull txt10 p-l-20" type="email" name="email" placeholder="Enter your email" required>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; align-items: center;" class="wrap-btn-booking flex-c-m m-t-6">
                            <!-- Submit Button -->
                            <button type="submit" class="btn3 flex-c-m size13 txt11 trans-0-4">
                                Send Reset Link
                            </button>
                            <p>Remembered your password? <a href="login.php">Login here</a></p>
                        </div>
                    </form>


                </div>

                <div class="col-lg-6 p-b-30 p-t-18">
                    <div class="wrap-pic-booking size2 bo-rad-10 hov-img-zoom m-l-r-auto">
                        <img src="images/booking-01.jpg" alt="IMG-OUR">
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Footer -->
    <footer class="bg1">
        <div class="container p-t-40 p-b-70">
            <div class="row">
                <div class="col-sm-6 col-md-4 p-t-50">
                    <!-- - -->
                    <h4 class="txt13 m-b-33">
                        Contact Us
                    </h4>

                    <ul class="m-b-70">
                        <li class="txt14 m-b-14">
                            <i class="fa fa-map-marker fs-16 dis-inline-block size19" aria-hidden="true"></i>
                            2nd floor, sreevalsam housing, mangattuparamb, pin 19023
                        </li>

                        <li class="txt14 m-b-14">
                            <i class="fa fa-phone fs-16 dis-inline-block size19" aria-hidden="true"></i>
                            (+1) 96 716 6879
                        </li>

                        <li class="txt14 m-b-14">
                            <i class="fa fa-envelope fs-13 dis-inline-block size19" aria-hidden="true"></i>
                            contact@site.com
                        </li>
                    </ul>

                    <!-- - -->
                    <h4 class="txt13 m-b-32">
                        Opening Times
                    </h4>

                    <ul>
                        <li class="txt14">
                            09:30 AM – 11:00 PM
                        </li>

                        <li class="txt14">
                            Every Day
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-md-4 p-t-50">
                    <!-- - -->
                    <h4 class="txt13 m-b-33">
                        Latest twitter
                    </h4>

                    <div class="m-b-25">
                        <span class="fs-13 color2 m-r-5">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </span>
                        <a href="#" class="txt15">
                            @colorlib
                        </a>

                        <p class="txt14 m-b-18">
                            Activello is a good option. It has a slider built into that displays the featured image in
                            the slider.
                            <a href="#" class="txt15">
                                https://buff.ly/2zaSfAQ
                            </a>
                        </p>

                        <span class="txt16">
                            21 Dec 2017
                        </span>
                    </div>

                    <div>
                        <span class="fs-13 color2 m-r-5">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </span>
                        <a href="#" class="txt15">
                            @colorlib
                        </a>

                        <p class="txt14 m-b-18">
                            Activello is a good option. It has a slider built into that displays
                            <a href="#" class="txt15">
                                https://buff.ly/2zaSfAQ
                            </a>
                        </p>

                        <span class="txt16">
                            21 Dec 2017
                        </span>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 p-t-50">
                    <!-- - -->
                    <h4 class="txt13 m-b-38">
                        Gallery
                    </h4>

                    <!-- Gallery footer -->
                    <div class="wrap-gallery-footer flex-w">
                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-01.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-01.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-02.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-02.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-03.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-03.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-04.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-04.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-05.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-05.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-06.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-06.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-07.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-07.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-08.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-08.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-09.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-09.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-10.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-10.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-11.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-11.jpg" alt="GALLERY">
                        </a>

                        <a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-12.jpg"
                            data-lightbox="gallery-footer">
                            <img src="images/photo-gallery-thumb-12.jpg" alt="GALLERY">
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="end-footer bg2">
            <div class="container">
                <div class="flex-sb-m flex-w p-t-22 p-b-22">
                    <div class="p-t-5 p-b-5">
                        <a href="#" class="fs-15 c-white"><i class="fa fa-tripadvisor" aria-hidden="true"></i></a>
                        <a href="#" class="fs-15 c-white"><i class="fa fa-facebook m-l-18" aria-hidden="true"></i></a>
                        <a href="#" class="fs-15 c-white"><i class="fa fa-twitter m-l-18" aria-hidden="true"></i></a>
                    </div>

                    <div class="txt17 p-r-20 p-t-5 p-b-5">
                        Copyright &copy; 2018 All rights reserved | This template is made with <i
                            class="fa fa-heart"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- Back to top -->
    <div class="btn-back-to-top bg0-hov" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="fa fa-angle-double-up" aria-hidden="true"></i>
        </span>
    </div>

    <!-- Container Selection1 -->
    <div id="dropDownSelect1"></div>

    <!-- Modal Video 01-->
    <div class="modal fade" id="modal-video-01" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="modal-dialog" role="document" data-dismiss="modal">
            <div class="close-mo-video-01 trans-0-4" data-dismiss="modal" aria-label="Close">&times;</div>

            <div class="wrap-video-mo-01">
                <div class="w-full wrap-pic-w op-0-0"><img src="images/icons/video-16-9.jpg" alt="IMG"></div>
                <div class="video-mo-01">
                    <iframe src="https://www.youtube.com/embed/5k1hSu2gdKE?rel=0&amp;showinfo=0"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>



    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/bootstrap/js/popper.js"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/slick/slick.min.js"></script>
    <script type="text/javascript" src="js/slick-custom.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/parallax100/parallax100.js"></script>
    <script type="text/javascript">
        $('.parallax100').parallax100();
    </script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script type="text/javascript" src="vendor/lightbox2/js/lightbox.min.js"></script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>

</html>
