<?php
    session_start();
    include('includes/dbconn.php');
    $email = $_SESSION['login'];
?>
<!-- By CodeAstro - codeastro.com -->
<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Hostel Management System</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <script type="text/javascript">
    function valid() {
    if(document.registration.password.value!= document.registration.cpassword.value){
        alert("Password and Re-Type Password Field do not match  !!");
    document.registration.cpassword.focus();
    return false;
        }
    return true;
        }
    </script>

</head>

<!-- By CodeAstro - codeastro.com -->

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- By CodeAstro - codeastro.com -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(../assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
               
                <div class="col-lg-12 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <!-- <img src="assets/images/big/icon.png" alt="wrapkit"> -->
                        </div>
                        <h2 class="mt-3 text-center">Verification</h2>
                        
                        <form class="mt-4" action="function.php" method="POST">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">OTP</label>
                                        <input class="form-control" name="otp" id="otp" type="number"
                                            placeholder="Enter OTP" required>
                                    </div>
                                </div>
                                <input type="hidden" value="<?php echo $email; ?>" id="email" />
                              
                                <div class="col-lg-12 text-center">
                                    <button type="submit" name="verify-otp" class="btn btn-block btn-dark">Submit</button>
                                </div>
                                 <div class="col-lg-12 text-center mt-2">
                                   <button type="button" id="resendOtp" class="btn btn-block btn-secondary">Didn't get the OTP? Resend</button>
                                </div>
                                <div class="col-lg-12 text-center mt-5">
                                   <a href="index.php" class="text-danger">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- By CodeAstro - codeastro.com -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader ").fadeOut();

        var sendOTP = true;
        $('#resendOtp').on('click', function(){

            var count = 120; // 2 minutes in seconds
            var button = $(this);
            button.prop("disabled", true); 

            var param = {
                'email': $('#email').val(),
                'forgot' : 1
            }

            $.ajax({
                type: "POST",
                url: "function.php",
                data: param,
                cache: false,
                dataType: "json",
                success: function(data) {

                    alert('New OTP Have Sent!');

                },
                error: function(data){
                
                }
            });

            var countdownInterval = setInterval(function(){
                count--;
                var minutes = Math.floor(count / 60);
                var seconds = count % 60;

                // Format seconds with two decimals
                var formattedSeconds = seconds < 10 ? "0" + seconds : seconds;

                // Update button text to show countdown
                button.text("Resend in: " + minutes + ":" + formattedSeconds);

                if(count === 0) {
                    clearInterval(countdownInterval);
                    button.text("Didn't get the OTP? Resend"); // Reset button text
                    button.prop("disabled", false); // Enable button after countdown
                }
            }, 1000);


        });
    </script>
</body>

</html>