<?php
// update_reject_status.php

// Replace these variables with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minorproject";

// Get proposalID from the request
$proposalID = $_GET['proposalID'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the status in the Proposalsass table
$sql = "UPDATE Proposalsass SET status = 'rejected' WHERE proposalID = $proposalID";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>