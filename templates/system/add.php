<?php
    if(canAccess(["Nurse", "Doctor"])){
?>
<form action="" method="post">
    <input type="text" name="fname" placeholder="First Name" id="">
    <input type="text" name="lname" placeholder="Last Name" id="">
    <input type="text" name="phone" placeholder="Phone Number" id="">
    <input type="text" name="login" placeholder="Login" id="">
    <input type="text" name="passwd" placeholder="Password" id="">
    <select name="type" id="">
        <option value="Patient">Patient</option>
        <option value="Nurse">Nurse</option>
        <option value="Doctor">Doctor</option>
        <option value="Admin" style="color: red;">Admin</option>
    </select>
    <input type="submit" value="Submit">
</form>
<?php
} else kick(true);
addUser();
?>