<?php
    session_start();
    require "vendor/autoload.php";

    use Mongo\Client;

    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);

    $db = $client->selectDatabase('Fundom');
    $collection = $db->selectCollection('Community');
    
    $collection_name = $_POST['community_name'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['community_name'])) {
        $communityName = $_POST['community_name'];
    
        try {
            // Maghanap at mag delete mula sa database
            $deleteResult = $db->Community->deleteOne(['Community Name' => $communityName]);
    
            if ($deleteResult->getDeletedCount() > 0) {
                // Successful deletion
                header("Location: profile.php?message=deleted");
                exit();
            } else {
                // If no document was deleted
                header("Location: profile.php?message=error");
                exit();
            }
        } catch (Exception $e) {
            // Handle exception in case of DB errors
            echo "Error: " . $e->getMessage();
        }
    } else {
        header("Location: profile.php");
        exit();
    }
?>