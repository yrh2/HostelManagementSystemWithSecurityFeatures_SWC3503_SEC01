<?php
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';

	session_start();
    include('includes/dbconn.php');
    // include('loger.php');
    if(isset($_POST['login']))
    {
	    $email=$_POST['email'];
	    $pw=$_POST['password'];

	    $stmt=$mysqli->prepare("SELECT email,password,id FROM userregistration WHERE email=? ");
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt -> bind_result($email,$password,$id);
	    $rs=$stmt->fetch();
	    $stmt->close();
        $_SESSION['id']=$id;
        $_SESSION['login']=$email;
        $_SESSION['isVerify']= false;

        $uip=$_SERVER['REMOTE_ADDR'];
        $ldate=date('d/m/Y h:i:s', time());
        if($rs){

        	if (password_verify($pw, $password)) {
	        	$otp = generateRandomOTP();
	        	$sendEmail = sendEmail($email, $otp);
	        	date_default_timezone_set("Asia/Kuala_Lumpur");
	        	$current = date('Y-m-d H:i:s'); 
	        	if($sendEmail){
	        		$query="INSERT into verification (user_id, verification_code, created_at) values(?,?,?)";
				    $stmt = $mysqli->prepare($query);
				    $rc = $stmt->bind_param('iis',$id,$otp, $current);
				    $stmt->execute();
				    echo "<script>alert('Email sent with Verification OTP!'); window.location.href='verification.php';</script>";
	        	}else{
	        		$_SESSION['isVerify']= false;
	        		echo "<script>alert('Sorry, Invalid Send Email!'); window.location.href='index.php';</script>";
	        	}
	        }else{

	        	$_SESSION['isVerify']= false;
	    		echo "<script>alert('Sorry, Invalid Username/Email or Password!'); window.location.href='index.php';</script>";

	        }

           
	    }else{
	    	$_SESSION['isVerify']= false;
	    	echo "<script>alert('Sorry, Invalid Username/Email or Password!'); window.location.href='index.php';</script>";
	    }
	}

	if(isset($_POST['forgot'])){

		$email = $_POST['email'];
		$id = $_SESSION['id'];
		$otp = generateRandomOTP();
    	$sendEmail = sendEmail($email, $otp);
    	date_default_timezone_set("Asia/Kuala_Lumpur");
    	$current = date('Y-m-d H:i:s'); 
    	if($sendEmail){
    		$query="INSERT into verification (user_id, verification_code, created_at) values(?,?,?)";
		    $stmt = $mysqli->prepare($query);
		    $rc = $stmt->bind_param('iis',$id,$otp, $current);
		    $stmt->execute();
		    returnSuccess('New OTP sent!');
    	}else{
    		$_SESSION['isVerify']= false;
    		returnErr('Failed to send new OTP!');
    	}


	}

	if(isset($_POST['verify-otp'])){	

		$id = $_SESSION['id'];
		$otp = $_POST['otp'];
		$stmt = $mysqli->prepare("SELECT verification_code, status FROM verification WHERE user_id=? ORDER BY created_at DESC LIMIT 1");
	    $stmt->bind_param('i', $id);
	    $stmt->execute();
	    $stmt->bind_result($verification_code, $status);
	    $stmt->fetch();
	    $stmt->close();

	    // Verify the OTP and check its status
	    if ($otp == $verification_code && $status == 0) {
	        // Update the OTP status to 1 (verified)
	        $stmt = $mysqli->prepare("UPDATE verification SET status=1 WHERE user_id=? AND verification_code=?");
	        $stmt->bind_param('ii', $id, $otp);
	        $stmt->execute();
	        $stmt->close();


            $uemail=$_POST['email'];
	        $ip=$_SERVER['REMOTE_ADDR'];
	        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
	        $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
	        $city = $addrDetailsArr['geoplugin_city'];
	        $country = $addrDetailsArr['geoplugin_countryName'];
	        $log="insert into userLog(userId,userEmail,userIp,city,country) values('$id','$uemail','$ip','$city','$country')";
	        $mysqli->query($log);
	       
	        echo "<script>alert('Verification successful!'); window.location.href='student/dashboard.php';</script>";
	    } else {
	        if ($status == 1) {
	            echo "<script>alert('This OTP has already been used.'); window.location.href='index.php';</script>";
	        } else {
	            echo "<script>alert('Invalid OTP. Please try again.'); window.location.href='verification.php';</script>";
	        }
	    }
	}

	function generateRandomOTP($length = 6) {
	    // Define the character set to choose from
	    $characters = '0123456789';
	    $otp = '';

	    // Get the total length of the character set
	    $charLength = strlen($characters);

	    // Generate random characters until the password length is reached
	    for ($i = 0; $i < $length; $i++) {
	        // Pick a random character from the character set
	        $randomChar = $characters[rand(0, $charLength - 1)];
	        // Append it to the password
	        $otp .= $randomChar;
	    }

	    return $otp;
	}

	function sendEmail($email, $randomOTP){

    	$mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Google's SMTP server
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = 'adrakoir@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'bjal uygc lsqu zwba'; // Replace with your app password

        $mail->setFrom('adrakoir@gmail.com');
        $mail->addAddress($email);
        $mail->Subject = 'Login Verification';
        $mail->Body = "The OTP number is : $randomOTP . Please enter to the otp to login to account!
		";

		if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
	}

	function returnSuccess($msg){

	  echo json_encode(array(
	    'msg' => $msg,
	    'status' => true
	  ));

	  exit();

	}


	function returnErr($msg){


	  echo json_encode(array(
	    'msg' => $msg,
	    'status' => false
	  ));

	  exit();

	}



?>