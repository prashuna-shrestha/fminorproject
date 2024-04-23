<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM freelancerss WHERE freelancerID=$id");
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "User not found";
        exit();
    }

    if (isset($_POST['update'])) {
        $id = $_POST['freelancerID'];
        $firstName = $_POST['firstName'];
        $lastName=$_POST['lastName'];
        $email = $_POST['email'];
      $passwordHash=$_POST['passwordHash'];
      $isactive=$_POST['isactive'];
      $updateSql = "UPDATE Freelancerss SET isactive='$isactive' WHERE freelancerID=$id";


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
  
    <form action="editf.php?id=<?php echo $id; ?>" method="post">
          <h2>Edit User</h2>
          <label for="id">ID:</label>
          <input type="text" name="freelancerID" value="<?php echo $row['freelancerID']; ?>" required>
        <label for="firstName">FirstName:</label>
        <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>" required>
        <label for="lastName">LastName:</label>
        <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>" required>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
        <label for="passwordHash">password:</label>
        <input type="text" name="passwordHash" value="<?php echo $row['passwordHash']; ?>" required>
        
        
        <div class="additionalinfo">
        <label for="isactive">choose:</label><br>
        <select name="isactive">
        <option value="N" <?php if(isset($isactive) && $isactive == 'N') echo 'selected'; ?>>N</option>
        <option value="Y" <?php if(isset($isactive) && $isactive == 'Y') echo 'selected'; ?>>Y</option>
</select>
</div>
        <button type="submit" name="update">Update User</button>
    </form>
</body>
</html>