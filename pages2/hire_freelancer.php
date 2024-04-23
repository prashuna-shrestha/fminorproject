<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if the proposalID is set and is a number
    if (isset($_GET["proposalID"]) && is_numeric($_GET["proposalID"])) {
        $proposalID = $_GET["proposalID"];
    }
   

        // Replace these variables with your database credentials
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "minorproject";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the freelancer is already hired
        $checkHiredQuery = "SELECT status FROM Proposalsass WHERE proposalID = $proposalID";
        $checkHiredResult = $conn->query($checkHiredQuery);

        if ($checkHiredResult->num_rows > 0) {
            $row = $checkHiredResult->fetch_assoc();
            $status = $row["status"];
        
         // Check if the freelancer is already hired or accepted
if ($status === 'hired' || $status === 'accepted') {
    echo "already_hired";
} else {
    // Update the status to hired
    $updateQuery = "UPDATE Proposalsass SET status = 'accepted' WHERE proposalID = $proposalID";
    if ($conn->query($updateQuery) === TRUE) {
        // Fetch company name and job position for recent activity
        $companyQuery = "SELECT company, jobPosition FROM Proposalsass WHERE proposalID = $proposalID";
        $companyResult = $conn->query($companyQuery);

        if ($companyResult->num_rows > 0) {
            $companyRow = $companyResult->fetch_assoc();
            $companyName = $companyRow["company"];
            $jobPosition = $companyRow["jobPosition"];

            echo "success|$companyName|$jobPosition"; // Return additional information
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
}
        }
    }

       
?>