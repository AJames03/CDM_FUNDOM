<?php
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['lastname'])) {
    header("Location: loginForm.html");
    exit();
}

$name = $_SESSION['name'];
$lname = $_SESSION['lastname'];
    // THIS IS FOR UPPER PROFILE PICTURE 
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nf.css?v=<?php echo time(); ?>">
    
    <title>Fundom</title>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>
    <link rel="icon" type="image/x-icon" href="./images/Smile Mail.png">
</head>
<body>
    <div class="profileName">
        <label class="nameLabel">
            <?php echo $name ?>
            <?php echo $lname ?>
        </label>
        <img src="data:image/jpeg;base64,<?php echo $imageBase64; ?>" class="small-profile-picture">
    </div>

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

        <!-- Content -->
         <div class="newsfeed_container"></div>

        <!-- This is for Your Post -->
        <div class="your_post"></div>
    </div>
</body>
</html>