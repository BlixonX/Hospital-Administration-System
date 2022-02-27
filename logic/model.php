<?php
$dbPort = 3306;
$dbName = "hospital";
$db = new mysqli('localhost', 'root', '', $dbName, $dbPort);

function addUser(): void
{
    verifyUser();
    
    if(!canAccess(["Doctor", "Nurse"])) kick(true);

    if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['phone']) && isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['type']))
    {
        if(str_contains($_POST['login'], " ") || str_contains($_POST['passwd'], " "))
        {
            echo '<p class="error">Login credentials contain spaces!</p>';
            return;
        }

        if(strlen($_POST['passwd']) < 3)
        {
            echo '<p class="error">Password too short! Minimum 3 characters!</p>';
            return;
        }

        global $db;

        $check = $db->prepare("SELECT * FROM users WHERE Login = ? ;");
        $str = $db->real_escape_string(str_replace(" ", "", $_POST['login']));
        $check->bind_param("s", $str);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0)
        {
            echo '<p class="error">User with same login exists!</p>';
            return;
        }
        else
        {
            $fname  = $db->real_escape_string(trim($_POST['fname'], " "));
            $lname  = $db->real_escape_string(trim($_POST['lname'], " "));
            $phone  = $db->real_escape_string(str_replace(" ", "", $_POST['phone']));
            $login  = $db->real_escape_string(str_replace(" ", "", $_POST['login']));
            $type   = $db->real_escape_string($_POST['type']);
            $passwd = password_hash(str_replace(" ", "", $_POST['passwd']), PASSWORD_DEFAULT);

            $insert = $db->prepare("INSERT INTO users (FirstName, LastName, PhoneNumber, Login, Password, Type) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->bind_param("ssssss", $fname, $lname, $phone, $login, $passwd, $type);
            $insert->execute();
        }
        header("Location: dashboard?page=add&usuccess=true");
    }
}

