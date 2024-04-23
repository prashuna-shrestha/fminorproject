<?php
session_start();
$servername="localhost";
$username="root";
$password="";
$dbname="minorproject";
// creating connection
$conn=mysqli_connect($servername,$username,$password,$dbname);
// checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$company_id = $_SESSION['id'];

 // Select data from the database
$sql_company = "Select * from signupss where id = $company_id";

$result_company = mysqli_query($conn, $sql_company);// This function executes the SQL query ($sql_company) on the database connected by $conn. It returns a result resource if the query is successful, or FALSE on failure.
$company = mysqli_fetch_assoc($result_company);
//This variable now holds an associative array where the keys are column names, and the values are the corresponding data for a single row from the result set.
$companyname="companyName";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $jobPosition = $_POST['jobPosition'];
    $academicQualification = $_POST['academicQualification'];
    $experiences = $_POST['experiences'];
    $skills = $_POST['skills'];
    
    // $skills = $_POST['skills'];
    $freelancerRequired = $_POST['freelancerRequired'];
    $salary = $_POST['salary'];
    $deadline = $_POST['deadline'];

    // Insert data into the database
    $sql = "INSERT INTO C_Project (companyId,jobPosition, academicQualification, experiences, skills, freelancerRequired, salary, deadline) 
            VALUES ($company_id,'$jobPosition', '$academicQualification', '$experiences', '$skills', '$freelancerRequired','$salary', '$deadline')";
// echo $sql ;
// die();

if($conn->query($sql) === TRUE)  {
    // Redirect to job board after successful submission
    header("Location: C_jobboard.php");
    exit(); // Ensure script stops here
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}


// Close the database connection
mysqli_close($conn);


?>
<!-- html code-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./../assets2/C_projectProfile.css">
</head>
<body>
<header>
        <div class="logo-container">
          <img src="./../assets2/logo new (1).jpg" alt="Logo">
        </div>
    </header>
     
    <h1>Tell Us What You,<br> Need <span class="bold" style="color: blueviolet;">Done</span></h1>
    <form action="" method="post" onsubmit="return validateform()">
    <div class="container">
        <div class="box-container">
            <h2>Project Profile</h2>
            <div>
                <label for="Company Name">Company Name</label>
                <input type="text"name="companyName"id="companyName" value="<?= $company["company"]; ?>" readonly><br>
            </div>

    <div class="additionalinfo">
    <label for="jobPosition">Job Position:</label><br>
    <select id="jobPosition" name="jobPosition">
        <option value="Manager" >Manager</option>
        <option value="Graphic Designer" >Graphic Designer</option>
        <option value="Photographer" >Photographer</option>
        <option value="Human Resource Manager" >Human Resource Manager</option>
        <option value="Teacher" >Teacher</option>
        <option value="Financial Anaylyst" >Financial Analyst</option>
        <option value="Legal Assistant" >Legal Assistant</option>
        <option value="Electrician" >Electrician</option>
        <option value="Chef">Chef</option>
        <option value="Pharmacist">Pharmacist</option>
        <option value="Surgeon" >Surgen</option>
        <option value="Interior Designer">Interior Designer</option>
        <option value="Plumber" >Plumber</option>
        <option value="helper" >helper</option>
        <option value="Assistant" >Assistant</option>
        <!-- Add more options as needed -->
    </select>
</div>

<div class="additionalinfo">
    <label for="academicQualification">Academic Qualifications:</label><br>
    <select id="academicQualification" name="academicQualification">
        <option value="Secondary Level">Secondary Level</option>
        <option value="Undergraduate">Undergraduate</option>
        <option value="Graduate">Graduate</option>
        <option value="Postgraduate">Postgraduate</option>
        <!-- Add more options as needed -->
    </select>
</div>


<div class="additionalinfo">
    <label for="experiences">Experiences:</label><br>
    <select id="experiences" name="experiences">
        <option value="Less than 1 year">Less than 1 year</option>
        <option value="1-3 years">1-3 years</option>
        <option value="3-5 years">3-5 years</option>
        <option value="5-10years">5-10 years</option>
        <option value="More than 10 years">10+ years</option>
    </select>
</div>

    <div class="additionalinfo">
                <label for="Skills">Skills</label>
                <input type="text" name="skills" id="skills" required><br>
    </div>






    <div class="additionalinfo">
    <label for="freelancerRequired">Number of Freelancers Required:</label><br>
    <select id="freelancerRequired" name="freelancerRequired">
        <option value="1">1</option>
        <option value="1-5">1-5</option>
        <option value="5-10">5-10</option>
        <option value="10-20">10-20</option>
        <option value="More than 20">More than 20</option>
    </select>
</div>


<div class="additionalinfo">
    <label for="salary">Salary:</label><br>
    <select id="salary" name="salary" required>
        <option value="Less than 10,000">Less than 10,000</option>
        <option value="10,000-20,000">10,000-20,000</option>
        <option value="20,000-40,000">20,000-40,000</option>
        <option value="40,000-50,000">40,000-50,000</option>
        <option value="More than 50,000">More than 50,000</option>

        <!-- Add more options as needed -->
    </select>
</div>

           <div>
                <label for="Deadline">Deadline:</label>
                <input type="date" name="deadline" id="deadline" required><br>
            </div> 

            <div>
                <button class="button" type="submit">Post</button>
            </div>
        </div>
        <div class="image">
            <img src="./../assets2/ppHD.jpg">
        </div>
    </div>
</form>


<!-- js code-->
<script>
       function validateform(){
        var companyName=document.getElementById('companyName').value;
                var jobPosition = document.getElementById('jobPosition').value;
                var academicQualification = document.getElementById('academicQualification').value;
                var experiences = document.getElementById('experiences').value;
                var skills = document.getElementById('skills').value;
                var freelancersRequired = document.getElementById('freelancerRequired').value;
                var salary = document.getElementById('salary').value;
                var deadline = document.getElementById('deadline').value;

                
            // Simple validation example (you can enhance this as needed)
            if (companyName === "" || jobPosition === "" || academicQualification === "" || experiences === "" || skills === "" || freelancerRequired === "" || salary=== "" || deadline === "") {
        alert("All fields must be filled out");
        return false;
    }

    // Add additional validation logic if needed

    return true; // Form is valid
}
</script>



</body>
</html>