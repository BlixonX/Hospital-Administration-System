<?php

function view()
{
    include_once 'templates/system/templates/header.php';
    include_once 'templates/system/templates/nav.php';
    switch ($_GET['page'])
    {
        case "search":
        case "appointments":
        case "add":
        case "remove":
            include_once 'templates/system/'.$_GET['page'].'.php';
            break;
        default:
            include_once 'templates/system/welcome.php';
    }
    include_once 'templates/system/templates/footer.php';
}