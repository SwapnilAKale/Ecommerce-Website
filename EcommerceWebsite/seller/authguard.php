<?php

session_start();

if(!isset($_SESSION["login_status"]))
{
    echo "Unauthorized Access";
    die;
}

if($_SESSION["login_status"]==false)
{
    echo "Unauthorized Access";
    die;
}

if($_SESSION["usertype"]!="Seller")
{
    echo "Forbidden Role error";
    die;
}

?>