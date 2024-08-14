<?php
session_start();
include 'navbar.footer.php';
include 'db_connection.php';
$con = OpenCon(); // Open database connection

$username = $_SESSION['Username'];
$currentDay = date('l'); // Get the current day, e.g., "Monday"

// Fetch total exercises for the current day from the stats table
$query = "SELECT totalex, nowex FROM stats WHERE username = '$username' AND day = '$currentDay'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $totalex = $row['totalex'];
    $nowex = $row['nowex'];
} else {
    // No data for the current day, set totalex and nowex to 0
    $totalex = 0;
    $nowex = 0;
}

// Query to get the user's ID from the users table
$userQuery = "SELECT id FROM users WHERE username = '$username'";
$userResult = mysqli_query($con, $userQuery);
$goal = isset($_SESSION['goal']) ? $_SESSION['goal'] : 'No goal set';

if (mysqli_num_rows($userResult) > 0) {
    $userData = mysqli_fetch_assoc($userResult);
    $userId = $userData['id'];

    // Check if the user's ID exists in the manager table
    $managerQuery = "SELECT * FROM manager WHERE manager_id = '$userId'";
    $managerResult = mysqli_query($con, $managerQuery);

    $managerQuery1 = "SELECT * FROM admin WHERE id = '$userId'";
    $managerResult1 = mysqli_query($con, $managerQuery1);

    if (mysqli_num_rows($managerResult1) > 0) {
        echo "<button class='bw-button' onclick='toggleVisibility(\"productManagement\")'>Product Management</button>";
        echo "<div id='productManagement' style='display:none;'>";
        echo "<h3>Product Management</h3>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Add Product'>";
        echo "</form>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Remove Product'>";
        echo "</form>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Show all product'>";
        echo "</form>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Show Additional Options'>";
        echo "</form>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='add to inventory'>";
        echo "</form>";
        echo "</div>";
    } elseif (mysqli_num_rows($managerResult) > 0) {
        // User is a manager
        echo "<div class='manager-panel'>";
        echo "<h2>Manager Panel</h2>";
        echo "<button class='bw-button' onclick='toggleVisibility(\"userManagement\")'>User Management</button>";
        echo "<div id='userManagement' style='display:none;'>";
        echo "<h3>User Management</h3>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Add User'>";
        echo "</form>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Remove User'>";
        echo "</form>";
        echo "<form action='whatmangmentcando.php' method='post'>";
        echo "<input type='submit' name='submit' value='Show all users'>";
        echo "</form>";
        echo "</div>";
        echo "<button class='bw-button' onclick='window.location.href=\"makeexforusers.php\"'>Make Exercise</button>";

        // Show manager's data with inputs
        echo "<h2>Manager Information</h2>";
        $managerDataQuery = "SELECT * FROM users WHERE id='$userId'";
        $managerDataResult = mysqli_query($con, $managerDataQuery);
        $_SESSION['userId'] = $userId;

        if (mysqli_num_rows($managerDataResult) > 0) {
            echo "<table border='1' id='managerData'>
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Birthday</th>
                    <th>ID</th>
                    <th>Locked</th>
                    <th>Login Attempts</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($managerDataResult)) {
                echo "<tr>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['password']}</td>";
                echo "<td>{$row['firstname']}</td>";
                echo "<td>{$row['lastname']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['phone']}</td>";
                echo "<td>{$row['birthday']}</td>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['looked']}</td>";
                echo "<td>{$row['login_attempts']}</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<button onclick='toggleVisibility(\"managerData\")' class='action-button'>Show Manager Data</button>";
            echo "<button onclick='window.location.href=\"forgetpassword.php\"' class='action-button'>Change Password</button>";
            echo "<br><br><br><br><br>";
        } else {
            echo "Manager information not found.";
        }
        echo "</div>";
    } else {
        // User is not a manager
        // echo "<form action='whatmangmentcando.php' method='post'>";
        // echo "<input type='submit' name='submit' value='Show Order' class='action-button'>";
        // echo "</form>";

            
 
        $memberDataQuery = "SELECT * FROM users WHERE id='$userId'";
        $memberDataResult = mysqli_query($con, $memberDataQuery);
        $_SESSION['userId'] = $userId;

        if (mysqli_num_rows($memberDataResult) > 0) {
            echo "<div class='member-panel'>";
            echo "<h2>Member Panel</h2>";
            echo "<button onclick='toggleVisibility(\"memberData\")' class='action-button'>Show Member Data</button>";
            echo "<div id='memberData' style='display:none;'>";
            echo "<table border='1'>
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Birthday</th>
                    <th>ID</th>
                    <th>Locked</th>
                    <th>Login Attempts</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($memberDataResult)) {
                echo "<tr>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['password']}</td>";
                echo "<td>{$row['firstname']}</td>";
                echo "<td>{$row['lastname']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['phone']}</td>";
                echo "<td>{$row['birthday']}</td>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['looked']}</td>";
                echo "<td>{$row['login_attempts']}</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<button onclick='window.location.href=\"forgetpassword.php\"' class='action-button'>Change Password</button>";
            echo "</div>";
            echo "</div>";
        }

        echo '<button onclick="showGenderForm()" class="action-button">Make your exercise schedule</button>';
        echo "</br></br>";
        echo "<button onclick='showWeeklyExercise()' class='action-button'>Show/Hide Weekly Exercise Schedule</button>";

        // Fetch and display weekly exercises with time
        $weeklyExerciseQuery = "SELECT we.day, we.exercise, we.musclename, we.sets, we.exvedio, wt.time
                                FROM weeklyexercise we
                                JOIN workouttime wt ON we.username = wt.username AND we.day = wt.day
                                WHERE we.username = '$username'
                                ORDER BY we.day";
        $weeklyExerciseResult = mysqli_query($con, $weeklyExerciseQuery);

        if (mysqli_num_rows($weeklyExerciseResult) > 0) {
            $exerciseData = [];
            while ($row = mysqli_fetch_assoc($weeklyExerciseResult)) {
                $exerciseData[$row['day']][] = $row;
            }

            // Check for empty days and remove from workouttime table
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($daysOfWeek as $day) {
                if (empty($exerciseData[$day])) {
                    $removeWorkoutTimeQuery = "DELETE FROM workouttime WHERE username='$username' AND day='$day'";
                    mysqli_query($con, $removeWorkoutTimeQuery);
                }
            }

            $goalQuery = "SELECT goal FROM users WHERE username='$username'";
            $result = mysqli_query($con, $goalQuery);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $currentGoal = $row['goal']; // Fetch the goal from the query result
            } else {
                $currentGoal = "No goal set"; // Default message if no goal is found
            }

            echo "<div id='weeklyExercisePanel' class='modal' style='display:none;'>";
            echo "<div class='modal-content'>";
            echo "<h2>Weekly Exercise Schedule</h2>";
            echo "<p><strong>Goal:</strong> $currentGoal</p>"; // Display the user's goal
            echo "<div class='scrollable-schedule'>"; // Add scrollable wrapper
            echo "<table border='1'>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Exercises</th>
                </tr>";

            foreach ($exerciseData as $day => $exercises) {
                $time = $exercises[0]['time']; // Time is the same for all exercises on the same day
                echo "<tr>";
                echo "<td>$day</td>";
                echo "<td>$time</td>";
                echo "<td>";
                foreach ($exercises as $exercise) {
                    echo "<div>";
                    echo "<strong>{$exercise['exercise']}</strong> ({$exercise['musclename']} - Sets: {$exercise['sets']}) ";
                    echo "<a href='{$exercise['exvedio']}' target='_blank' class='small-link'>Watch Video</a>";
                    echo "<form action='remove_exercise.php' method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='exerciseId' value='{$exercise['exercise']}'>";
                    echo "<input type='hidden' name='day' value='$day'>";
                    echo '<input style="background-color: red; color: white;" type="submit" value="Remove">';
                    echo "</form>";
                    // echo "<form action='update.php' method='post' style='display:inline;' onsubmit='return validateDayMatch(this);'>";
                    // echo "<input type='hidden' name='exerciseId' value='{$exercise['exercise']}'>";
                    // echo "<input type='hidden' name='day' value='$day'>";
                    // echo "<button type='submit' class='action-button'>Done</button>";
                    // echo "</form>";
                    echo "</div>";
                }
            }

            echo "</table>";
            echo "</div>"; // Close scrollable wrapper
            echo '<button style="background-color: red; color: white;" onclick="closeWeeklyExercise()" class="action-button">Close</button>';
            echo '<button class="action-button" onclick="location.href=\'exercise_schedule.php\'">Change</button>';

            echo "<form action='saveexscdulle.php' method='post'>";
            echo "<input type='hidden' name='username' value='$username'>";
            echo "<button type='submit' class='action-button'>Save this schedule</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>No exercises found in your schedule.</p>";
        }

        // Add button to show last saved schedule
        echo '<button onclick="toggleVisibility(\'savedScheduleModal\')" class="action-button">Show/Hide Last Saved Schedule</button>';

        // Begin the modal structure
        echo"<center>";
        echo "<div id='savedScheduleModal' class='modal' style='display:none;'>";
        echo "<div class='modal-content' style='max-width: 50%;'>"; // Adjust the width here
        echo "<h2>Last Saved Schedule</h2>";
        echo "<table border='1' style='width: 100%; margin: 0 auto;'>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Exercise Name</th>
                    <th>Muscle</th>
                    <th>Sets</th>
                    <th>Exercise Video</th>
                    <th>Muscle Part</th>
                </tr>";

        // Fetch and display last saved schedule
        $exHistoryQuery = "SELECT * FROM exhistory WHERE username='$username' ORDER BY day, time";
        $exHistoryResult = mysqli_query($con, $exHistoryQuery);

        if (mysqli_num_rows($exHistoryResult) > 0) {
            while ($row = mysqli_fetch_assoc($exHistoryResult)) {
                echo "<tr>";
                echo "<td>{$row['day']}</td>";
                echo "<td>{$row['time']}</td>";
                echo "<td>{$row['exname']}</td>";
                echo "<td>{$row['mucsle']}</td>";
                echo "<td>{$row['sets']}</td>";
                echo "<td><a href='{$row['exvedio']}' target='_blank' class='custom-link'>Watch Video</a></td>";
                echo "<td>{$row['musclepart']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No saved schedule found.</td></tr>";
        }

        echo "</table>";

        // Include Set and Clear Schedule buttons
        echo "<form action='set_schedule.php' method='post'>";
        echo "<button type='submit' class='action-button'>Set Schedule</button>";
        echo "</form>";

        echo "<form action='clear_schedule.php' method='post'>";
        echo "<button type='submit' class='action-button'>Clear Schedule</button>";
        echo "</form>";

        // Close button for modal
        echo "<button onclick=\"toggleVisibility('savedScheduleModal')\" class=\"action-button\" style=\"background-color: red;\">Close</button>";

        echo "</div>"; // Close modal-content div
        echo "</div>"; // Close modal div
        echo"</center>";
                echo "<div class='progress-container'>";
                echo "<h2>progress pre day</h2>";
                echo "<div class='progress-circle' id='progressCircle'>";
                echo "<span id='progressText'>0%</span>";
                echo "</div>";
                echo "</div>";








      echo '<button type="button" onclick="showCurrentDaySchedule()" class="action-button">Check Schedule</button>';

        // Fetch and display exercises for the current day
        $exerciseQuery = "SELECT we.exercise, we.musclename, we.sets, we.exvedio 
                          FROM weeklyexercise we 
                          JOIN workouttime wt ON we.username = wt.username AND we.day = wt.day
                          WHERE we.username = '$username' AND we.day = '$currentDay'
                          ORDER BY we.day";
        $exerciseResult = mysqli_query($con, $exerciseQuery);
    
        if (mysqli_num_rows($exerciseResult) > 0) {
            
            echo "<div id='currentDaySchedule' class='modal' style='display:none;'>";
            echo "<div class='modal-content'>";
            echo "<h2>Today's Exercise Schedule ($currentDay)</h2>";
            echo "<div class='scrollable-schedule'>"; // Add scrollable wrapper
            echo "<table border='1'>
                <tr>
                    <th>Exercise</th>
                    <th>Muscle Name</th>
                    <th>Sets</th>
                    <th>Video</th>
                    <th>Action</th>
                </tr>";
    
            while ($row = mysqli_fetch_assoc($exerciseResult)) {
                echo "<tr>";
                echo "<td>{$row['exercise']}</td>";
                echo "<td>{$row['musclename']}</td>";
                echo "<td>{$row['sets']}</td>";
                echo "<td><a href='{$row['exvedio']}' target='_blank' class='small-link'>Watch Video</a></td>";
                echo "<td>";
                echo "<form action='update.php' method='post' style='display:inline;' onsubmit='return validateDayMatch(this);'>";
                echo "<input type='hidden' name='exerciseId' value='{$row['exercise']}'>";
                echo "<input type='hidden' name='day' value='$currentDay'>";
                echo "<button type='submit' class='action-button done-button'>Done</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
    
            // Add a "Done for the Day" button
            echo "<tr>";
            echo "<td colspan='5' style='text-align:center;'>";
            echo "<form id='doneForTodayForm' action='donefortoday.php' method='post' onsubmit='return confirmDoneForToday()'>";
            echo "<input type='hidden' name='day' value='$currentDay'>";
            echo "<input type='hidden' name='username' value='$username'>";
            echo "<input type='hidden' name='percentage' id='percentageInput' value=''>";
            echo "<button type='submit' class='action-button'>Done for the Day</button>";
            echo "</form>";
            
            echo "</td>";
            echo "</tr>";
    
            echo "</table>";
            echo "</div>"; // Close scrollable wrapper
            echo '<button style="background-color: red; color: white;" onclick="closeCurrentDaySchedule()" class="action-button">Close</button>';
            echo "</div>";
            echo "</div>";
        }
       
        
 
    







    }

} else {
    // User not found
    echo "User not found.";
}

CloseCon($con); // Close database connection
?>

<script>
    function confirmDoneForToday() {
    var totalex = <?php echo $totalex; ?>;
    var nowex = <?php echo $nowex; ?>;

    var percentage = 0;

    if (totalex > 0) {
        percentage = Math.round((nowex / totalex) * 100);
    }

    document.getElementById('percentageInput').value = percentage;

    return confirm("Are you sure you want to mark today's exercises as done? This action cannot be undone.");
}

document.addEventListener("DOMContentLoaded", function() {
    var totalex = <?php echo $totalex; ?>;
    var nowex = <?php echo $nowex; ?>;

    var percentage = 0;

    // If totalex is greater than 0, calculate the progress percentage
    if (totalex > 0) {
        percentage = Math.round((nowex / totalex) * 100);
    }

    // Update the progress circle
    let circle = document.getElementById('progressCircle');
    let progressText = document.getElementById('progressText');
    
    // Determine the color of the progress circle based on completion
    let color = 'red';
    if (percentage >= 100) {
        color = 'green';
        <?php
         $_SESSION['percentage']=100;
        ?>
        window.location.href = 'donefortoday.php';
    } else if (percentage >= 50) {
        color = 'yellow';
        <?php
         $_SESSION['percentage']=0;
        ?>
    }
    else{
        <?php
         $_SESSION['percentage']=0;
        ?>
    }

    // Update the circle's appearance
    circle.style.background = `conic-gradient(${color} ${percentage}%, #ddd ${percentage}%)`;
    progressText.textContent = `${percentage}%`;
});


function showCurrentDaySchedule() {
    var currentDaySchedule = document.getElementById("currentDaySchedule");
    currentDaySchedule.style.display = "flex";
}

function closeCurrentDaySchedule() {
    var currentDaySchedule = document.getElementById("currentDaySchedule");
    currentDaySchedule.style.display = "none";
}
function validateDayMatch(form) {
    var scheduledDay = form.day.value;
    var currentDay = new Date().toLocaleString('en-US', { weekday: 'long' });

    if (scheduledDay === currentDay) {
        return true; // Allow form submission
    } else {
        alert("You can only mark exercises as done for the current day.");
        return false; // Prevent form submission
    }
}

function showGenderForm() {
    // Create HTML for gender selection modal
    var genderForm = `
      <div id="gender-form" class="modal">
    <div class="modal-content">
        <h2>Choose Your Gender:</h2>
        <form id="genderSelectForm">
            <label>
                <input type="radio" name="gender" value="male">
                Male
            </label><br>
            <label>
                <input type="radio" name="gender" value="female">
                Female
            </label><br>
            <button type="button" onclick="selectGender()">Next</button>
            <button type="button" onclick="closeGenderForm()">Cancel</button>
        </form>
    </div>
 </div>`;
    
    // Append gender form to the body
    document.body.insertAdjacentHTML("beforeend", genderForm);
}

function closeGenderForm() {
    var genderForm = document.getElementById("gender-form");
    if (genderForm) {
        genderForm.remove();
    }
}

function selectGender() {
    var form = document.getElementById("genderSelectForm");
    var selectedGender = form.querySelector('input[name="gender"]:checked');
    
    if (selectedGender) {
        var gender = selectedGender.value;

        // Create HTML for exercise schedule modal based on selected gender
        var codeSnippet = 
            "<div id='exercise-schedule' class='modal'>" +
                "<div class='modal-content'>" +
                    "<h2>Choose Your Goal:</h2>" +
                    "<p>You are " + gender + "</p>" +
                    "<div style='margin-top: 20px;'>" +
                        "<h3>Not sure where to start?</h3>" +
                        "<button type='button' class='beginner-button' onclick='redirectToBeginner(\"" + gender + "\")'>Beginner</button>" +
                    "</div>" +
                    "<form action='exercise_schedule.php' method='post'>" +
                        "<input type='hidden' name='gender' value='" + gender + "'>" +
                        "<h3>" + (gender === 'male' ? 'Male Options:' : 'Female Options:') + "</h3>" +
                        "<input type='submit' name='goal' value='Lose Weight'>" +
                        "<input type='submit' name='goal' value='Gain Muscles'>" +
                        "<input type='hidden' name='beginner' value='no'>" +
                    "</form>" +
                    "<button type='button' onclick='closeExerciseSchedule()'>Cancel</button>" +
                "</div>" +
            "</div>";
        
        // Append exercise schedule modal to the body
        document.body.insertAdjacentHTML("beforeend", codeSnippet);
        
        // Remove the gender selection modal
        document.getElementById("gender-form").remove();
    } else {
        alert("Please select your gender.");
    }
}

function redirectToBeginner(gender) {
    // Handle the redirection to the beginner page or save beginner status
    var beginnerForm = 
        "<form id='beginnerForm' action='pageforbeginer.php' method='post'>" +
            "<input type='hidden' name='beginner' value='yes'>" +
            "<input type='hidden' name='gender' value='" + gender + "'>" +
        "</form>";
    
    // Append the form to the body and submit it
    document.body.insertAdjacentHTML("beforeend", beginnerForm);
    document.getElementById("beginnerForm").submit();
}

function closeExerciseSchedule() {
    var exerciseSchedule = document.getElementById("exercise-schedule");
    if (exerciseSchedule) {
        exerciseSchedule.remove();
    }
}

function showWeeklyExercise() {
    var weeklyExercisePanel = document.getElementById("weeklyExercisePanel");
    weeklyExercisePanel.style.display = "flex";
}

function closeWeeklyExercise() {
    var weeklyExercisePanel = document.getElementById("weeklyExercisePanel");
    weeklyExercisePanel.style.display = "none";
}

function toggleVisibility(elementId) {
    var element = document.getElementById(elementId);
    if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function setSchedule() {
    // Implement the logic to set the saved schedule
    alert("Schedule has been set!");
}

</script>

<style>
/* Modal container */
/* Modal container */
.modal {
    position: fixed;
    margin-top:50px;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    z-index: 9999;
}

/* Modal content */
.modal-content {
    background-color: #fff; /* White background */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Soft box shadow */
    max-width: 50%; /* Limit width for better readability, adjustable */
    text-align: center;
    overflow-y: auto; /* Allow scrolling if content is too long */
    max-height: 80%; /* Limit height */
}

/* Center table within the modal */
.modal-content table {
    width: 100%; /* Ensure table takes full width of the modal */
    margin: 0 auto; /* Center the table horizontally */
}

/* Action buttons */
.action-button {
    margin-top: 20px;
    background-color: green; /* Customize this color */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.action-button:hover {
    background-color: darkgreen; /* Darker shade on hover */
}

/* Custom styles for the video link */
.custom-link {
    font-size: 12px;
    padding: 5px 10px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.3s, color 0.3s;
}

.custom-link:hover {
    background-color: purple;
    color: #fff;
}





.member-panel button,
.action-button,
.bw-button,
.toggle-button,
.show-order-button {
    background-color: black; /* Set the background to black */
    color: antiquewhite; /* Set the text color to antiquewhite */
    padding: 8px 16px; /* Padding for better size */
    border: none; /* Remove border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
    margin-top: 15px;
    font-size: 15px;
}

.member-panel button:hover,
.action-button:hover,
.bw-button:hover,
.toggle-button:hover,
.show-order-button:hover {
    background-color: #333; /* Slightly lighter black on hover */
    color: white; /* Optional: Change text color to white on hover */
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    height: 100vh; /* Full height to allow middle alignment */
    justify-content: space-between; /* Space between buttons and progress circle */
    align-items: center; /* Align content vertically in the middle */
    position: relative;
}

.button-container {
    display: flex;
    flex-direction: column; /* Stack buttons vertically */
    align-items: flex-start; /* Align buttons to the left */
    margin-left: 20px; /* Add some margin from the left edge */
}

.progress-container {
    display: flex;
    flex-direction: column; /* Stack the heading and circle vertically */
    justify-content: center;
    align-items: center;
    position: absolute;
    right: 400px; /* Positioned 400px from the right edge */
    top: 300px;
}

.progress-container h2 {
    margin-bottom: 10px; /* Adjust spacing between the heading and the circle */
    color: antiquewhite; 
}

.progress-circle {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: conic-gradient(red 0%, grey 0%);
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    transition: background 0.4s ease;
}

.progress-circle::before {
    content: '';
    position: absolute;
    width: 130px;
    height: 130px;
    background-color: #fff;
    border-radius: 50%;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
}

.progress-circle span {
    position: relative;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    z-index: 10;
}

.progress-circle span:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 130px;
    height: 130px;
    background-color: #fff;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    z-index: -1;
}

/* Additional Styling */
.custom-link {
    font-size: 12px;
    padding: 5px 10px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.3s, color 0.3s;
}

.custom-link:hover {
    background-color: purple;
    color: #000;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .progress-circle {
        width: 100px;
        height: 100px;
    }

    .progress-circle::before {
        width: 80px;
        height: 80px;
    }

    .progress-circle span {
        font-size: 18px;
    }
}

.beginner-button {
    background-color: green; /* Coral color for a warm, inviting look */
    color: white; /* White text color */
    padding: 8px 18px; /* Padding for better button size */
    border: none; /* Remove default border */
    border-radius: 8px; /* Rounded corners */
    font-size: 12px; /* Slightly larger font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

.beginner-button:hover {
    background-color: black; /* Slightly darker shade on hover */
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('userdataback.webp') no-repeat center center fixed; 
    background-size: cover;
    filter: blur(1px); /* Adjust the blur radius as needed */
    z-index: -1; /* Ensures it stays behind the content */
}

.container {
    width: 80%;
    margin: auto;
    overflow: hidden;
}

header {
    background: #35424a;
    color: #ffffff;
    padding-top: 30px;
    min-height: 70px;
    border-bottom: #e8491d 3px solid;
}

header a {
    color: #ffffff;
    text-decoration: none;
    text-transform: uppercase;
    font-size: 16px;
}

header ul {
    padding: 0;
    list-style: none;
}

header li {
    float: left;
    display: inline;
    padding: 0 20px 0 20px;
}

header #branding {
    float: left;
}

