<?php
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['lastname'])) {
    header("Location: loginForm.html");
    exit();
}

$name = $_SESSION['name'];
$lname = $_SESSION['lastname'];

require 'database.php';

$userdata = $collection->findOne(['First Name' => $name]);

// This is for display the profile picture
if (isset($userdata['Profile Picture']) && $userdata['Profile Picture'] instanceof MongoDB\BSON\Binary) {
    // This is for displaying the profile picture
    $image = $userdata['Profile Picture']->getData(); // Get the image data as binary data
    $imageBase64 = base64_encode($image);

    // Create an image tag to display the image
} else {
    echo "No profile picture available.";
}



if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])){ 
    $image = $_FILES['image']['tmp_name'];  // Get the uploaded image
    $imageData = file_get_contents($image); // Read the image as binary data

    $updateData =[
        'Profile Picture' => new MongoDB\BSON\Binary($imageData, MongoDB\BSON\BINARY::TYPE_GENERIC)
    ];

    $filter = ['First Name' => $name, 'Last Name' => $lname];

    $collection->updateOne(
        $filter,
        ['$set' => $updateData]
    );

    header("Location: profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nf.css?v=<?php echo time(); ?>">

    <title>Profile <?php echo $name; echo " "; echo $lname; ?></title>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>
    <link rel="icon" type="image/x-icon" href="./images/Smile Mail.png">
</head>
<body>

    <div class="container">

        <!-- Navigation Bar and Icon -->
        <div class="navigation">
            <div class="FDIcon">
                <img src="./images/Smile Mail.png" id="imgICON">
                <h2>FUNDOM</h2>
            </div>

            <!-- Navigation Bar -->
            <nav>
            <a href="newsfeed.php" class="navLink">
                    <ion-icon name="home-outline"></ion-icon> &nbsp;
                    Home
                </a>
                
                <a href="community.php" class="navLink">
                    <ion-icon name="people-outline"></ion-icon> &nbsp;
                    Join Community
                </a>
                
                <a href="profile.php" class="navLink">
                    <ion-icon name="person-circle-outline"></ion-icon> &nbsp;
                    Profile
                </a>
            </nav>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>

        <!-- Your Profile -->
         <div class="profileContainer">
            <div class="profileINFO">
                <div class="dpIMG">
                    <img src="data:image/jpeg;base64,<?php echo $imageBase64; ?>" class="profile-picture">    
                </div>
                <label class="profileNameLabel">
                    <?php echo $name ?>
                    <?php echo $lname ?>
                </label>
                <form action="profile.php" method="post" enctype="multipart/form-data" id="uploadForm">
                    <input type="file" name="image" id="inputFile" onchange="uploadImg()"/>
                    
                    <label for="inputFile">
                        <ion-icon name="camera-outline" class="custom-file-label"></ion-icon>
                    </label>
                </form>

                <script>
                    function uploadImg(){
                        const fileInput = document.getElementById('inputFile');
                        if (fileInput.files.length > 0) {
                            // Automatically submit the form if a file is selected
                            document.getElementById('uploadForm').submit();
                        }
                    }
                </script>
            </div>
            
            <div class="yourCommunity">

            </div>
         </div>
    </div>
</body>
</html>