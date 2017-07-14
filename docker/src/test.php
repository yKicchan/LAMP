<?php
$mysqli  = new mysqli('mysql', 'user', 'password', 'database');

if ($mysqli->connect_errno) {
    printf("Failed: %s\n", $mysqli->connect_error);
    exit();
}
printf("Success!!");
$mysqli->close();
