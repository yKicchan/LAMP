<?php

$mysqli  = new mysqli('mysql', 'user', 'password', 'database');

$sql = "INSERT INTO `test` (`time`) VALUES ('".date('Y/m/d H:i:s')."')";
$mysqli->query($sql);

$sql = "SELECT * FROM `test`";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        echo "{$row['id']}: {$row['time']}<br>";
    }
    $result->close();
}

$mysqli->close();