header #branding h1 {
    margin: 0;
}

header nav {
    float: right;
    margin-top: 10px;
}

/* Manager Panel */
.manager-panel {
    margin-top: 20px;
    padding: 10px;
    border-radius: 5px;
}

.manager-panel h2 {
    font-size: 24px;
    margin-bottom: 10px;
    color: antiquewhite;
}

.manager-panel h3 {
    font-size: 20px;
    margin-bottom: 5px;
}

.manager-panel form {
    margin-bottom: 10px;
}

.bw-button {
    background-color: #000;
    color: antiquewhite;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
}

.bw-button:hover {
    background-color: #555;
}

/* Manager Information */
#managerData {
    display: none;
}

#managerData table {
    width: 100%;
    border-collapse: collapse;
}

#managerData th, #managerData td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    background-color: white;
}

#managerData th {
    background-color: #f2f2f2;
}

#managerData button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#managerData button:hover {
    background-color: #45a049;
}

/* Member Panel */
.member-panel {
    margin-top: 20px;
}

.member-panel h2 {
    color: antiquewhite;
}

.member-panel button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.member-panel button:hover {
    background-color: #45a049;
}

/* Show Order Button */
.show-order-button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.show-order-button:hover {
    background-color: #45a049;
}

.action-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 15px; 
}

