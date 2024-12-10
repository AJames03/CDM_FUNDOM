<?php
    session_start();
    if (!isset($_SESSION['name']) || !isset($_SESSION['lastname']) || !isset($_SESSION['id'])) {
        header("Location: loginForm.html");
        exit();
    }

    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);

    $db = $client->selectDatabase('Fundom');
    $collection = $db->selectCollection('post');

    $txtPOST = $_POST['txt'];
    $fname = $_SESSION['name'];
    $lname = $_SESSION['lastname'];
    $ID = $_SESSION['id'];
    $userPOST = $fname . " " . $lname;
    $imgPost => new MongoDB\BSON\Binary($imageData, MongoDB\BSON\BINARY::TYPE_GENERIC);

    
?>