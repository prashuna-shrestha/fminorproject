<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM signupss WHERE id=$id");
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "User not found";
        exit();
    }

    if (isset($_POST['update'])) {
        $id=$_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname=$_POST['lastname'];
        $email = $_POST['email'];
      $password_hash=$_POST['password_hash'];
      $isactive=$_POST['isactive'];

       $updateSql = "UPDATE signupss SET isactive='$isactive' WHERE id=$id";


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
 
    <form action="editc.php?id=<?php echo $id; ?>" method="post">
          <h2>Edit User</h2>
          <label for="id">ID:</label>
        <input type="int" name="id" value="<?php echo $row['id']; ?>" required>
        <label for="firstname">FirstName:</label>
        <input type="text" name="firstname" value="<?php echo $row['firstname']; ?>" required>
        <label for="lastname">LastName:</label>
        <input type="text" name="lastname" value="<?php echo $row['lastname']; ?>" required>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
        <label for="password">password:</label>
        <input type="text" name="password_hash" value="<?php echo $row['password_hash']; ?>" required>

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