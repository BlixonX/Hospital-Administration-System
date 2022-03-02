<?php
    include_once "logic/model.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="setup/setup-desktop.css">
    <link rel="stylesheet" href="setup/setup-mobile.css" media="(max-width: 630px)">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <fieldset>
            <legend align="center">First Time Admin Account Setup</legend>
            <input type="text" name="fname" placeholder="First Name">
            <input type="text" name="lname" placeholder="Last Name">
            <input type="tel" name="phone" placeholder="Phone Number">
            <input type="text" name="login" placeholder="Login">
            <input type="password" name="passwd" placeholder="Password">
            <input type="submit" value="Submit">
<?php
if(isset($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['login'], $_POST['passwd']))
{
    if($_POST['fname'] == "" || $_POST['lname'] == "" || $_POST['phone'] == "" || $_POST['login'] == "" || $_POST['passwd'] == "")
        echo '<p class="error">Not all fields were filled!</p>';
    else if (strlen($_POST['passwd']) < 3)
        echo '<p class="error">Password too short! Minimum 3 characters!</p>';
    else
    {
        $db->query("CREATE TABLE users (ID INT UNSIGNED AUTO_INCREMENT NOT NULL, FirstName VARCHAR(255) NOT NULL, LastName VARCHAR(255) NOT NULL, PhoneNumber VARCHAR(255) DEFAULT NULL, Login VARCHAR(255) NOT NULL, Password VARCHAR(255) NOT NULL, Type VARCHAR(31) NOT NULL DEFAULT 'Patient', PRIMARY KEY (ID) )");
        $db->query("CREATE TABLE appointments ( ID INT UNSIGNED AUTO_INCREMENT NOT NULL, DoctorID INT NOT NULL, PatientID INT NOT NULL, Date DATE NOT NULL, Start TIME NOT NULL, End TIME NOT NULL, Title VARCHAR(255) NOT NULL, PRIMARY KEY (ID) )");
        $db->query("INSERT INTO users (FirstName, LastName, PhoneNumber, Login, Password, Type) VALUES ('".$_POST['fname']."','".$_POST['lname']."','".$_POST['phone']."','".$_POST['login']."','".password_hash($_POST['passwd'], PASSWORD_DEFAULT)."',"."'Admin'".")");

        $htaContent = file_get_contents("setup/postsetup.htaccess");
        file_put_contents(".htaccess", $htaContent);

        array_map('unlink', glob("setup/*.*"));
        rmdir("setup");
        unlink("setup.php");

        header("Location: /");
    }
}
?>
        </fieldset>
    </form>
    <p class="note">Setup doesn't contain any security checks such as (space checking in inputs, SQL Injection, etc.)</p>
</body>
</html>