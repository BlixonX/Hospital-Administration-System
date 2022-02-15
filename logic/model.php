<?php
$dbPort = 3306;
$dbName = "hospital";
$db = new mysqli('localhost', 'root', '', $dbName, $dbPort);

function addUser(): void
{
    verifyUser();
    
    if(getUserByLogin($_SESSION['login'])['Type'] === "Patient")
        return;

    if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['phone']) && isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['type']))
    {
        if(str_contains($_POST['login'], " ") || str_contains($_POST['passwd'], " "))
        {
            echo "<p>Login credentials contain spaces!</p>";
            return;
        }

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
            $phone  = $db->real_escape_string(trim($_POST['phone']));
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

function loginUser(): void
{
    if(isset($_POST['login']) && isset($_POST['passwd']))
    {
        if($_POST['login'] === "" || $_POST['passwd'] === "")
        {
            echo "<p>Missing credentials!</p>";
            return;
        }

        if(str_contains(trim($_POST['login']), " "))
        {
            echo "<p>Login cannot contain spaces!</p>";
            return;
        }

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

function verifyUser(): void
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

function getUserByLogin(string $login): array
{
    global $db;

    $user = $db->prepare("SELECT * FROM users WHERE Login = ?");
    $login = $db->real_escape_string($login);
    $user->bind_param("s", $login);
    $user->execute();
    $result = $user->get_result();

    return $result->fetch_array();
}

function canAccess(array $types=[]): bool
{
    array_push($types, "Admin"); //Controls Everything
    if(in_array(getUserByLogin($_SESSION['login'])['Type'], $types))
        return true;
    return false;
}

function kick(bool $allow)
{
    if($allow)
        header("Location: /dashboard");
}

function getData(string $column, string $value)
{
    verifyUser();
    global $db;
    $value = $db->real_escape_string($value);
    $likeValue = "%" . $value . "%";
    $stmt = $db->prepare("SELECT ID, FirstName, LastName, PhoneNumber, Login, Type FROM users WHERE ".$column." LIKE ?");
    $stmt->bind_param("s", $likeValue);
    $stmt->execute();

    $res = NULL;

    $stmt->store_result();
    $stmt->bind_result($res['ID'], $res['FirstName'], $res['LastName'], $res['PhoneNumber'], $res['Login'], $res['Type']);
    
    for ($i=0; $i < $stmt->num_rows() ; $i++) 
    {
        $stmt->fetch();
        echo "<tr";
        if($res['Type'] == "Nurse")
            echo ' style="background-color: #aebf2e;"';
        else if($res['Type'] == "Doctor")
            echo ' style="background-color: #0085cc;"';
        else if($res['Type'] == "Admin")
            echo ' style="background-color: #bd5151;"';
        echo ">";
            // $data = [$res['ID'],  $res['FirstName'], $res['LastName'], $res['PhoneNumber'], strtolower($res['Login']), $res['Type']];
            echo "<td><p>" . ($column === "ID" ? boldSearched($res['ID'], $value) : $res['ID']) . "</p></td>";
            echo "<td><p>" . ($column === "FirstName" ? boldSearched($res['FirstName'], $value) : $res['FirstName']) . "</p></td>";
            echo "<td><p>" . ($column === "LastName" ? boldSearched($res['LastName'], $value) : $res['LastName']) . "</p></td>";
            echo "<td><p>" . ($column === "PhoneNumber" ? boldSearched($res['PhoneNumber'], $value) : $res['PhoneNumber']) . "</p></td>";
            echo "<td><p>" . strtolower(($column === "Login" ? boldSearched($res['Login'], $value) : $res['Login'])) . "</p></td>";
            echo "<td><p>" . ($column === "Type" ? boldSearched($res['Type'], $value) : $res['Type']) . "</p></td>";
        echo "</tr>";
        // echo strstr($res['PhoneNumber'], $value, true)."<b>".$value."</b>".substr($res['PhoneNumber'], strlen($value)+strpos($res['PhoneNumber'], $value));
        // strpos($res['PhoneNumber'], "09")
    }
}

function boldSearched(string $haystack, string $needle): string
{
    return strstr($haystack, $needle, true)."<b>".$needle."</b>".substr($haystack, strlen($needle)+strpos($haystack, $needle));
}