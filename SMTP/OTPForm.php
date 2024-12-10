<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp']; // Get OTP from the form
    if (isset($_SESSION['otp'])) {
        if ($otp == $_SESSION['otp']) { // Compare correctly
            // OTP is correct
            echo "<script>
                function myFunction(){
                    alert('OTP verified successfully! You can now reset your password.');
                }
            </script>";
            
            // Optionally clear the OTP after successful verification
            unset($_SESSION['otp']);
        } else {
            // OTP is incorrect
            echo "<script>alert('Invalid OTP. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No OTP was sent or expired. Please request a new one.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Fpass.css?v=<?php echo time(); ?>">
    <title>OTP Verification</title>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <p>Please enter OTP which you received.</p>
        <form id="forgot-password-form" action="verifyOTP.php" method="POST">
            <div class="form-group">
                <h2 for="email">OTP</h2>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            </div>
            <button type="submit" onclick="myFunction()">Verify OTP</button>
        </form>
    </div>
</body>
</html>