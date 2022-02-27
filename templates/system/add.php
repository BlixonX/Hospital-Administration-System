<?php
    if(canAccess(["Nurse", "Doctor"])){
?>
<form action="" method="post">
    <input type="text" name="fname" placeholder="First Name">
    <input type="text" name="lname" placeholder="Last Name">
    <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{9}"> <?php //*pattern* is just regex. Change {9} to your number of digits in phone number. (ex. [0-9]{9} to [0-9]{11}) ?>
    <input type="text" name="login" placeholder="Login">
    <input type="password" name="passwd" placeholder="Password">
    <select name="type">
        <option value="Patient">Patient</option>
        <option value="Nurse">Nurse</option>
        <option value="Doctor">Doctor</option>
        <option value="Admin" style="color: red;">Admin</option>
    </select>
    <input type="submit" value="Submit">
</form>
<?php
if(isset($_POST['fname']))
    addUser();
if(isset($_GET['usuccess']) && $_GET['usuccess'] == "true")
    echo '<p class="info">User added!</p>';
?>
<form action="" method="post">
    <input type="text" name="title" placeholder="Appointment title">
    <label>Select doctor by:
        <select name="doctorQuery">
            <option value="id">ID</option>
            <option value="phone">Phone Number</option>
            <option value="login">Login</option>
        </select>
        <input type="text" name="doctorValue" placeholder="Search doctor">
    </label>
    <label>Select patient by:
        <select name="patientQuery">
            <option value="id">ID</option>
            <option value="phone">Phone Number</option>
            <option value="login">Login</option>
        </select>
        <input type="text" name="patientValue" placeholder="Search patient">
    </label>
    <label>Time and date: 
    <input type="date" name="date">
    <input type="time" name="start" id="start" value="00:00">
    <input type="time" name="end" id="end" value="00:00">
    </label>
    <input type="submit" value="Submit">
</form>
<?php
} else kick(true);

if(isset($_GET['asuccess']) && $_GET['asuccess'] == "true")
    echo '<p class="info">Appointment added!</p>';

if(isset($_POST['doctorQuery']))
    addAppointment();
?>
<script src="js/time-controller.js"></script>