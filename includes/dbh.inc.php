<?php

$servername = "localhost";
$username = "root";
$pssword = "";
$dbname = "educconnect";

$conn = mysqli_connect($servername, $username, $pssword, $dbname);

if(!$conn)
{
    die("Connection failed: ". mysqli_connect_error());
}