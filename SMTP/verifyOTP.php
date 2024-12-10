<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userOTP = $_POST['otp'];

    if (isset($_SESSION['otp'])) {
        if ($userOTP == $_SESSION['otp']) {
            // OTP is correct
            echo "<script> window.location.href='resetPass.html'; </script>";
            
            // Optionally clear the OTP after successful verification
            unset($_SESSION['otp']);
        } else {
            // OTP is incorrect
            echo "<script> alert('Invalid OTP. Please try again.'); window.location.href='OTPForm.php'; </script>";
        }
    } else {
        echo "<script> alert('No OTP was sent or expired. Please request a new one.'); window.location.href='OTPForm.php'; </script>";
    }
}
?>
