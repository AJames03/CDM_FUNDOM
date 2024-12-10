<?php
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['lastname'])  || !isset($_SESSION['id'])) {
    header("Location: profile.php");
    exit();
}

$name = $_SESSION['name'];
$lname = $_SESSION['lastname'];
$fullname = $name . " " . $lname;
$id = $_SESSION['id'];
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

    // This is for Chat Function
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
    
    <title>Community Chat</title>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="./images/Smile Mail.png">

</head>
<body>
<div class="profileName">
        <label class="nameLabel">
            <?php echo $name ?>
            <?php echo $lname ?>
        </label>
        <label class="Small-Profile-Picture">
            <img src="data:image/jpeg;base64,<?php echo $imageBase64; ?>" class="small-profile-picture">
        </label>
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

        <div class="communitychat_Container">
            <div class="chatlist">
                <h1 id="Community-Chat-Text">Community Chat</h1>
                <div class="chatTAB">
                <?php foreach ($communities as $index => $community): ?>
                    <?php
                        $memberFound = false;
                        
                        foreach($community['Members'] as $member) {
                            if ($member['id'] == $id) {
                                $memberFound = true;
                                break;
                            }
                        }
                    ?>
                    <?php if ($memberFound): ?>
                        <button type="tablinks" class="tabLinks" onclick="openChat(event, '<?php echo 
                        $community['_id']; ?>')"
                        <?php echo $index === 0 ? 'id="defaultOpen"' : ''; ?>>
                            <label class="Small-Profile-Picture">
                                <img src="data:image/jpeg;base64,<?php echo $imageBase64; ?>" class="small-profile-picture">
                            </label>
                            <label id="Community-Name-Chat">
                                <?php echo htmlspecialchars($community['Community Name']); ?>
                            </label>
                        </button>
                    <?php else: ?>
                        <button type="tablinks" class="tabLinks" style="display: none;"></button>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
            </div>
            
            <div class="chatarea">
                
                <div class="chatContent">
                    <?php foreach ($communities as $community): ?>
                        <?php
                            $isMember1 = false;

                            foreach($community['Members'] as $members){
                                if($members['id'] == $id){
                                    $isMember1 = true;
                                    break;
                                }
                            }
                        ?>
                        <?php if ($isMember1): ?>
                            <div id="<?php echo $community['_id']; ?>-chat" class="conversation tabcontent">
                                <h3><?php echo htmlspecialchars($community['Community Name']); ?></h3>
                                <?php
                                    foreach ($community['Chat'] as $chats) {
                                        // Check if the current chat message is from the logged-in user
                                        $messageClass = ($chats['id'] == $id) ? 'my-message' : 'other-message'; // Add class for logged-in user or others
                                    ?>
                                        <div class="<?php echo $messageClass; ?>" id="message-Container">
                                            <br>
                                            <label id="users_Name"><?php echo htmlspecialchars($chats['name']); ?></label>
                                            
                                            <?php if(!isset($chats['image']) && !empty($chats['message'])): ?>
                                                <!-- Message Only -->
                                                <label id="Text-Message"><?php echo htmlspecialchars($chats['message']); ?></label>

                                            <?php elseif (isset($chats['image']) && $chats['image'] instanceof MongoDB\BSON\Binary): ?>
                                                <!-- Image Message Only -->
                                                <?php
                                                    // Get the binary image data
                                                    $imageData = $chats['image']->getData();
                                                    // Encode the binary data to base64
                                                    $imageBase64 = base64_encode($imageData);
                                                ?>
                                                <img src="data:image/jpeg;base64,<?php echo $imageBase64; ?>" alt="Image" style="max-width: 30%; height: auto;">
                                                <?php elseif (isset($chats['image']) && $chats['image'] instanceof MongoDB\BSON\Binary && !empty($chats['message'])): ?>
                                                    <!-- Case: Image and text -->
                                                    <p><?php echo htmlspecialchars($chats['message']); ?></p>
                                                    <?php
                                                        // Get the binary image data
                                                        $imageData = $chats['image']->getData();
                                                        // Encode the binary data to base64
                                                        $imageBase64 = base64_encode($imageData);
                                                    ?>
                                                <?php elseif (!empty($chats['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($chats['image']); ?>" alt="Image" style="max-width: 100%; height: auto;">
                                            <?php endif; ?>
                                        </div>
                            <?php } ?>

                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
            </div>

                <form action="send.php" method="POST" id="messageCHAT" enctype="multipart/form-data">
    
                    <input type="file" accept="image/*" name="image" id="fileIMG" style="display: none;"/>
                    <label for="fileIMG">
                        <i class="fa-solid fa-paperclip" id="paperclip"></i>
                    </label>
                    <div class="text_AREA">
                        <textarea name="messageTXT" rows="1" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';" placeholder="Aa" id="textArea"></textarea>
                    </div>
                    <input type="text" style="display: none;" name="communityIDInput" id="communityIDInput" value="<?php echo $communityID; ?>">

                    <button type="submit" id="sendBTN">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
                <script src="./JavaScript/textarea.js"></script>
            </div>

        </div>
    </div>
    <script>
        function openChat(evt, communityID) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(communityID + "-chat").style.display = "block";
            evt.currentTarget.className += " active";

            document.getElementById("communityIDInput").value = communityID;
        }
        document.getElementById("defaultOpen").click();

    </script>
</body>
</html>