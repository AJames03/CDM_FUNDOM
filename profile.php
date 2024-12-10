<?php
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['lastname'])|| !isset($_SESSION['id'])) {
    header("Location: profile.php");
    exit();
}

$name = $_SESSION['name'];
$lname = $_SESSION['lastname'];
$id = $_SESSION['id'];
$fullname = $name . " " . $lname;

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


// This is for Updating Profile Picture
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

// List of your community
$uri = "mongodb://localhost:27017/";
$client = new MongoDB\Client($uri);

$db = $client->Fundom;
$collection = $db->Community;

// Find all communities
$communities = iterator_to_array($collection->find());

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

    <!-- Alert Deletion -->
    <script>
        function confirmDeletion() {
            return confirm('Are you sure you want to delete this community?');
        }
    </script>
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
                
                <a href="community.php" class="navLink">
                    <ion-icon name="people-outline"></ion-icon> &nbsp;
                    Join Community
                </a>
                
                <a href="profile.php" class="navLink">
                    <ion-icon name="person-circle-outline"></ion-icon> &nbsp;
                    Profile
                </a>
                
                <a href="communityChat.php" class="navLink">
                    <ion-icon name="chatbubbles-outline"></ion-icon> &nbsp;
                    Community Chat
                </a>

                <a href="changePassMain.php" class="navLink">
                    <ion-icon name="settings-outline"></ion-icon> &nbsp;
                    Change Password
                </a>

                <a href="./about/about.html" class="navLink">
                <ion-icon name="information-circle-outline"></ion-icon> &nbsp;
                    About
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
                    <input type="file" name="image" id="inputFile" onchange="uploadImg()" accept="image/*"/>
                    
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
                <h2>Your Community</h2>
                <div class="community_information">
                    <table class="table_list">
                        <tr>
                            <th>Community Name</th>
                            <th>Admins</th>
                            <th>Members</th>
                            <th>Delete</th>
                        </tr>
                            <?php foreach ($communities as $index => $community): ?>
                                <?php
                                    $memberFound = false;
                                    $isAdmin = false;
                                    
                                    foreach($community['Members'] as $member) {
                                        if (isset($member['id']) && $member['id'] == $id) {
                                            $memberFound = true;
                                            break;
                                        }
                                    }


                                    foreach($community['Admin'] as $admin) {
                                        if (isset($admin['id']) && $admin['id'] == $id) {
                                            $isAdmin = true;
                                            break;
                                        }
                                    }
                                ?>

                                <?php if ($memberFound): ?>
                                    <tr>
                                        <!-- Community Name -->
                                        <td> <?php echo htmlspecialchars($community['Community Name']); ?> </td>
                                        
                                        <!-- Number of Admins -->
                                        <td>
                                            <?php 
                                                if (isset($community['Admin']) && (is_array($community['Admin']) || $community['Admin'] instanceof MongoDB\Model\BSONArray)) {
                                                    echo count($community['Admin']); // Count of Admins
                                                } else {
                                                    echo 0;
                                                }
                                            ?>
                                        </td>

                                        <!-- Number of Admins -->
                                        <td>
                                            <?php 
                                                if (isset($community['Members']) && (is_array($community['Members']) || $community['Members'] instanceof MongoDB\Model\BSONArray)) {
                                                    echo count($community['Members']); // Count of Admins
                                                } else {
                                                    echo 0;
                                                }
                                            ?>
                                        </td>

                                        <!-- Admin Actions (Delete) -->
                                        <td>
                                            <?php 
                                                if ($isAdmin) {
                                                    echo '<form action="deleteFunction.php" method="POST">
                                                            <input type="hidden" name="community_name" value="' . htmlspecialchars($community['Community Name']) . '">
                                                            <button type="submit" id="deleteButton" onclick="return confirmDeletion();">
                                                                <ion-icon name="trash-sharp" id="deleteICON" style="color: red;"></ion-icon>
                                                            </button>
                                                        </form>';
                                                }
                                                else{
                                                    echo '<button type="button" id="deleteButton" disabled>
                                                                <ion-icon name="trash-sharp"></ion-icon>
                                                            </button>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                    </table>
                </div>
            </div>
         </div>
    </div>
</body>
</html>