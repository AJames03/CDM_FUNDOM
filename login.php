<?php
    session_start();
    
    require_once 'security/config.php';
    require_once 'security/encryption.php';
    require "database.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Find the user in the MongoDB collection
        $user = $collection->findOne(['Username' => $username]);
    
        if ($user) {
            // Decrypt Data
            $decryptedPassword = decryptdata($user['Password'], ENCRYPTION_KEY);

            // Compare the input password with the stored hashed password
            if ($password === $decryptedPassword){
                
                // Set the session variables
                $_SESSION['name'] = $user['First Name'];
                $_SESSION['lastname'] = $user['Last Name'];
                $_SESSION['id'] = $user['_id'];
                $_SESSION['username'] = $user['Username'];

                var_dump($_SESSION['name']);
                header("Location: Profile.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
    }
?>
