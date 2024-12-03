
<!-- Don't insert anything in this database.php because it is our main connection -->

<?php
    require "vendor/autoload.php";

    use Mongo\Client;

    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);

    $db = $client->selectDatabase('Fundom');
    $collection = $db->selectCollection('users');
?>