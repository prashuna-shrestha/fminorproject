<?php
session_start();
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

// Initialize variables
$companyId = $jobPosition = $academicQualification = $experience = $skills = $numFreelancers = $deadline = "";

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
        } else {
            echo "No project found with the specified job position.";
        }
    } else {
        die("Invalid request");
    }
}



// Assuming these are initialized earlier in your code
$freelancerId = isset($_SESSION['freelancerID']) ? $_SESSION['freelancerID'] : 0;
$projectId = isset($_SESSION['projectID']) ? $_SESSION['projectID'] : 0;
$companyId = isset($_SESSION['companyId']) ? $_SESSION['companyId'] : 0;

// Fetch company and jobPosition details from the respective tables
$sqlCompany = "SELECT company FROM signupss WHERE id = '$companyId'";
$resultCompany = $conn->query($sqlCompany);

if ($resultCompany->num_rows > 0) {
    $rowCompany = $resultCompany->fetch_assoc();
    $companyName = isset($rowCompany["company"]) ? $rowCompany["company"] : "";
} else {
    $companyName = "Company not found"; // Provide a default value or handle accordingly
}

$sqlJobPosition = "SELECT jobPosition FROM C_Project WHERE ProjectID = '$projectId'";
$resultJobPosition = $conn->query($sqlJobPosition);

if ($resultJobPosition->num_rows > 0) {
    $rowJobPosition = $resultJobPosition->fetch_assoc();
    $jobPosition = isset($rowJobPosition["jobPosition"]) ? $rowJobPosition["jobPosition"] : "";
} else {
    $jobPosition = "Job Position not found"; // Provide a default value or handle accordingly
}

// to insert the proposal path
if (isset($_POST['submit'])) { // check the form if it is submitted
    $file = $_FILES['fileUpload'];
    // get information about the uploaded file from the $_FILES superglobal
    $fileName = $_FILES['fileUpload']['name'];
    $fileTmpName = $_FILES['fileUpload']['tmp_name'];
    $fileSize = $_FILES['fileUpload']['size'];
    $fileError = $_FILES['fileUpload']['error'];
    $fileType = $_FILES['fileUpload']['type'];
    // various detail about the uploaded file

    $fileExt = explode('.', $fileName);
    // split the filename into an array using the dot as a delimiter
    $fileActualExt = strtolower(end($fileExt));
    // get the last element of the array, which represents the file extensio

    $allowed = array('pdf');
    // define an array of allowed file extensions

    if (in_array($fileActualExt, $allowed)) {
        // cheking it is on pdf or not
        if ($fileError === 0) {
            if ($fileSize < ) {
                // file size is below the cert1000000ain limit (1 mb)
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                // generate a unique filename to avoid overwriting existing files
                $fileDestination = 'upppload/' . $fileNameNew;
                // set the destination path for the uploaded file

                move_uploaded_file($fileTmpName, $fileDestination);
                // move the uploaded file from the temporary directory to the specified destination

                // Insert data into Proposalsas table
                $insertQuery = "INSERT INTO Proposalsass (companyId, FreelancerID, ProjectID, fileUpload, company, jobPosition) 
                                VALUES ('$companyId', '$freelancerId', '$projectId', '$fileDestination', '$companyName', '$jobPosition')";

                if ($conn->query($insertQuery) === TRUE) {
                    echo '<script>alert("File uploaded successfully!");</script>';

                } else {
                    echo "Error: " . $insertQuery . "<br>" . $conn->error;
                }
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "Shows an error uploading the file!";
        }
    } else {
        echo "You cannot upload a file of this type!";
    }
}

// Rest of your existing code...

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Profile</title>
    <link rel="stylesheet" href="./../assets2/F_projectProfile.css">
</head>
<body>

<!-- Header -->
<header>
   <div class="logo-container">
    <img src="./../assets2/logo new (1).jpg">
</div>
</header>
<p>Get Your Desired <span class="bold" style="color:blueviolet;">Job</span></p>

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
        <label class="label">Deadline:</label>
        <span id="deadline"><?php echo $deadline; ?></span>
    </div>


    <!-- Upload file button -->
    <form method="post" action="" enctype="multipart/form-data">
        <input type="file" id="fileUpload" name="fileUpload">
        <button type="submit" name="submit">Upload File</button>
    </form>
    
</div>
<div>
    <img src="./../assets2/projectprofile.jpg">
</div>
</div>
</body>
</html>