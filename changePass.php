<?php
    session_start();
    
    require_once 'security/config.php';
    require_once 'security/encryption.php';
    require "database.php";

    $username = $_SESSION['name'] . " " . $_SESSION['lastname'];
    $userID = $collection->findOne(['_id' => $_SESSION['id']]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['oldPassword'];
        echo "Password from POST: " . $password . "<br>";
        
        $user = $collection->findOne(['Username' => $username]);
        if ($user) {
            echo $userID;
            var_dump($user);
            
            $decryptedPassword = decryptdata($user['Password'], ENCRYPTION_KEY);
            echo "Decrypted Password: " . $decryptedPassword . "<br>";
            
            if ($password === $decryptedPassword) {
                echo "Password match!";
                header("Location: ../SMTP/resetPass.html");
                exit();
            } else {
                echo "Incorrect password.";
                exit();
            }
        } else {
            echo;
            exit();
        }
    }
    
?>
