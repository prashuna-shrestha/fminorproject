<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Proposals</title>
    <link rel="stylesheet" href="./../assets2/C_review.css">
</head>

<body>
<header>
       <div class="logo-container">
        <img src="./../assets2/logo new (1).jpg">
        </div>
    </header>
 
    <main>
    <div class="image-container">
            <img src="./../assets2/reviewHD.jpg">
            <h1><span style="color:blueviolet">Together,</span> we can do better.</h1>
    </div>
        <section>
            <?php
            session_start(); // Start the session

            // Replace these variables with your database credentials
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

            // Check if a company is logged in (assuming you have stored company ID in the session)
            if (isset($_SESSION['id'])) {
                $companyId = $_SESSION['id'];

                // SQL query to select job positions and the list of freelancers who applied for each job for the specific company
                $sql = "SELECT C_Project.ProjectID, C_Project.jobPosition, 
                               Proposalsass.proposalID, 
                               Proposalsass.fileUpload, 
                               Proposalsass.status,
                               Proposalsass.company, -- Added company name
                               C_Project.jobPosition AS proposalJobPosition, -- Added job position
                               GROUP_CONCAT(Freelancerss.email) AS FreelancerList
                        FROM C_Project
                        LEFT JOIN Proposalsass ON C_Project.ProjectID = Proposalsass.ProjectID
                        LEFT JOIN Freelancerss ON Proposalsass.FreelancerID = Freelancerss.freelancerID
                        WHERE C_Project.companyId = $companyId
                        GROUP BY C_Project.ProjectID, C_Project.jobPosition, Proposalsass.proposalID, Proposalsass.fileUpload, Proposalsass.status, Proposalsass.company";

                // Execute the query
                $result = $conn->query($sql);

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        $jobPosition = $row["jobPosition"];
                        $freelancerList = $row["FreelancerList"];
                        $proposalID = $row["proposalID"];
                        $fileUpload = $row["fileUpload"];
                        $status = $row["status"];
                        $companyName = $row["company"]; // Added company name
                        $proposalJobPosition = $row["proposalJobPosition"]; // Added job position

                       
                if ($status != 'rejected') {
    // Output HTML for each job position and freelancer list
    echo '<div class="proposal" id="proposal_' . $proposalID . '" onclick="toggleDetails(\'' . $proposalID . '\')">';
    echo '<h3>Job Position: ' . $jobPosition . '</h3>';
    echo '<p>Freelancers Applied: ' . $freelancerList . '</p>';
    echo '</div>';

    // Proposal details section
    echo '<div id="' . $proposalID . 'Details" class="proposal-details" style="display: none;">';
    echo '<p>Details about the proposal...</p>';
    echo '<p>Company: ' . $companyName . '</p>'; // Display company name
    echo '<p>Job Position: ' . $proposalJobPosition . '</p>'; // Display proposal job position
    echo '<div class="pdf-viewer">';
    echo '<iframe src="' . $fileUpload . '" width="100%" height="500px"></iframe>';
    echo '</div>';
    echo '<div class="action-buttons">';

    // Check if the status is not 'accepted' or 'hired'
    if ($status != 'accepted' ) {
        // Allow actions only if the status is not 'accepted' or 'hired'
        echo '<button class="button hire-button" onclick="handleFreelancerAction(\'hire\', ' . $proposalID . ', \'' . $jobPosition . '\', \'' . $companyName . '\', \'' . $proposalJobPosition . '\')">Hire</button>';
        echo '<button class="button reject-button" onclick="handleFreelancerAction(\'reject\', ' . $proposalID . ')">Reject</button>';
    } else {
        // Display a message if the proposal is already accepted or hired
        echo '<p>This proposal has already been ' . ($status == 'accepted' ? 'accepted' : 'hired') . '.</p>';
    }

    echo '</div>';
    echo '</div>';
} else {
    // Proposal has been rejected, skip displaying it
    continue;
}

                    }
                }
            }

            // Close the database connection
            $conn->close();
             ?>
        </section>
    </main>
    <script>
        // this function is use to display the proposal details
    function toggleDetails(proposalID) {// get the proposal details by the Id
        var proposalDetails = document.getElementById(proposalID + 'Details');
        if (!proposalDetails) {
            // Create proposal details dynamically if not already present
            proposalDetails = document.createElement('div');
            proposalDetails.id = proposalID + 'Details';// details create a unique identifier for the details section associated with the particular proposal
            proposalDetails.className = 'proposal-details';//only done to assign css
            proposalDetails.style.display = 'none';// hides the proposal detail becomes invisible

            document.querySelector('main section').appendChild(proposalDetails);
            //this is a query where that when a proposal details seciton is created , it is appended to the exisiting HTML structure wiht the main section
        }

        // Check if the proposal has already been hired
        var hireButton = document.querySelector('#proposal_' + proposalID + 'Details .hire-button');
        //document.querySelector is a document object model(dom) that allow you to select the first element in the document that matches the specified
        // css selector
        if (hireButton && hireButton.disabled) {
            alert('Proposal already hired!');
            return;
        }

        // Toggle the display of proposal details
        proposalDetails.style.display = proposalDetails.style.display === 'block' ? 'none' : 'block';
        //So, the entire line essentially toggles the visibility of the proposalDetails element. If it's currently visible ('block'), it sets the display property to 'none' to hide it. If it's not visible, it sets the display property to 'block' to make it visible.
    }

    function handleFreelancerAction(action, proposalID, jobPosition, companyName, proposalJobPosition) {
    var proposalDetails = document.getElementById(proposalID + 'Details');
    //This line retrieves the HTML element that represents the details section of a specific proposal using the getElementById method. The ID is generated by concatenating the proposalID with 'Details'.
    var hireButton = document.querySelector('#proposal_' + proposalID + 'Details .hire-button');
    //This line uses querySelector to find the HTML element with the class 'hire-button' that is a descendant of the details section associated with the specific proposal.
    // Check if the proposal has already been hired or rejected
    if (hireButton && hireButton.disabled) {
        alert('Proposal already hired or rejected!');
        return;
    }

    var xhr = new XMLHttpRequest();
    // The XMLHttpRequest object is part of the browser's built-in JavaScript functionality and provides a way to interact with servers.
    xhr.onreadystatechange = function () {
        // This sets up an event handler function that will be called every time the readyState property of the XMLHttpRequest object changes.
        if (xhr.readyState == 4 && xhr.status == 200) {
            //This condition checks whether the request has been completed (readyState == 4) and whether the HTTP status code is 200 (OK). This typically indicates a successful response from the server.
            var responseArray = xhr.responseText.split("|");
            //It takes the response text from the server (xhr.responseText) and splits it into an array using the pipe character (|) as the delimiter. This suggests that the server might be sending a response with multiple parts separated by the pipe character.

            var response = responseArray[0];
            //: This extracts the first part of the response array. It assumes that the server's response is structured in a way that the first part (index 0) contains the relevant information.

            if (response.trim() === "success") {//This condition checks whether the trimmed response (without leading or trailing whitespaces) is equal to the string "success". If the condition is true, it implies that the server has acknowledged the success of the request.
                var company = responseArray[1];// retrive the company
                var hiredJobPosition = responseArray[2];// retrived the jobposition

                alert('this proposal has been reviewed!');

                // Optionally, you can also remove the corresponding proposal div here
       
                // Disable buttons after hiring
                disableActionButtons(proposalID);
            
            } else {
                alert('Error: ' + response);
            }
        }
    };

    var actionURL = action === 'hire' ?
    //This is a ternary operator that sets the actionURL variable based on the value of the action variable. If action is equal to 'hire', it constructs a URL for hiring a freelancer; otherwise, it constructs a URL for updating/rejecting the status.
        "hire_freelancer.php?proposalID=" + proposalID +// Appends the proposalID as a query parameter to the URL.(get)
        "&jobPosition=" + encodeURIComponent(jobPosition) +// Appends the jobPosition as a query parameter to the URL, ensuring it's properly encoded.
        "&companyName=" + encodeURIComponent(companyName) +// Appends the companyName as a query parameter to the URL, ensuring it's properly encoded.
        "&proposalJobPosition=" + encodeURIComponent(proposalJobPosition) ://Appends the proposalJobPosition as a query parameter to the URL, ensuring it's properly encode
        "update_reject_status.php?proposalID=" + proposalID;// (get)Appends the proposalID as a query parameter to the URL.

    xhr.open("GET", actionURL, true);
    xhr.send();//Sends the XMLHttpRequest.
}

</script>


</body>

</html>
