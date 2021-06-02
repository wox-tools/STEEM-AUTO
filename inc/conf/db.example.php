<?php
$server="127.0.0.1";
$user="username";
$pw="password";
$db="databasename";
$conn = new mysqli($server,$user,$pw,$db);
$conn->set_charset('utf8mb4');
// $BACKENDSERVER  = 'http://127.0.0.1:3001/';
// $FRONTEND = "http://localhost/steemauto/";
$BACKENDSERVER  = 'https://example.com/';
$FRONTEND = "https://example.com/";
?>
