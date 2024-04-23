<?php
include 'db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="read.css">
</head>

<body>

<header>
        <div class="logo-container">
          <img src="./../assets2/logo new (1).jpg" alt="Logo">
        </div>
    </header>
    
    <h1>Admin List</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>CONTACT</th>
            <th>Age</th>
            <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM user");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['firstname']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['contact']}</td>
                    <td>{$row['age']}</td>
                    <td>
                        <a href='edit.php?id={$row['id']}'>Edit</a> 
                        <a href='delete.php?id={$row['id']}'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <h1>Company List</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>FIRSTNAME</th>
            <th>LASTNAME</th>
            <th>EMAIL</th>
            <th>PASSWORD</th>
            <th>ISACTIVE</th>
            <th>Action</th>
        </tr>
    <?php
       $sql = "SELECT * FROM signupss";
       $result = mysqli_query($conn, $sql);
   
       if ($result && mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['firstname']}</td>
                        <td>{$row['lastname']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['password_hash']}</td>
                        <td>{$row['isactive']}</td>
                        <td>
                            <a href='editc.php?id={$row['id']}'>Approve</a>
                            <a href='deletec.php?id={$row['id']}'>Reject</a>
                        </td>
                    </tr>";
            }
        }
        ?>


    </table>
    <h1>Freelancer List</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>FIRSTNAME</th>
            <th>LASTNAME</th>
            <th>EMAIL</th>
            <th>PASSWORD</th>
            <th>ISACTIVE</th>
            <th>Action</th>
        </tr>
    <?php
       $sql = "SELECT * FROM Freelancerss";
       $result = mysqli_query($conn, $sql);
   
       if ($result && mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['freelancerID']}</td>
                        <td>{$row['firstName']}</td>
                        <td>{$row['lastName']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['passwordHash']}</td>
                        <td>{$row['isactive']}</td>
                        <td>
                            <a href='editf.php?id={$row['freelancerID']}'>Approve</a>
                            <a href='deletef.php?id={$row['freelancerID']}'>Reject</a>
                        </td>
                    </tr>";
            }
        }
        // used get id for next page
        ?>
    </table>


   
</body>
</html>
