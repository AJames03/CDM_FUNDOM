<?php
    session_start();
    require "vendor/autoload.php";

    use Mongo\Client;

    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);

    $db = $client->selectDatabase('Fundom');
    $collection = $db->selectCollection('Community');
    
    $collection_name = $_POST['community_name'];

    $fname = $_SESSION['name'];
    $lname = $_SESSION['lastname'];
    $creator = $fname . " " . $lname;
    $members = $fname . " " . $lname;
    $id = $_SESSION['id'];

    $collection->insertOne([
        'Community Name' => $collection_name,
        'Admin' => [
            [
                'id' => $id,
                'name' => $creator,
            ]
        ],
        'Members' => [
            [
                'id' => $id,
                'name' => $members,
            ]
        ],
        'Chat' => []
    ]);

    header("Location: community.php");
    exit;
?>