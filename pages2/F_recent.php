<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minorproject";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['freelancerID'])) {
    $freelancerID = $_SESSION['freelancerID'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="./../assets2/F_recent.css">
  </head>
  <body>
    <header>
      <div class="logo-container">
        <img src="./../assets2/logo new (1).jpg">
      </div>
    </header>
    
    <div class="container">
      <!-- aside starts (menu) -->
      <aside>
      
        <div class="sidebar">
          <a href="#">
            <span class="material-symbols-outlined">grid_view</span>
            <h3 class="dashboard" style="color:red" font_weight="100px"><strong>Dashboard</strong></h3>
          </a>
          <a href="F_dashboard.php">
            <span class="material-symbols-outlined">account_circle</span>
            <h3><strong>My Profile</strong></h3>
          </a>
          <a href="F_jobboard.php">
            <span class="material-symbols-outlined">business_center</span>
            <h3><strong>Job Board</strong></h3>
          </a>
          <a href="#">
            <span class="material-symbols-outlined">tab_recent</span>
            <h3><strong>Recent-Activity</strong></h3>
          </a>
        </div>

      </aside>
       <!-- aside end(menu) -->
       

      <!-- main section starts-->
      <main>
          <!-- Freelancer Dashboard Structure -->
          <div class="dashboard-container">
          <h2>Recent Activity</h2>

          <?php
          // Query to get distinct companies for recent activity
          $companyQuery = "SELECT DISTINCT company FROM Proposalsass WHERE freelancerID = $freelancerID AND status = 'accepted'";
          $companyResult = mysqli_query($conn, $companyQuery);

          if ($companyResult && mysqli_num_rows($companyResult) > 0) {
              while ($companyRow = mysqli_fetch_assoc($companyResult)) {
                  $companyName = $companyRow['company'];

                  // Fetch recent activity for each company
                  $recentActivitySql = "SELECT * FROM Proposalsass WHERE freelancerID = $freelancerID AND company = '$companyName' AND status = 'accepted' ORDER BY submissionTime DESC LIMIT 5";
                  $recentActivityResult = mysqli_query($conn, $recentActivitySql);

                  if ($recentActivityResult && mysqli_num_rows($recentActivityResult) > 0) {
                      echo '<div class="recent-activity-section">';
                      echo "<h3>Recent Activity for $companyName:</h3>";
                      echo "<ul>";
                      while ($activityRow = mysqli_fetch_assoc($recentActivityResult)) {
                          echo "<li>Hired for the position of " . $activityRow['jobPosition']  . "</li>";
                      }
                      echo "</ul>";
                      echo '</div>';
                  } else {
                      echo "<div class='recent-activity-section'>";
                      echo "<h3>No recent activity for $companyName.</h3>";
                      echo "</div>";
                  }
              }
          } else {
              echo "<div class='recent-activity-section'>";
              echo "<h3>No recent activity.</h3>";
              echo "</div>";
          }
          ?>

        </div>
    </div>
      </main>
      <!-- main section ends -->
    </div>
    
  </body>
  
</html>