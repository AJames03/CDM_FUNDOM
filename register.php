<?php
    require_once 'security/config.php';
    require_once 'security/encryption.php';
    require "database.php";

    $firstName = $_POST['FN'];
    $lastName = $_POST['LN'];
    $gender = $_POST['gen'];
    $bday = $_POST['bday'];
    $user = $_POST['UN'];
    $pass = $_POST['psw'];
    $cpass = $_POST['cpsw'];

    // This is for Encrypt the Password
    $key = ENCRYPTION_KEY;
    $encryptPass = encryptdata($pass, $key);

    $profileImg = "./images/default_profile.png";

    $insertResult = $collection->insertOne([
        'First Name' => $firstName,
        'Last Name' => $lastName,
        'Gender' => $gender,
        'Birthday' => $bday,
        'Username' => $user,
        'Password' => $encryptPass,
        'Profile Picture' => $profileImg
    ]);
    
    $insertID = $insertResult->getInsertedId();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Edu+AU+VIC+WA+NT+Pre:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="regSuc.css">
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>Registration Successful</h1>
            <p>
                Welcome to Fundom, 
                <label>
                    <?php echo $firstName ?>
                </label>
                ! you can now join to CDM community.
            </p>
            <img src="./images/Smile Mail.png">
        </div>
        <a href="loginForm.html">Go to Login</a>
    </div>
</body>
</html> 