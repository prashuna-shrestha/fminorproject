<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minorproject";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['id'])) {//check if the user has login using session varible and proceeeds with the script is true
    $id = $_SESSION['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['companyname']) && isset($_POST['location'])) {
            $companyname = mysqli_real_escape_string($conn, $_POST['companyname']);
            $location = mysqli_real_escape_string($conn, $_POST['location']);

            if (!empty($companyname) && !empty($location)) {// check the posted company name and location
                $sql = "UPDATE signupss SET company='$companyname', location='$location' WHERE id='$id'";

                if (mysqli_query($conn, $sql)) {
                    $alertMessage = 'Profile updated successfully.';
                } else {
                    $alertMessage = 'Error updating profile: ' . mysqli_error($conn);
                }
            } else {
                $alertMessage = 'Company name and location are required.';
            }
        }

        // Handle profile picture uploading
        if (isset($_FILES['profile_picture'])) {//condition checks if the "profile_picture" file input in the form has been set.
            $profilePictureName = $_FILES['profile_picture']['name'];// the orginal name of the uploded file
            $profilePictureTemp = $_FILES['profile_picture']['tmp_name'];// the temporary file name
            $profilePicturePath = 'company_profile_pictures/' . $profilePictureName; 
            
            // Move uploaded file to target directory
            if (move_uploaded_file($profilePictureTemp, $profilePicturePath)) {//move_uploaded_file function is used to move the uploaded file from its temporary location ($profilePictureTemp) to the specified target path ($profilePicturePath). This function returns true on success and false on failure.
                // Update profile picture path in the database
                $sql = "UPDATE signupss SET profile_picture=? WHERE id =?";//specific id ma path store
                $stmt = mysqli_prepare($conn, $sql);// is used to create a prepared statement. Prepared statements are a way to execute SQL queries with parameters, providing a layer of security against SQL injection.
                mysqli_stmt_bind_param($stmt, "si", $profilePicturePath, $id);//$profilePicturePath is the new path of the profile picture.
               // $id is the user ID for whom the profile picture is being updated
                mysqli_stmt_execute($stmt);//mysqli_stmt_execute is used to execute the prepared statement. This actually performs the database update operation.
                
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

    $sql = "SELECT * FROM signupss WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $companyname = $row['company'];
        $location = $row['location'];
        $profilePicture = $row['profile_picture'];
    } else {
        $alertMessage = 'User details not found';
    }

    $companynameAlreadySet = !empty($companyname);// check the certain information is already provided
    $locationAlreadySet = !empty($location);

    // Check if profile picture has been uploaded to hide the upload section
    if (isset($profilePictureUploaded) && $profilePictureUploaded) {
        $hideProfilePictureUpload = true;
    } else {
        $hideProfilePictureUpload = false;
    }

    // Check if profile picture has already been uploaded to hide the upload label and button
    if (isset($profilePicture) && !empty($profilePicture)) {
        $hideProfilePictureForm = true;
    } else {
        $hideProfilePictureForm = false;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="./../assets2/C_dashboard.css">
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

            <?php if ($companynameAlreadySet && $locationAlreadySet): ?>
                <a href="C_projectProfile.php">
                    <span class="material-symbols-outlined">tab_recent</span>
                    <h3><strong>Post a Job</strong></h3>
                </a>
            <?php else: ?>
                <!-- the style set to disable pointer event that mean the link inside the link is not clickable -->
                <div style="pointer-events: none;">
                    <a>
                        <span class="material-symbols-outlined">tab_recent</span>
                        <h3><strong>Post a Job</strong></h3>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($companynameAlreadySet && $locationAlreadySet): ?>
                <a href="C_jobboard.php">
                    <span class="material-symbols-outlined">business_center</span>
                    <h3><strong>Job Board</strong></h3>
                </a>
            <?php else: ?>
                <div style="pointer-events: none;">
                    <a>
                        <span class="material-symbols-outlined">business_center</span>
                        <h3><strong>Job Board</strong></h3>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($companynameAlreadySet && $locationAlreadySet): ?>
                <a href="C_review.php">
                    <span class="material-symbols-outlined">business_center</span>
                    <h3><strong>Review Proposals</strong></h3>
                </a>
            <?php else: ?>
                <div style="pointer-events: none;">
                    <a>
                        <span class="material-symbols-outlined">business_center</span>
                        <h3><strong>Review Proposals</strong></h3>
                    </a>
                </div>
            <?php endif; ?>
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
               
                <?php if (!$hideProfilePictureUpload): ?>
                    <div>
                        <button type="submit" class="ProfileButton">Upload Profile Picture</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <section class="profile-section">
            <h2><span style="color: purple;">Welcome,</span>  <span style="color: purple;"><?php echo $firstname; ?></span></h2>
            <div class="profile-info">
                <label for="firstname">First Name:</label>
                <span id="firstname"><?php echo $firstname; ?></span>
            </div>
            <div class="profile-info">
                <label for="lastname">Last Name:</label>
                <span id="lastname"><?php echo $lastname; ?></span>
            </div>
            <div class="profile-info">
                <label for="email">Email Address:</label>
                <span id="email"><?php echo $email; ?></span>
            </div>

            <form id="additionalDetailsForm" method="POST">
                <div class="additionalinfo">
                    <label for="companyname">Company Name:</label>
                    <?php if ($companynameAlreadySet): ?>
                        <span class="retrieved-data"><?php echo $companyname; ?></span>
                    <?php else: ?>
                        <input type="text" id="companyname" name="companyname" value="<?php echo isset($companyname) ? $companyname : ''; ?>">
                    <?php endif; ?>
                </div>
                <div class="additionalinfo">
                    <label for="location">Location:</label>
                    <?php if ($locationAlreadySet): ?>
                        <span class="retrieved-data"><?php echo $location; ?></span>
                    <?php else: ?>
                        <input type="text" id="location" name="location" value="<?php echo isset($location) ? $location : ''; ?>">
                    <?php endif; ?>
                </div>
                
                <div>
                    <?php if (!$companynameAlreadySet || !$locationAlreadySet): ?>
                        <button type="submit">Update Profile</button>
                    <?php endif; ?>
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

    // Check if profile picture is updated
    var profilePictureUpdated = <?php echo isset($profilePictureUpdated) ? 'true' : 'false'; ?>;

    // Get the profile picture element
    var profilePicture = document.getElementById('profile-picture');

    // Set the alt text based on whether the profile picture is updated or not
    if (!profilePictureUpdated) {
        profilePicture.alt = './../assets2/profile.jpg'; // If not updated, show the placeholder icon
    }
});
</script>

</body>
</html>
