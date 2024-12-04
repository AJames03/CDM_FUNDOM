<?php
    session_start();
    if (!isset($_SESSION['name']) || !isset($_SESSION['lastname'])) {
        header("Location: loginForm.html");
        exit();
    }
    
    $name = $_SESSION['name'];
    $lname = $_SESSION['lastname'];

    require "vendor/autoload.php";
    use MongoDB\Client;
    
    $uri = "mongodb://localhost:27017/";
    $client = new MongoDB\Client($uri);
    
    $db = $client->Fundom;
    $collection = $db->Community;
    
    // Find all communities

    $communities = $collection->find();

    // Join the Community
    
    // Find the community by name
    if (isset($_POST['join_community'])) {
        $collection_name = $_POST['community_name'];
        $user = $_SESSION['name'];
    
        // Find the community by name
        $existingCommunity = $collection->findOne(['Community Name' => $collection_name]);
    
        if ($existingCommunity) {
            // Check if user is already a member
            if (!in_array($user, (array)$existingCommunity['Members'])) {
                $collection->updateOne(
                    ['Community Name' => $collection_name], // Find community
                    ['$push' => ['Members' => $user]]       // Add user to Members array
                );
            }
        }
    }

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
    
    <title>Fundom Community</title>
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

        <!-- Community List and Join Community -->
        <div class="community_content">
            <h2 id="community_Name">Community</h2>
            <div class="community_list_container">
                <?php foreach ($communities as $community): ?>
                    <div class="community_list">
                        <label class="community">
                            <?php echo htmlspecialchars($community['Community Name']); ?>
                        </label>
                        <form action="community.php" method="POST" class="join_community">
                            <input type="hidden" name="community_name" value="<?php echo htmlspecialchars($community['Community Name']); ?>">
                            <button type="submit" name="join_community" class="join_button">Join</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Add Community -->
         <div class="add_community">
            <h2>Create New Community</h2>
                <form action="functionCommunity.php" method="POST" class="fillup_new_community">
                    <input type="text" name="community_name" required>
                    <label class="label_create_community">Create a Community Name</label>
                    <input type="submit" value="Create Community" id="communityBTN">
                </form>
         </div>
    </div>
</body>
</html>