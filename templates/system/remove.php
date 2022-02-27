<?php
    if(canAccess()){
?>
<form action="" method="get" id="form-remove">
    <input type="hidden" name="page" value="remove">
    <p>Remove user by:</p>
        <select name="query" id="removeBy">
            <option value="id">ID</option>
            <option value="phone">Phone Number</option>
            <option value="login">Login</option>
        </select>
    <input type="text" name="value" id="" placeholder="Search value">
    <input type="submit" value="Submit">
</form>
<?php
} else kick(true);

if(isset($_GET['query']) && (isset($_GET['value']) && $_GET['value'] !== ""))
{
    $column = "";
    $arr = NULL;
    switch ($_GET['query'])
    {
        case "id":
            $column = "ID";
            break;
        case "phone":
            $column = "PhoneNumber";
            break;
        case "login":
            $column = "Login";
            break;
    }
    if($column !== "")
        $arr = getUserByColumnAndValue($column, $_GET['value']);

    if($arr != NULL)
    {
        echo '<div class="user-info">';
        echo '<h2>[#'.$arr['ID'].']'.' '.$arr['FirstName'].' '.$arr['LastName'].'</h2>';
        echo '<h3>Phone Number: '.$arr['PhoneNumber'].'</h3>';
        echo '<h3>Login: '.strtolower($arr['Login']).'</h3>';
        echo '<h3>Type: '.$arr['Type'].'</h3>';
        echo '</div>';
        ?>
<form action="" method="get" id="form-confirm">
    <input type="hidden" name="page" value="remove">
    <input type="hidden" name="query" value="<?php echo $_GET['query'];?>">
    <input type="hidden" name="value" value="<?php echo $_GET['value'];?>">
    <input type="hidden" name="remove" value="true">
    <input type="submit" value="Confirm removal">
</form>
<?php
    }
    else
    {
        echo '<h1>No user found!</h1>';
    }
    if(isset($_GET['remove']) && $_GET['remove'] === "true")
    {
        removeUser();
    }
}
?>