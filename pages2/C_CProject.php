<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "minorproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch project details based on the selected job position
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['jobPosition'])) {
        $jobPosition = $_GET['jobPosition'];

        // Fetch project details from Project table
        $sql = "SELECT * FROM C_Project WHERE jobPosition = '$jobPosition'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $companyId = $row["companyId"]; // Fetch companyId from the result
            $academicQualification = $row["academicQualification"];
            $experience = $row["experiences"];
            $skills = $row["skills"];
            $numFreelancers = $row["freelancerRequired"];
            $deadline = $row["deadline"];
            $salary= $row["salary"];

            // Fetch company details from signupss table
            $sqlCompany = "SELECT company FROM signupss WHERE id = '$companyId'";
            $resultCompany = $conn->query($sqlCompany);

            if ($resultCompany->num_rows > 0) {
                $rowCompany = $resultCompany->fetch_assoc();
                $companyName = isset($rowCompany["company"]) ? $rowCompany["company"] : "";
            } else {
                $companyName = "Company not found"; // Provide a default value or handle accordingly
            }
        } else {
            echo "No project found with the specified job position.";
        }
    } else {
        die("Invalid request");
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Profile</title>
    <link rel="stylesheet" href="./../assets2/C_CProject.css">
</head>
<body>

<!-- Header -->
<header>
<div class="logo-container">
      <img src="./../assets2/logo new (1).jpg" alt="Logo">
    </div>
</header>
<p>Find the right freelance service,<span class="bold" style="color:blueviolet;">right away</span></p>

<!-- Main Content -->
<div class="container">
    <!-- Display project details fetched from the database -->
    <div class="box-container">
   
        <div>
        <label class="label">Company Name:</label>
        <span id="companyName"><?php echo $companyName; ?></span>
    </div>

    <div>
        <label class="label">Job Position:</label>
        <span id="jobPosition"><?php echo $jobPosition; ?></span>
    </div>
    <div>
        <label class="label">Academic Qualification:</label>
        <span id="academicQualification"><?php echo $academicQualification; ?></span>
    </div>
    <div>
        <label class="label">Experience:</label>
        <span id="experience"><?php echo $experience; ?></span>
    </div>
    <div>
        <label class="label">Skills:</label>
        <span id="skills"><?php echo $skills; ?></span>
    </div>
    <div>
        <label class="label">Number of Freelancers Required:</label>
        <span id="numFreelancers"><?php echo $numFreelancers; ?></span>
    </div>
    <div>
        <label class="label">Salary offered:</label>
        <span id="salary"><?php echo $salary; ?></span>
    </div>

    <div>
        <label class="label">Deadline:</label>
        <span id="deadline"><?php echo $deadline; ?></span>
    </div>
    <div>

    <!-- Apply button with a link to the application page -->
  <a href="C_jobboard.php"> <button style="width:100px; height:40px;"  > back</button></a>
    </div>
</div>

  <div>
    <img src="./../assets2/cppHD.jpg">
    
</div>
</body>
</html>