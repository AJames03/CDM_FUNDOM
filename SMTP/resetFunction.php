<?php
session_start();
    require_once '../security/config.php';
    require_once '../security/encryption.php';
    require "../database.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        if($password !== $confirmPassword){
            echo "<script>alert('Passwords do not match.'); window.location.href='resetPass.html';</script>";
        }
        $user = $collection->findOne(['Username' => $username]);
        if($user){
            $key = ENCRYPTION_KEY;
            $newPassword = encryptdata($password, $key);
            $collection->updateOne(
                ['Username' => $username],
                ['$set' => ['Password' => $newPassword]]
            );
            echo "<script>alert('Password reset successfully.'); window.location.href='../loginForm.html';</script>";
        }
        else{
            echo "<script>alert('User not found.'); window.location.href='resetPass.html';</script>";
        }
    }
?>