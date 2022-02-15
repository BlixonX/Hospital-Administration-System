<?php
    if (canAccess(["Doctor"])){
?>
<form action="" method="get">
    <input type="hidden" name="page" value="search">
    <label>Search by:
        <select name="query" id="searchBy">
            <option value="id">ID</option>
            <option value="fname">First Name</option>
            <option value="lname">Last Name</option>
            <option value="phone">Phone Number</option>
            <option value="login">Login</option>
            <option value="type">User Type</option>
        </select>
    </label>
    <input type="text" name="value" id="" placeholder="Search value">
    <input type="submit" value="Submit">
</form>
<table>
    <thead>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Phone Number</th>
        <th>Login</th>
        <th>User Type</th>
    </thead>
    <tbody>
<?php
    } else kick(true);
    if(isset($_GET['query']) && isset($_GET['value']))
    {
        $column = "";
        switch ($_GET['query'])
        {
            case "id":
                $column = "ID";
                break;
            case "fname":
                $column = "FirstName";
                break;
            case "lname":
                $column = "LastName";
                break;
            case "phone":
                $column = "PhoneNumber";
                break;
            case "login":
                $column = "Login";
                break;
            case "type":
                $column = "Type";
                break;
        }
        if($column !== "")
            getData($column, $_GET['value']);
    }
?>
    </tbody>
</table>