.action-button:hover {
    background-color: #45a049;
}

/* Toggle Button */
.toggle-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 15px; 
}

.toggle-button:hover {
    background-color: #45a049;
}

/* Modal container */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Modal content */
.modal-content {
    background-color: #fff; /* White background */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Soft box shadow */
    max-width: 80%; /* Limit width for better readability */
    text-align: center;
}

.modal h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.modal p {
    font-size: 16px;
    margin-bottom: 20px;
}

.modal form {
    margin-top: 10px;
}

.modal form input[type="radio"] {
    margin-right: 10px;
}

.modal form button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.modal form button:hover {
    background-color: #45a049;
}

.modal h3 {
    font-size: 18px;
    margin-bottom: 10px;
}

/* Weekly Exercise Panel */
.weekly-exercise-panel {
    margin-top: 20px;
    display: none;
}

.weekly-exercise-panel h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

#weeklyExercise {
    width: 100%;
    border-collapse: collapse;
}

#weeklyExercise th, #weeklyExercise td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

#weeklyExercise th {
    background-color: #f2f2f2;
}

.toggle-button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.toggle-button:hover {
    background-color: #45a049;
}

/* Smaller link for video */
.small-link {
    font-size: 12px; /* Adjust the size as needed */
    text-decoration: none; /* Remove the default underline */
    color: #0000EE; /* Default link color */
    background-color: #f8f8f8; /* Light background color */
    padding: 4px 8px; /* Add padding for better spacing */
    border-radius: 4px; /* Rounded corners */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover effects */
}

.small-link:hover {
    color: #ffffff; /* Change text color on hover */
    background-color: #551A8B; /* Change background color on hover */
}

/* Schedule Table */
.schedule-table {
    background-color: #fff; /* White background */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Soft box shadow */
    max-width: 80%; /* Limit width for better readability */
    margin: 20px auto; /* Center the table */
    text-align: left; /* Left-align text for better readability */
}

.schedule-table h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.schedule-table table {
    width: 100%;
    border-collapse: collapse;
}

.schedule-table th, .schedule-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.schedule-table th {
    background-color: #f2f2f2;
}

input[type="submit"] {
    background-color: red;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 15px;
}

input[type="submit"]:hover {
    background-color: black;
}

/* Scrollable Schedule */
.scrollable-schedule {
    max-height: 400px; /* Adjust the height as needed */
    overflow-y: scroll;
    overflow-x: hidden;
    margin-top: 20px; /* Add some margin for spacing */
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
}

.scrollable-schedule table {
    width: 100%;
    border-collapse: collapse;
}

.scrollable-schedule th,
.scrollable-schedule td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    background-color: white;
}

.scrollable-schedule th {
    background-color: #f2f2f2;
}
</style>
