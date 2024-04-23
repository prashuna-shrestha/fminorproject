<?php
session_start();    
$servername = "localhost";
$email = "root";
$password = "";
$dbname = "minorproject";

// create connection
$conn = new mySqli($servername, $email, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //If they are set, it uses mysqli_real_escape_string to escape any special characters in the input before
    // assigning them to the corresponding variables. If they are not set, empty strings are assigned to the variables.
    $email = isset($_POST["email"]) ? mysqli_real_escape_string($conn, $_POST["email"]) : "";
    $password = isset($_POST["password"]) ? mysqli_real_escape_string($conn, $_POST["password"]) : "";

    // Check against the database email
    $sql = "SELECT * FROM signupss WHERE email = '$email' ";
    $result = mysqli_query($conn, $sql); // store the selected queries

    // if the email is found then
    if ($result && mysqli_num_rows($result) > 0) {// email
        // get the user's data from the database
        $row = mysqli_fetch_assoc($result);//mysqli_fetch_assoc($result). This function returns an associative 
        //array representing the fetched row, where keys are column names
        $hashedPassword = $row["password_hash"]; // move this line here

        // Verify the entered password with the stored hashed password
        if (password_verify($password, $hashedPassword)) {
            if($row["isactive"] == "Y"){
                // store the data in session for later use
                $_SESSION['id'] = $row['id'];
            
                // Password is correct, redirect to the company dashboard
                header("Location: C_dashboard.php");
                exit;// ensures that no futher code to be executed.
            }else{
                echo '<script> alert("User not active. Please contact adminstrator");</script>';
            }
        } else {
            echo '<script> alert("Incorrect password");</script>';
        }
    } else {
        echo '<script> alert("User not found");</script>';
    }

    mysqli_free_result($result);// free the result set (mysqli_result_fetch)helps release the memory occupied by the result set
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./../assets2/C_login.css">


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
   
                 <p class="login-link">Don't have an account?<a href="C_signup.php" style="color:darkgreen;"> Register</a>
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