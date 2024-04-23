<?php
 session_start();
$servername="localhost";
$username="root";
$password="";
$databasename="minorproject";


// create connection
$conn = mysqli_connect($servername, $username, $password, $databasename);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? mysqli_real_escape_string($conn, $_POST["email"]) : "";
    $password = isset($_POST["password"]) ? mysqli_real_escape_string($conn, $_POST["password"]) : "";
    // Check against the database email
    $sql = "SELECT * FROM Freelancerss WHERE email = '$email' ";
    $result = mysqli_query($conn, $sql);

// if the email is found then
    if ($result && mysqli_num_rows($result) > 0) {
        // get the users data from the database
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row["passwordHash"];

        // Verify the entered password with the stored hashed password
        if (password_verify($password, $hashedPassword)) {
          if($row["isactive"]=="Y"){
            // store the data in session for later use
         
           $_SESSION['freelancerID']= $row['freelancerID'];
    
             // Password is correct, redirect to the company dashboard
              header("Location:F_dashboard.php");
            exit;
        } else{
            echo'<script> alert("user not active. please contact adminstrator");</script>';
       }
     } else {
            echo '<script> alert("Incorrect password");</script>';
        }
    }
    else {
        echo '<script> alert("user not found");</script>';
    }
    mysqli_free_result($result);
}

// Close the database connection
 mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./../assets2/F_login.css">


</head>
<body >

   <div class="container">
    <div class="myform">
        <h1>Welcome Back</h1>
        <form  method="post" action="">
            <h2>Log In</h2>
                <input type="text" id="email"  name="email"placeholder="Email Address" required>
                <input type="Password" id="password" name="password"  placeholder="Password" required>
                <button type="submit" class="btn" onclick="validateForm()">Login</button>
   
                 <p class="login-link">Don't have an account?<a href="F_signup.php" style="color:darkgreen;"> Register</a>
                 </p>
</form>
</div>
<div class="image">
    <img src="./../assets2/loginHD.jpg">
</div>
</div>



<!-- javascript code goes here-->
<script>
    function validateForm() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        // Simple validation example (you can enhance this as needed)
        if (email === "" || password === "") {
            alert("Username and password are required");
            return;
        }
       

    }
</script>
    <!-- end of js code-->
</body>
</html>