<?php

session_start();
session_destroy();

header("location: /shared/login.html");

?>