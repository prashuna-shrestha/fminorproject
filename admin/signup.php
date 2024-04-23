<?php // php code

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {// check if the form has been submitted using the post method
    // Retrieve form data (user input) and handle case where they are not set
     $firstname = isset($_POST["firstName"])? $_POST["firstName"]:"" ;
    $lastname = isset($_POST["lastName"]) ? $_POST["lastName"]:"";
    $email = isset($_POST["email"])? $_POST["email"]:"" ;
    $contact= isset($_POST["contact"])? $_POST["contact"]:"";
    $age= isset($_POST["age"])? $_POST["age"]:"";
    $password = isset($_POST["password"])? $_POST["password"]:""  ;
    $confirm = $_POST['confirm-password'];
    

    
    // Hash the password (for security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the table
    if($confirm === $password){
        $sql = "INSERT INTO user (firstname, lastname, email, password_hash,contact,age)
        VALUES ('$firstname', '$lastname', '$email', '$hashedPassword',$contact,$age)";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert( "Signup successful!");</script>' ;
            header("location: login.php");//Redirects the user to the login page Redirects the user to the login page  
        } else {
            echo "Error: " . $conn->error;
        }
    }
    else{
        // if passwords dont match display an alert
        echo "<script> alert('Passwords Didn't Matched!') </script>";
    }
    
}

// Close the database connection
$conn->close();


?> 




<!-- html code -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Sign Up</title>
    <link rel="stylesheet" href="signup.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   
</head>

<body>
    <div class="container">
    <div class="myform">
        <h1>SignUp</h1>
        <form id="signup-form" action="signup.php" method="post">
        
        <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
        <input type="email" id="email" name="email" placeholder="Email Address" required>
        <input type="tel" id="contact" name="contact" placeholder="contact number" required>
        <input type="int" id="age" name="age" placeholder="age" required>
    
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Password " required>
            <span class="eye-icon" onclick="togglePasswordVisibility('password')">&#x1F441;</span>
        </div>
        <input type="password" id="confirm-password" placeholder="Confirm Password" name = "confirm-password" required>
        <button type="button" onclick="if (validateForm()) { document.getElementById('signup-form').submit(); }">Create Account</button>


        <p class="login-link">Already have an account? <a href="login.php" style="color: blue;">Log In</a></p>
    </form>
</div>
<div class="image">
    <img src="asignup.jpg">
</div>
</div>



  <!-- js code-->
  <script>
    function validateForm() {
        var firstname = document.getElementById("firstName").value;
        var lastname = document.getElementById("lastName").value;
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm-password").value;
        var contact=document.getElementById("contact").value;
         var age=document.getElementById("age").value;

        // Verify email using regular expression
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address");
            return false; // Prevent form submission
        }

        // Check if password meets strength criteria
        if (!isStrongPassword(password)) {
            alert("Please choose a stronger password");
            return false; // Prevent form submission
        }

        if (firstname === "" || lastname === "" || email === "" || password === "" || confirmPassword === "" || contact===""  || age==="") {
            alert("All fields must be filled out");
            return false; // Prevent form submission
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match");
            return false; // Prevent form submission
        }

        // Return true only if all checks pass
        return true;
    }

    function togglePasswordVisibility(fieldId) {
        var passwordField = document.getElementById(fieldId);
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    }

    // Function to check password strength
    function isStrongPassword(password) {
        // Add your own criteria for a strong password
        // For example, you may check for a minimum length and the presence of uppercase, lowercase, and numeric characters.
        var minLength = 8;
        var hasUpperCase = /[A-Z]/.test(password);
        var hasLowerCase = /[a-z]/.test(password);
        var hasNumber = /\d/.test(password);

        return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumber;
    }
</script>
</body>

</html>