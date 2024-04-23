<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minorproject";

$con = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search_btn'])) {
        $keyword = $_POST['form_search'];
        if (isset($keyword)) {
            $search_sql = "SELECT C_Project.*, signupss.company, signupss.profile_picture FROM C_Project
            JOIN signupss ON C_Project.companyId = signupss.id
            WHERE C_Project.jobPosition LIKE '%" . $keyword . "%';";
        }
    }

    if (isset($_POST['sort_btn'])) {
        $sort_keyword = $_POST['sort_data'];
        if (isset($sort_keyword)) {
            if ($sort_keyword == 'position_asc') {
                $search_sql = "SELECT C_Project.*, signupss.company, signupss.profile_picture FROM C_Project
                JOIN signupss ON C_Project.companyId = signupss.id
                WHERE C_Project.jobPosition LIKE '%" . $keyword . "%'
                ORDER BY C_Project.jobPosition ASC;";
            } else {
                $search_sql = "SELECT C_Project.*, signupss.company, signupss.profile_picture FROM C_Project
                JOIN signupss ON C_Project.companyId = signupss.id
                WHERE C_Project.jobPosition LIKE '%" . $keyword . "%'
                ORDER BY C_Project.jobPosition DESC;";
            }
        }
    }
}

$sql = isset($search_sql) ? $search_sql : "SELECT C_Project.*, signupss.company, signupss.profile_picture FROM C_Project
JOIN signupss ON C_Project.companyId = signupss.id;";

$result = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Connect</title>
    <link rel="stylesheet" href="./../assets2/C_jobboard.css">
</head>
<body>
<header>
    <div class="logo-container">
        <img src="./../assets2/logo new (1).jpg">
    </div>
</header>

<div class="html_form">
    <img src="./../assets2/search.avif">
    <form action="C_jobboard.php" method='POST'>
        <div class="search_bar">
            <input type="search" name="form_search" placeholder="ENTER YOUR KEYWORD ....">
            <button type="submit" name="search_btn"> SEARCH </button>
        </div>
        <div class="search_dropdown">
            <select name="sort_data">
                <option selected disabled> SELECT YOUR OPTION </option>
                <option value="position_asc"> ASCENDING </option>
                <option value="position_desc"> DESCENDING </option>
            </select>
            <button type="submit" name="sort_btn"> SORT </button>
        </div>
    </form>
</div>

<div class='job-box'>
<?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='job-box-role'>";
        echo "<div class='image'><img src='" . $row['profile_picture'] . "' alt='Company Logo'></div>";
        echo "<div class='info'>";
        echo "<p>Company: " . $row['company'] . "</p>";
        echo "<a href='C_CProject.php?jobPosition=" . $row['jobPosition'] . "' >";//C_CProject.php page with the 'jobPosition' parameter in the URL.
        echo "<p>Job Position: " .$row['jobPosition'] . "</p>";
        echo "<p>Salary: " . $row['salary'] . "</p>";
        echo "</div>"; // Closing the 'info' div
        echo "</a>"; // Closing the anchor tag
        echo "</div>"; // Closing the 'job-box-role' div
    }
?>
</div>

</body>
</html>