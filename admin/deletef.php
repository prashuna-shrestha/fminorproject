<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $deleteSql = "DELETE FROM Freelancerss WHERE freelancerID=$id";

    if ($conn->query($deleteSql) === TRUE) {
        echo "Record deleted successfully";
        header("Location: read.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request";
    exit();
}
?>
