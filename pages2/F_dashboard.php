<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minorproject";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// $hideProfilePictureForm = false; // Define the variable with a default value
// $hideProfilePictureUploaded = false; // Define the variable with a default value
// $profilePictureUpdated = false; // Define the variable with a default value

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['freelancerID'])) {
    $freelancerID = $_SESSION['freelancerID'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle form submissions or updates here

        // Example: Update skills, academicQualification, and experiences
        if (isset($_POST['skills']) && isset($_POST['academicQualification']) && isset($_POST['experiences'])) {
            $skills = mysqli_real_escape_string($conn, $_POST['skills']);
            $academicQualification = mysqli_real_escape_string($conn, $_POST['academicQualification']);
            $experiences = mysqli_real_escape_string($conn, $_POST['experiences']);

            $sqlUpdate = "UPDATE Freelancerss SET skills='$skills', academicQualification='$academicQualification', experiences='$experiences' WHERE freelancerID='$freelancerID'";
            mysqli_query($conn, $sqlUpdate);
        }

        // Handle profile picture uploading
        if (isset($_FILES['profile_picture'])) {
            $profilePictureName = $_FILES['profile_picture']['name'];
            $profilePictureTemp = $_FILES['profile_picture']['tmp_name'];
            $profilePicturePath = 'profile_pictures/' . $profilePictureName; 
            
            // Move uploaded file to target directory
            if (move_uploaded_file($profilePictureTemp, $profilePicturePath)) {
                // Update profile picture path in the database
                $sql = "UPDATE Freelancerss SET profile_picture=? WHERE freelancerID=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $profilePicturePath, $freelancerID);
                mysqli_stmt_execute($stmt);
                
                // Update $profilePicture variable
                $profilePicture = $profilePicturePath;

                // Set flag indicating profile picture is uploaded
                $profilePictureUploaded = true;
                $profilePictureUpdated = true; // Update profilePictureUpdated when picture is uploaded
            } else {
                $alertMessage = 'Failed to upload profile picture.';
            }
        }
    }

    $sql = "SELECT * FROM Freelancerss WHERE freelancerID='$freelancerID'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $email = $row['email'];
        $skills = $row['skills'];
        $academicQualification = $row['academicQualification'];
        $experiences = $row['experiences'];
        $profilePicture = $row['profile_picture'];

        // Check if profile picture has already been uploaded to hide the upload label and button
        if (isset($profilePicture) && !empty($profilePicture)) {
            $hideProfilePictureForm = true;
            $hideProfilePictureUploaded = true;
        }
    } else {
        $alertMessage = 'User details not found';
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    <link rel="stylesheet" href="./../assets2/F_dashboard.css">
</head>
<body>
<header>
    <div class="logo-container">
        <img src="./../assets2/logo new (1).jpg">
    </div>
</header>

<div class="container">
    <aside>
        <div class="sidebar">
            <a href="#">
                <span class="material-symbols-outlined">grid_view</span>
                <h3 class="dashboard" style="color:red" font_weight="100px"><strong>Dashboard</strong></h3>
            </a>
            <a href="#">
                <span class="material-symbols-outlined">account_circle</span>
                <h3><strong>My Profile</strong></h3>
            </a>
            <a href="F_recent.php">
                <span class="material-symbols-outlined">tab_recent</span>
                <h3><strong>Recent-Activity</strong></h3>
            </a>
            <a href="F_jobboard.php">
                <span class="material-symbols-outlined">business_center</span>
                <h3><strong>Job Board</strong></h3>
            </a>
        </div>
    </aside>

    <main>
        <div class="dashboard-container">
            <div class="profile-picture">
            <div id="profile-picture-frame">
    <img id="profile-picture" src="<?php echo isset($profilePicture) && !empty($profilePicture) ? $profilePicture : './../assets2/profile.jpg'; ?>" alt="Profile Picture">
</div>
                
                <form id="profilePictureForm" method="POST" enctype="multipart/form-data" <?php if ($hideProfilePictureForm) echo 'style="display: none;"'; ?>>
                    <div class="PP">
                        <label for="profile_picture">Profile Picture:</label>
                        <input type="file" id="profile_picture" name="profile_picture">
                    </div>
                    <?php if (!$hideProfilePictureUploaded): ?>
                    <div>
                        <button type="submit" class="ProfileButton">Upload Profile Picture</button>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
            <section class="profile-section">
                <h2><span style="color: purple;">Welcome,</span> <span style="color: green;"><?php echo $firstName; ?></span></h2>
                <div class="profile-info">
                    <label for="firstname">First Name:</label>
                    <span id="firstname"><?php echo $firstName; ?></span>
                </div>
                <div class="profile-info">
                    <label for="lastname">Last Name:</label>
                    <span id="lastname"><?php echo $lastName; ?></span>
                </div>
                <div class="profile-info">
                    <label for="email">Email Address:</label>
                    <span id="email"><?php echo $email; ?></span>
                </div>

                <form id="additionalDetailsForm" method="POST">
                    <div class="additionalinfo">
                        <label for="skills">Skills:</label>
                        <input type="text" id="skills" name="skills" value="<?php echo isset($skills) ? $skills : ''; ?>">
                    </div>
                    <div class="additionalinfo">
                        <label for="academicQualification">Academic Qualifications:</label><br>
                        <select id="academicQualification" name="academicQualification">
                            <option value="Below 10" <?php if(isset($academicQualification) && $academicQualification == 'Below 10') echo 'selected'; ?>>Below 10</option>
                            <option value="+2 pass" <?php if(isset($academicQualification) && $academicQualification == '+2 pass') echo 'selected'; ?>>+2 pass</option>
                            <option value="Bachelor" <?php if(isset($academicQualification) && $academicQualification == 'Bachelor') echo 'selected'; ?>>Bachelor</option>
                            <option value="Master" <?php if(isset($academicQualification) && $academicQualification == 'Master') echo 'selected'; ?>>Master</option>
                            <option value="PhD" <?php if(isset($academicQualification) && $academicQualification == 'PhD') echo 'selected'; ?>>PhD</option>
                        </select>
                    </div>
                    <div class="additionalinfo">
                        <label for="experiences">Experiences:</label><br>
                        <select id="experiences" name="experiences">
                            <option value="Less than 1 year" <?php if(isset($experiences) && $experiences == 'Less than 1 year') echo 'selected'; ?>>Less than 1 year</option>
                            <option value="1-3 years" <?php if(isset($experiences) && $experiences == '1-3 years') echo 'selected'; ?>>1-3 years</option>
                            <option value="3-5 years" <?php if(isset($experiences) && $experiences == '3-5 years') echo 'selected'; ?>>3-5 years</option>
                            <option value="5-10 years" <?php if(isset($experiences) && $experiences == '5-10 years') echo 'selected'; ?>>5-10 years</option>
                            <option value="More than 10 years" <?php if(isset($experiences) && $experiences == 'More than 10 years') echo 'selected'; ?>>More than 10 years</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit">Save</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var alertMessage = "<?php echo isset($alertMessage) ? $alertMessage : ''; ?>";
        if (alertMessage) {
            setTimeout(function () {
                alert(alertMessage);
            }, 100); // Adjust the timeout duration as needed
        }
    });
     // Check if profile picture is updated
     var profilePictureUpdated = <?php echo isset($profilePictureUpdated) ? 'true' : 'false'; ?>;

// Get the profile picture element
var profilePicture = document.getElementById('profile-picture');

// Set the alt text based on whether the profile picture is updated or not
if (!profilePictureUpdated) {
    profilePicture.alt = './../assets2/profile.jpg'; // If not updated, show the placeholder icon
}

</script>

</body>
</html>
