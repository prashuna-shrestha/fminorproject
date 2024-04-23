<?php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "minorproject";

// create connection
$conn = mysqli_connect($servername, $username, $password, $databasename);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? mysqli_real_escape_string($conn, $_POST["email"]) : "";
    $user_password = isset($_POST["password"]) ? mysqli_real_escape_string($conn, $_POST["password"]) : "";
    // Check against the database email
    $sql = "SELECT * FROM user WHERE email = '$email' ";
    $result = mysqli_query($conn, $sql);

    // if the email is found then
    if ($result && mysqli_num_rows($result) > 0) {
        // get the user's data from the database
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row["password_hash"];

        // Verify the entered password with the stored hashed password
        if (password_verify($user_password, $hashedPassword)) {
            // store the data in session for later use
            session_start();
            $_SESSION['id'] = $row['id'];

            // Password is correct, redirect to the company dashboard
            header("Location: read.php");
            exit;
        } else {
            echo '<script> alert("Incorrect password");</script>';
        }
    } else {
        echo '<script> alert("User not found");</script>';
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Panel</title>
  <link rel="stylesheet" href="login.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  
  <div class="container">
    <div class="myform">
      <h1>LOGIN</h1>
      <form method="POST" onsubmit="return validateForm()">
        <h2>ADMIN</h2>
        <input type="text" name="email" id="email" placeholder="Admin Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit">LOGIN</button>
      </form>
    </div>
    <div class="image">
      <img src="alogin.png">
    </div>
  </div>

  <script>
    function validateForm() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        // Simple validation example (you can enhance this as needed)
        if (email === "" || password === "") {
            alert("Username and password are required");
            return false;
        }
        return true;
    }
  </script>
</body>
</html>

