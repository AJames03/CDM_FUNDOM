<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: loginForm.html");
    exit();
}

$name = $_SESSION['name'];

require 'database.php';

$userdata = $collection->findOne(['First Name' => $name]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nf.css?v=<?php echo time(); ?>">

    <title>Profile <?php echo $name; ?></title>
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
            <a href="newsfeed.php">
                    <ion-icon name="home-outline"></ion-icon> &nbsp;
                    Home
                </a>
                
                <a href="community.php">
                    <ion-icon name="people-outline"></ion-icon> &nbsp;
                    Join Community
                </a>
                
                <a href="profile.php">
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
                <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-picture">
                <label class="profileNameLabel">
                    <?php echo $name ?>
                </label>
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profilePic" id="profilePic" accept="image/*" required>
                    <button type="submit" class="update-btn">Update</button>
                </form>
            </div>
            
            <div class="yourCommunity">

            </div>
         </div>
    </div>
</body>
</html>