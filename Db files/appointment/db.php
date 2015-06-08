<?php
session_start();
$servername = "localhost";
$username = "fsef141g4";
$password = "fsef141g4";
$dbname = "fsef141g4";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



