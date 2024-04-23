<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM user WHERE id=$id");
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "User not found";
        exit();
    }

    if (isset($_POST['update'])) {
        $firstname = $_POST['firstname'];
        $lastname=$_POST['lastname'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $age = $_POST['age'];

        $updateSql = "UPDATE user SET firstname='$firstname', lastname='$lastname', email='$email', contact='$contact', age=$age WHERE id=$id";

        if ($conn->query($updateSql) === TRUE) {
            echo "Record updated successfully";
            header("Location: read.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    echo "Invalid request";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit.css">
</head>

<body>
  
    <form action="edit.php?id=<?php echo $id; ?>" method="post">
          <h2>Edit User</h2>
        <label for="firstname">FirstName:</label>
        <input type="text" name="firstname" value="<?php echo $row['firstname']; ?>" required>
        <label for="lastname">LastName:</label>
        <input type="text" name="lastname" value="<?php echo $row['lastname']; ?>" required>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
        <label for="contact">Contact:</label>
        <input type="contact" name="contact" value="<?php echo $row['contact']; ?>" required>
        <label for="age">Age:</label>
        <input type="number" name="age" value="<?php echo $row['age']; ?>" required>
        <button type="submit" name="update">Update User</button>
    </form>
</body>
</html>
