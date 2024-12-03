<?php
    session_start();
    require "vendor/autoload.php";

    use Mongo\Client;

    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);

    $db = $client->selectDatabase('Fundom');
    $collection = $db->selectCollection('Community');
    
    $collection_name = $_POST['community_name'];

    $creator = $_SESSION['name'];

    $collection->insertOne([
        'Community Name' => $collection_name,
        'Admin' => $creator,
        'Members' => [$creator],
        'Posts' => [],
    ]);

    header("Location: community.php");
    exit;
?>