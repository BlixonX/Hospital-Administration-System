<?php
    include_once 'logic/model.php';

    loginUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Mono&display=swap');
    * { font-family: 'Ubuntu Mono', monospace; }
    </style>
    <link rel="stylesheet" href="css/login/desktop.css">
    <link rel="stylesheet" href="css/login/mobile.css" media="(max-width: 630px)">
    <title>Hospital - Login</title>
</head>
<body>
    <div class="login">
        <a href="/">Go to homepage</a>
        <form action="" method="post">
            <label for="">Username:
                <input type="text" name="login" id="" placeholder="Username" value="">
            </label>
            <label for="">Password:
                <input type="password" name="passwd" id="" placeholder="Password" value="">
            </label>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>