function loginUser(): void
{
    if(isset($_POST['login']) && isset($_POST['passwd']))
    {
        if($_POST['login'] === "" || $_POST['passwd'] === "")
        {
            echo '<p class="error">Missing credentials!</p>';
            return;
        }

        if(str_contains(trim($_POST['login']), " "))
        {
            echo '<p class="error">Login cannot contain spaces!</p>';
            return;
        }

        if(strlen($_POST['passwd']) < 3)
        {
            echo '<p class="error">Password is too short minimum 3 characters!</p>';
            return;
        }

        $data = getUserByLogin($_POST['login']);

        if($data === NULL)
        {
            echo '<p class="error">Invalid login!</p>';
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
    if(!canAccess(["Doctor"])) kick(true);
    global $db;
    $value = $db->real_escape_string($value);
    $column = $db->real_escape_string($column);
    $likeValue = "%" . $value . "%";
    $stmt = $db->prepare("SELECT ID, FirstName, LastName, PhoneNumber, Login, Type FROM users WHERE ".$column." LIKE ?");
    $stmt->bind_param("s", $likeValue);
    $stmt->execute();

    $res = NULL;

    $stmt->store_result();
    $stmt->bind_result($res['ID'], $res['FirstName'], $res['LastName'], $res['PhoneNumber'], $res['Login'], $res['Type']);
    
    while($stmt->fetch())
    {
        echo "<tr";
        if($res['Type'] == "Nurse")
            echo ' class="nurse"';
        else if($res['Type'] == "Doctor")
            echo ' class="doctor"';
        else if($res['Type'] == "Admin")
            echo ' class="admin"';
        echo ">";
            echo "<td><p>" . ($column === "ID" ? boldSearched($res['ID'], $value) : $res['ID']) . "</p></td>";
            echo "<td><p>" . ($column === "FirstName" ? boldSearched($res['FirstName'], $value) : $res['FirstName']) . "</p></td>";
            echo "<td><p>" . ($column === "LastName" ? boldSearched($res['LastName'], $value) : $res['LastName']) . "</p></td>";
            echo "<td><p>" . ($column === "PhoneNumber" ? boldSearched($res['PhoneNumber'], $value) : $res['PhoneNumber']) . "</p></td>";
            echo "<td><p>" . strtolower(($column === "Login" ? boldSearched($res['Login'], $value) : $res['Login'])) . "</p></td>";
            echo "<td><p>" . ($column === "Type" ? boldSearched($res['Type'], $value) : $res['Type']) . "</p></td>";
        echo "</tr>";
    }
}

function boldSearched(string $haystack, string $needle): string
{
    return strstr($haystack, $needle, true)."<b>".$needle."</b>".substr($haystack, strlen($needle)+strpos($haystack, $needle));
}

function getUserByColumnAndValue(string $column, string $value): array | NULL
{
    verifyUser();

    global $db;
    $column = $db->real_escape_string($column);
    $value = "%".$db->real_escape_string($value)."%";
    $stmt = $db->prepare("SELECT ID, FirstName, LastName, PhoneNumber, Login, Type FROM users WHERE ".$column." LIKE ? LIMIT 1");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $res = NULL;
    $stmt->store_result();

    if($stmt->num_rows() == 0)
        return NULL;

    $stmt->bind_result($res['ID'], $res['FirstName'], $res['LastName'], $res['PhoneNumber'], $res['Login'], $res['Type']);
    $stmt->fetch();
    return $res;
}

function removeUser(): void
{
    verifyUser();
    if(!canAccess()) kick(true);

    global $db;

    $column = $db->real_escape_string($_GET['query']);
    $value = "%".$db->real_escape_string($_GET['value'])."%";

    $stmt = $db->prepare("DELETE FROM users WHERE ".$column." LIKE ? LIMIT 1");
    $stmt->bind_param("s", $value);
    $success = $stmt->execute();

    if($success === false)
        echo $stmt->error;
    else
        echo '<p class="info">User removed successfully!</p>';
}

function addAppointment(): void
{
    verifyUser();
    if(!canAccess(["Doctor", "Nurse"])) kick(true);

    if(!canAccess(["Doctor", "Nurse"])) kick(true);

    if ($_POST['doctorValue'] == "" || $_POST['patientValue'] == "" || $_POST['date']  == "" || $_POST['start']  == "" || $_POST['end'] == "" || $_POST['title'] == "" )
    {
        echo '<p class="error">Not all fields were filled!</p>';
        return;
    }

    global $db;

    if($_POST['date'] == "")
    {
        echo '<p class="error">Invalid date!</p>';
        return;
    }

    if($_POST['start'] == "" || $_POST['end'] == "")
    {
        echo '<p class="error">Invalid time</p>';
        return;
    }

    if($_POST['start'] == $_POST['end'])
    {
        echo '<p class="error">Appointment cannot be 0 minutes long!</p>';
        return;
    }

    $title = $db->real_escape_string($_POST['title']);
    $date = $db->real_escape_string($_POST['date']);
    $start = $db->real_escape_string($_POST['start']);
    $end = $db->real_escape_string($_POST['end']);

    $doctor = getUserByColumnAndValue($_POST['doctorQuery'], $_POST['doctorValue']);
    $patient = getUserByColumnAndValue($_POST['patientQuery'], $_POST['patientValue']);

    if($doctor == $patient)
    {
        echo '<p class="error">Patient and doctor is the same person!</p>';
        return;
    }
    
    $thisday = $db->prepare("SELECT ID, DoctorID, PatientID, Date, Start, End, Title FROM appointments WHERE Date = ? AND DoctorID = ? ORDER BY Start");
    $thisday->bind_param("si", $date, $doctor['ID']);
    $thisday->execute();
    $thisday->store_result();
    $result = NULL;
    $thisday->bind_result($result['ID'], $result['DoctorID'], $result['PatientID'], $result['Date'], $result['Start'], $result['End'], $result['Title']);

    $pushAppointment = $db->prepare("INSERT INTO appointments (DoctorID, PatientID, Date, Start, End, Title) VALUES (?, ?, ?, ?, ?, ?)");
    
    if($doctor != NULL && $patient != NULL && $doctor['Type'] == "Doctor")
    {
        if($thisday->num_rows() == 0)
        {
            $pushAppointment->bind_param("iissss", $doctor['ID'], $patient['ID'], $date, $start, $end, $title);
            $pushAppointment->execute();
            header("Location: dashboard?page=add&asuccess=true");
        }
        else
        {
            $startTIME = strtotime($start);
            $endTIME = strtotime($end);
            $doesCollide = false;
            while($thisday->fetch())
            {
                $resultStart = strtotime($result['Start']);
                $resultEnd = strtotime($result['End']);

                if( ($resultStart <= $startTIME && $startTIME < $resultEnd)
                || ($resultStart <= $endTIME && $endTIME < $resultEnd)
                || ($startTIME <= $resultStart && $resultEnd <= $endTIME) ) //Start or End in inside another appointment or Appointment wrapping over another appointment
                {
                    $doesCollide = true;
                    echo '<p class="error">Appointment collides with another appointment!</p>';
                    break;
                }
            }
            
            if($doesCollide) return;
            
            $pushAppointment->bind_param("iissss", $doctor['ID'], $patient['ID'], $date, $start, $end, $title);
            $pushAppointment->execute();
            header("Location: dashboard?page=add&asuccess=true");
        }
    }
    else
    {
        if($doctor == NULL && $patient == NULL)
            echo '<p class="error">Invalid patient and doctor</p>';
        else if ($doctor == NULL || $doctor['Type'] != "Doctor")
            echo '<p class="error">Invalid doctor</p>';
        else
            echo '<p class="error">Invalid patient</p>';
    }
}

function searchAppointments(): void
{
    verifyUser();
    global $db;
    $result = NULL;
    $stmt = NULL;

    $now = date("Y-m-d");
    $then = date("Y-m-d", strtotime($now."+ 7 days"));
    if(canAccess(["Doctor", "Nurse"]) && isset($_GET['patOrDoc']) && isset($_GET['userQuery']) && isset($_GET['value']) && isset($_GET['start']) && isset($_GET['end']))
    {
        if($_GET['start'] != "" && $_GET['end'] != "")
        {
            $now = $db->real_escape_string($_GET['start']);
            $then = $db->real_escape_string($_GET['end']);
        }
        else if ($_GET['start'] != "")
        {
            $now = $db->real_escape_string($_GET['start']);
            $then = "9999-12-31";
        }
        else if ($_GET['end'] != "")
        {
            $now = "0001-01-01";
            $then = $db->real_escape_string($_GET['end']);
        }
        if($_GET['value'] != "")
        {
            $userType = ($_GET['patOrDoc'] == "doctor" ? "DoctorID" : "PatientID");
            $query = ($_GET['userQuery'] == "id" ? "ID" : ($_GET['userQuery'] == "phone" ? "PhoneNumber" : "Login") );
            $user = getUserByColumnAndValue($query, $_GET['value']);
            if($user == NULL)
            {
                echo '<p class="error">No such user!</p>';
                return;
            }
            $stmt = $db->prepare("SELECT ID, DoctorID, PatientID, Date, Start, End, Title FROM appointments WHERE ".$userType." = ? AND Date BETWEEN ? AND ? ORDER BY Date, Start");
            $stmt->bind_param("sss", $user['ID'], $now, $then);
        }
        else
        {
            $stmt = $db->prepare("SELECT ID, DoctorID, PatientID, Date, Start, End, Title FROM appointments WHERE Date BETWEEN ? AND ? ORDER BY Date, Start");
            $stmt->bind_param("ss", $now, $then);
        }
    }
    else if(isset($_GET['start']) && isset($_GET['end']) )
    {
        if($_GET['start'] != "" && $_GET['end'] != "")
        {
            $now = $db->real_escape_string($_GET['start']);
            $then = $db->real_escape_string($_GET['end']);
        }
        else if ($_GET['start'] != "")
        {
            $now = $db->real_escape_string($_GET['start']);
            $then = "9999-12-31";
        }
        else if ($_GET['end'] != "")
        {
            $now = "0001-01-01";
            $then = $db->real_escape_string($_GET['end']);
        }
        $stmt = $db->prepare("SELECT ID, DoctorID, PatientID, Date, Start, End, Title FROM appointments WHERE Date BETWEEN ? AND ? AND PatientID = ? ORDER BY Date, Start");
        $stmt->bind_param("sss", $now, $then, getUserByLogin($_SESSION['login'])['ID']);
    }
    if( (canAccess(["Doctor", "Nurse"]) && isset($_GET['patOrDoc']) && isset($_GET['userQuery']) && isset($_GET['value']) && isset($_GET['start']) && isset($_GET['end']) ) || (isset($_GET['start']) && isset($_GET['end'])) )
    {
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['ID'], $result['DoctorID'], $result['PatientID'], $result['Date'], $result['Start'], $result['End'], $result['Title']);

        if(canAccess(["Doctor", "Nurse"]))
        {
            if($_GET['value'] == "")
            {
                while($stmt->fetch())
                {
                    $doctor = getUserByColumnAndValue("ID", $result['DoctorID']);
                    $patient = getUserByColumnAndValue("ID", $result['PatientID']);
                    echo "<tr>";
                    echo "<td>".$result['ID']."</td>";
                    echo "<td>".$result['Title']."</td>";
                    echo "<td>".($doctor != NULL ? $doctor['FirstName']." ".$doctor['LastName'] : "[DELETED DOCTOR]")."</td>";
                    echo "<td>".($patient != NULL ? $patient['FirstName']." ".$patient['LastName'] : "[DELETED PATIENT]")."</td>";
                    echo "<td>".$result['Date']."</td>";
                    echo "<td>".substr($result['Start'], 0, -3)."</td>";
                    echo "<td>".substr($result['End'], 0, -3)."</td>";
                    echo "</tr>";
                }
                return;
            }
            if($_GET['patOrDoc'] == "doctor")
            {
                while($stmt->fetch())
                {
                    $patient = getUserByColumnAndValue("ID", $result['PatientID']);
                    echo "<tr>";
                    echo "<td>".$result['ID']."</td>";
                    echo "<td>".$result['Title']."</td>";
                    echo "<td>".($patient != NULL ? $patient['FirstName']." ".$patient['LastName'] : "[DELETED PATIENT]")."</td>";
                    echo "<td>".$result['Date']."</td>";
                    echo "<td>".substr($result['Start'], 0, -3)."</td>";
                    echo "<td>".substr($result['End'], 0, -3)."</td>";
                    echo "</tr>";
                }
                return;
            }
        }
        while($stmt->fetch())
        {
            $doctor = getUserByColumnAndValue("ID", $result['DoctorID']);
            echo "<tr>";
            echo "<td>".$result['ID']."</td>";
            echo "<td>".$result['Title']."</td>";
            echo "<td>".($doctor != NULL ? $doctor['FirstName']." ".$doctor['LastName'] : "[DELETED DOCTOR]")."</td>";
            echo "<td>".$result['Date']."</td>";
            echo "<td>".substr($result['Start'], 0, -3)."</td>";
            echo "<td>".substr($result['End'], 0, -3)."</td>";
            echo "</tr>";
        }
        return;
    }
}