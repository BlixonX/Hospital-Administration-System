<?php
$db = new mysqli('localhost', 'root', '', 'hospital');

function addUser()
{
    if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['phone']) && isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['type']))
    {
        if(strlen($_POST['passwd']) < 3)
        {
            echo "<p>Password too short! Minimum 3 characters!</p>";
            return;
        }

        global $db;

        $check = $db->prepare("SELECT * FROM users WHERE Login = ? ;");
        $str = $db->real_escape_string($_POST['login']);
        $check->bind_param("s", $str);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0)
        {
            echo "<p>User with same login exists!</p>";
            return;
        }
        else
        {
            $fname  = $db->real_escape_string($_POST['fname']);
            $lname  = $db->real_escape_string($_POST['lname']);
            $phone  = $db->real_escape_string($_POST['phone']);
            $login  = $db->real_escape_string($_POST['login']);
            $type   = $db->real_escape_string($_POST['type']);
            $passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT);

            $insert = $db->prepare("INSERT INTO users (FirstName, LastName, PhoneNumber, Login, Password, Type) VALUES (?, ?, ?, ?, ?, ?);");
            $insert->bind_param("ssssss", $fname, $lname, $phone, $login, $passwd, $type);
            $insert->execute();
        }
        header("Location: ");
    }
}

function loginUser()
{
    if(isset($_POST['login']) && isset($_POST['passwd']))
    {
        if(strlen($_POST['passwd']) < 3)
        {
            echo "<p>Password is too short minimum 3 characters!</p>";
            return;
        }

        $data = getUserByLogin($_POST['login']);

        if($data === NULL)
        {
            echo "<p>Invalid login!</p>";
            return;
        }

        $dbPassword = $data['Password'];

        if(password_verify($_POST['passwd'], $dbPassword))
        {
            session_start();
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['passwd'] = $_POST['passwd'];
            echo $_SESSION['login'];
            echo $_SESSION['passwd'];
            header('Location: /dashboard');
        }
    }
}

function verifyUser()
{
    if(!isset($_SESSION['login']) && !isset($_SESSION['passwd']))
    {
        header("Location: /");
        return;
    }

    $data = getUserByLogin($_SESSION['login']);
    if(strtolower($data['Login']) !== strtolower($_SESSION['login']) || !password_verify($_SESSION['passwd'], $data['Password']))
    {
        session_destroy();
        header("Location: /");
        return;
    }
}

function getUserByLogin(string $login)
{
    global $db;

    $user = $db->prepare("SELECT * FROM users WHERE Login = ?");
    $login = $db->real_escape_string($login);
    $user->bind_param("s", $login);
    $user->execute();
    $result = $user->get_result();

    return $result->fetch_array();
}