<?php
session_start();
include_once 'model.php';
include_once 'view.php';

function controller()
{
    verifyUser();

    if(!isset($_GET['page']) || !in_array($_GET['page'], ["welcome", "search", "appointments", "add", "remove", "logout"]))
        header("Location: ?page=welcome");
    else if($_GET['page'] == "logout")
    {
        session_destroy();
        header("Location: ../");
    }

    view();
}