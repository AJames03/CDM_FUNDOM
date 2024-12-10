<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Fpass.css?v=<?php echo time(); ?>">

    <!-- For Fonts Style-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Pre:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- For ICON-->
    <link rel="icon" type="image/x-icon" href="../images/Smile Mail.png">

    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <p>Please enter your email address to receive a password reset link.</p>
        <form id="forgot-password-form" action="" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>

<!-- PHP part for SMTP -->

<?php

session_start();

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['email'];
    $mail = new PHPMailer(true);

    try{
        $otp = rand(1000, 9999);
        $_SESSION['otp'] = $otp;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'betinolaronjames529@gmail.com';
        $mail->Password = 'opgo jgyg lbij jjks';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;


        $mail->setFrom('betinolaronjames529@gmail.com', 'Fundom Admin');
        $mail->addAddress($username);
        $mail->isHTML(true);
        $mail->Body = 
        '<p>Your OTP: </p> <h2>'. $otp .'</h2>
        <p>Kindly use this OTP to reset your password.</p>
        <p>Click on the link below to reset your password.</p>
        <a href="http://localhost/project/SMTP/OTPForm.php" style="
        text-decoration: none;
        background-color: #4169e1 ;
        color: white;
        padding: 10px;
        font-size: 20px;
        ">Reset Password</a>
        ';

        $mail->send();

        $mail->SMTPDebug = 1;
    }
    catch(Exception $e){
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
