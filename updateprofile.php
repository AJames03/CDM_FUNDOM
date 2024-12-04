<?php
    session_start();
    if (!isset($_SESSION['name'])) {
        header("Location: loginForm.html");
        exit();
    }

    require "database.php";

    $name = $_SESSION['name'];
    
    echo $name;

    $filter = ['First Name' => $name];

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])){ 
        $image = $_FILES['image']['tmp_name'];  // Get the uploaded image
        $imageData = file_get_contents($image); // Read the image as binary data


        $updateData =[
            '$set' => [
                'Profile Picture' => new MongoDB\BSON\Binary($imageData, MongoDB\BSON\BINARY::TYPE_GENERIC)
            ]
            
        ];

        $collection->updateOne($filter, $updateData);

        echo "Image uploaded successfully for user ID: $name!";
    }

    $document = $collection->findOne($filter);

    $image = $document['Profile Picture']->getData(); // Get the image data as binary data

    $imageBase64 = base64_encode($image);

    // Create an image tag to display the image
    $imageDisplay = '<img src="data:image/jpeg;base64,' . $imageBase64 . '" alt="Profile Picture" />';

    echo $imageDisplay;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>
    <link rel="icon" type="image/x-icon" href="./images/Smile Mail.png">
    <link rel="stylesheet" href="update.css?v=<?php echo time(); ?>">
    <title>Fundom Change Profile</title>
</head>
<body>
    <div class="container">
    <h2>Change Profile</h2>
        
        <form action="updateprofile.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" />
            <button type="submit">Upload Image</button>
        </form>
    </div>
</body>
</html>