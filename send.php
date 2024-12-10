<?php
    session_start();
    require "vendor/autoload.php";

    use Mongo\Client;

    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);

    $db = $client->selectDatabase('Fundom');
    $collection = $db->selectCollection('Community');

    

    if(isset($_POST['messageTXT'])) {
        $messages = $_POST['messageTXT'];
        $id = $_SESSION['id'];
        $name = $_SESSION['name'];
        $lname = $_SESSION['lastname'];
        $communityID = new MongoDB\BSON\ObjectId($_POST['communityIDInput']);

        if(isset($_FILES['image']) && $_FILES['image']['error'] ==0 && !empty($messages)) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $file = new MongoDB\BSON\Binary($imageData, MongoDB\BSON\Binary::TYPE_GENERIC);
    
            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($communityID)],
                [
                    '$push' => [
                        'Chat' => [
                            'id' => $id,
                            'name' => $name . " " . $lname,
                            'message' => $messages,
                            'image' => $file,
                            'timestamp' => new MongoDB\BSON\UTCDateTime()
                        ]
                    ]
                ]
            );
        }
        elseif(isset($_FILES['image']) && $_FILES['image']['error'] ==0 && empty($messages)){
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $file = new MongoDB\BSON\Binary($imageData, MongoDB\BSON\Binary::TYPE_GENERIC);
    
            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($communityID)],
                [
                    '$push' => [
                        'Chat' => [
                            'id' => $id,
                            'name' => $name . " " . $lname,
                            'image' => $file,
                            'timestamp' => new MongoDB\BSON\UTCDateTime()
                        ]
                    ]
                ]
            );
        }
        else{
            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($communityID)],
                [
                    '$push' => [
                        'Chat' => [
                            'id' => $id,
                            'name' => $name . " " . $lname,
                            'message' => $messages,
                            'timestamp' => new MongoDB\BSON\UTCDateTime()
                        ]
                    ]
                ]
            );
        }
    }

    header("Location: communityChat.php");
    exit();

?>