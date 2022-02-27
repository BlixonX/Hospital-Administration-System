<?php
    if(canAccess(["Doctor", "Nurse"])){
        if(!isset($_GET['start']) && !isset($_GET['end']))
        header("Location: dashboard?page=appointments&patOrDoc=doctor&userQuery=id&value=&start=&end=");
?>
<form action="" method="get">
    <input type="hidden" name="page" value="appointments">
    <label>Search 
        <select name="patOrDoc">
            <option value="doctor">Doctor</option>
            <option value="patient">Patient</option>
        </select>
        's appointments
    </label>
    <label>
        Search by:
        <select name="userQuery">
            <option value="id">ID</option>
            <option value="phone">Phone Number</option>
            <option value="login">Login</option>
        </select>
    </label>
    <input type="text" name="value" placeholder="Search value">
    <label>
        Start:<input type="date" name="start" id="start">
    </label>
    <label>
        End:<input type="date" name="end" id="end">
    </label>
    <input type="submit" value="Search">
</form>
<?php
} 
else
{
    if(!isset($_GET['start']) && !isset($_GET['end']))
        header("Location: dashboard?page=appointments&start=&end=");
?>
<form action="" method="get">
    <input type="hidden" name="page" value="appointments">
    <label>
        Start:<input type="date" name="start" id="start">
    </label>
    <label>
        End:<input type="date" name="end" id="end">
    </label>
        <input type="submit" value="Search">
</form>
<?php
}
?>
<table>
    <thead>
        <th>ID</th>
        <th>Title</th>
        <?php
            if(getUserByLogin($_SESSION['login'])['Type'] != "Patient" )
            {
                if(isset($_GET['value']) && $_GET['value'] != "")
                {
                    echo (isset($_GET['patOrDoc']) && $_GET['patOrDoc'] == "doctor" ? "<th>Patient</th>" : "<th>Doctor</th>");
                }
                else
                    echo "<th>Doctor</th><th>Patient</th>";
            }
            else
            {
                echo "<th>Doctor</th>";
            }
        ?>
        <th>Date</th>
        <th>Start</th>
        <th>End</th>
    </thead>
    <tbody>
        <?php
            searchAppointments();
        ?>
    </tbody>
</table>