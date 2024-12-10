<?php
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['lastname']) || !isset($_SESSION['id'])) {
    header("Location: profile.php");
    exit();
}

require_once 'security/config.php';
require_once 'security/encryption.php';
require "database.php";

use MongoDB\BSON\ObjectId;

$fullname = $_SESSION['name'] . " " . $_SESSION['lastname'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['id'];
    $password = $_POST['oldPassword'];
    echo "Password from POST: " . $userID . "<br>";
    
    try {
        $encryptedPasswordResponse = $collection->findOne(['_id' => new ObjectId($userID)], ['projection' => ['Password' => 1]]);

        
        echo "Encrypted Database Response: ";
        

        if ($encryptedPasswordResponse) {
            $decryptedPassword = decryptdata($encryptedPasswordResponse['Password'], ENCRYPTION_KEY);

            echo "Decrypted Password: ";
            var_dump($decryptedPassword);

            echo "Password Input from Form: ";
            var_dump($password);

            if (trim($decryptedPassword) === trim($password)) {
                echo "Match Found!";
                header("Location: ./SMTP/resetPass.html");
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href = 'changePassMain.php';</script>";
                exit();
            }
        } else {
            echo "User not found.";
            exit();
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="changePass.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="forgot-password-form">
            <div class="form-header">
                <i class="fas fa-lock"></i>
                <h2>Forget Password</h2>
            </div>
            <p class="description">Please enter your old password to continue</p>
            
            <form action="changePassMain.php" method="POST">
                <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($_SESSION['id'] ?? ''); ?>">
                <div class="form-group">
                    <div class="input-group">
                        <i class="fas fa-key"></i>
                        <input type="password" id="oldPassword" name="oldPassword" placeholder="Enter Old Password" required>
                        
                        <button type="button" class="show-password-btn">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>   
                <a href="./SMTP/forgetPass.php" class="forgot-password-link">Forgot Password?</a>
                <button type="submit" class="reset-btn">
                    <span>Reset Password</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="links">
                <a href="profile.php"><i class="fas fa-chevron-left"></i> Back to Login</a>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.show-password-btn').addEventListener('click', function() {
            const passwordInput = document.querySelector('#oldPassword');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>