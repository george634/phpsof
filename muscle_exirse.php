<?php
session_start();
include 'db_connection.php';
$con = OpenCon();

// Fetch exercises based on the selected muscle part
$day = isset($_GET['day']) ? $_GET['day'] : 'Unknown Day';
$muscle = isset($_GET['muscle']) ? $_GET['muscle'] : 'Unknown Muscle';
$goal = isset($_SESSION['goal']) ? $_SESSION['goal'] : 'Unknown Goal';
$lowerchest = 'lower chest';
$upperpart = 'upper part';
$midelepart = 'middele chest';
$frontsholder = 'front sholder';
$middleshoulder = 'middle sholder';
$rearsholder = 'rear sholder';
$longhead = 'long head';
$shorthead = 'short head';
$tricepslonghead='long head';
$tricepsmedialhead='medial head';
$tricepslaterialhead='laterial head';
$fleg='front legs';
$hamstrings='hamstrings';
$sideabs='side abs';
$lowrabs='lower abs';
$upperabs='upper abs';


$sql = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'Chest' AND musclepart = '$lowerchest'";
$userResult = mysqli_query($con, $sql);

$sql1 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'Chest' AND musclepart = '$upperpart'";
$userResult1 = mysqli_query($con, $sql1);

$sql2 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'Chest' AND musclepart = '$midelepart'";
$userResult2 = mysqli_query($con, $sql2);

$sql3 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'sholder' AND musclepart = '$frontsholder'";
$fshoulder = mysqli_query($con, $sql3);

$sql4 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'sholder' AND musclepart = '$middleshoulder'";
$mshoulder = mysqli_query($con, $sql4);

$sql5 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'sholder' AND musclepart = '$rearsholder'";
$rshoulder = mysqli_query($con, $sql5);

$sql6 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'biceps' AND musclepart = '$longhead'";
$lhead = mysqli_query($con, $sql6);

$sql7 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'biceps' AND musclepart = '$shorthead'";
$shead = mysqli_query($con, $sql7);

$sql8 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'triceps' AND musclepart = '$tricepslonghead'";
$tlonghead = mysqli_query($con, $sql8);

$sql9 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'triceps' AND musclepart = '$tricepsmedialhead'";
$tmedialghead = mysqli_query($con, $sql9);

$sql10 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'triceps' AND musclepart = '$tricepslaterialhead'";
$tlaterialhead = mysqli_query($con, $sql10);

$sql11 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'legs' AND musclepart = '$fleg'";
$flegs = mysqli_query($con, $sql11);

$sql12 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'legs' AND musclepart = '$hamstrings'";
$hamst = mysqli_query($con, $sql12);

$sql13 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'abs' AND musclepart = '$sideabs'";
$sabs = mysqli_query($con, $sql13);

$sql14 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'abs' AND musclepart = '$lowrabs'";
$labs = mysqli_query($con, $sql14);

$sql15 = "SELECT exname, exvedio FROM allexercise WHERE muscle = 'abs' AND musclepart = '$upperabs'";
$uabs = mysqli_query($con, $sql15);

// Query to get the number of users with the same exercise on the same day in the morning and evening
$countSql = "SELECT we.exercise, we.day, 
                    COUNT(CASE WHEN wt.time = 'morning' THEN 1 END) AS morning_count, 
                    COUNT(CASE WHEN wt.time = 'evening' THEN 1 END) AS evening_count 
             FROM weeklyexercise we
             LEFT JOIN workouttime wt ON we.username = wt.username AND we.day = wt.day
             GROUP BY we.exercise, we.day";
$countResult = mysqli_query($con, $countSql);
$exerciseCounts = [];
while ($row = mysqli_fetch_assoc($countResult)) {
    $exerciseCounts[$row['exercise']][$row['day']]['morning'] = $row['morning_count'];
    $exerciseCounts[$row['exercise']][$row['day']]['evening'] = $row['evening_count'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($muscle); ?> Workouts</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        h1,
        h2 {
            color: #35424a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #35424a;
            color: white;
        }

        video {
            display: block;
            margin: auto;
        }

        a.button {
            display: inline-block;
            color: white;
            background: #e8491d;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        a.button:hover {
            background: #35424a;
        }

        .day-display {
            background-color: #35424a;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
            border-radius: 5px;
        }

        .day-display h2 {
            color: white;
        }

        .input-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            position: relative;
        }

        .input-container input {
            width: 50px;
            text-align: center;
        }

        .input-container button {
            background-color: #e8491d;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .input-container button:hover {
            background-color: #35424a;
        }

        
        .recommendation {
    color: orange;
    font-weight: bold;
    margin-right: 10px;
    display: inline-flex;
    align-items: center;
}

.fire-icon {
    width: 20px; /* Adjust size as needed */
    height: 20px;
    margin-right: 5px;
}
    </style>
    <script>
        function incrementValue(inputId, goal) {
            var input = document.getElementById(inputId);
            input.value = parseInt(input.value) + 1;
            showRecommendation(inputId, input.value, goal);
        }

        function decrementValue(inputId, goal) {
            var input = document.getElementById(inputId);
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                showRecommendation(inputId, input.value, goal);
            }
        }

        function showRecommendation(inputId, value, goal) {
            var recommendation = document.getElementById('rec-' + inputId);
            if (value > 1 && value < 4) {
                if (goal == 'Gain Muscles') {
                    recommendation.innerText = "Recommended for gaining muscles";
                } else {
                    recommendation.innerText = "";
                }
            } else {
                recommendation.innerText = "";
            }
        }
    </script>
</head>

<body>
<header>
    <div class="container">
        <div id="branding">
            <h1 style="color: white;"><?php echo htmlspecialchars($muscle); ?> Workouts</h1>
        </div>
        <nav>
            <ul>
                <?php
               
                
                $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php'; // Default page if referrer is not set

                echo '<li><a href="' . $previousPage . '">Back to Workout Plan</a></li>';
                ?>
            </ul>
        </nav>
    </div>
</header>



    <div class="container">
        <div class="day-display">
            <h2><?php echo htmlspecialchars($day); ?></h2>
        </div>
    </div>

    <?php if (strtolower($muscle) == 'chest') { ?>
        <div class="container">
            <?php if (mysqli_num_rows($userResult) > 0) { ?>
                <h2><?php echo htmlspecialchars($lowerchest); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($userResult)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($lowerchest); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($userResult1) > 0) { ?>
                <h2><?php echo htmlspecialchars($upperpart); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($userResult1)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($upperpart); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
    <?php if (mysqli_num_rows($userResult2) > 0) { ?>
        <h2><?php echo htmlspecialchars($midelepart); ?></h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Video</th>
                <th>Sets</th>
                <th>Users with same exercise (Morning)</th>
                <th>Users with same exercise (Evening)</th>
                <th>Action</th>
            </tr>
            <?php 
            $recommendedExercises = ['bench press', 'Front Legs']; // Define your recommended exercises
            while ($row = mysqli_fetch_assoc($userResult2)) {
                $inputId = 'sets-' . htmlspecialchars($row['exname']);
                $exerciseName = htmlspecialchars($row['exname']);
                $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                // Determine if the exercise is recommended for gaining muscle
                $recommendationHtml = '';
                if (in_array($exerciseName, $recommendedExercises) && $goal == 'Gain Muscles') {
                    $recommendationHtml = "<div class='recommendation'><img src='firelogo.jpeg' alt='fire icon' class='fire-icon'>Recommended for gaining muscles</div>";
                }

                // Determine background color based on user count
                $morningBgColor = '';
                $eveningBgColor = '';
                if ($morningCount > 4) {
                    $morningBgColor = 'background-color: red;';
                } elseif ($morningCount > 2) {
                    $morningBgColor = 'background-color: yellow;';
                } elseif ($morningCount >= 1) {
                    $morningBgColor = 'background-color: green;';
                }
                if ($eveningCount > 4) {
                    $eveningBgColor = 'background-color: red;';
                } elseif ($eveningCount > 2) {
                    $eveningBgColor = 'background-color: yellow;';
                } elseif ($eveningCount >= 1) {
                    $eveningBgColor = 'background-color: green;';
                }
            ?>
            <tr>
                <td>
                    <?php echo $recommendationHtml; ?> <!-- Display the recommendation -->
                    <div><?php echo $exerciseName; ?></div>
                </td>
                <td><video width="320" height="240" controls>
                        <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </td>
                <td>
                    <div class="input-container">
                        <div>
                            <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                            <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                            <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                        </div>
                    </div>
                </td>
                <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                <td>
                    <form method="POST" action="add_exercise.php">
                        <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                        <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                        <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($midelepart); ?>">
                        <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                        <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                        <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                        <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No exercises found for the specified muscle part.</p>
    <?php } ?>
</div>
    <?php } ?>

    <?php if (strtolower($muscle) == 'sholder') { ?>
        <div class="container">
            <?php if (mysqli_num_rows($fshoulder) > 0) { ?>
                <h2><?php echo htmlspecialchars($frontsholder); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($fshoulder)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($frontsholder); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($mshoulder) > 0) { ?>
                <h2><?php echo htmlspecialchars($middleshoulder); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($mshoulder)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($middleshoulder); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($rshoulder) > 0) { ?>
                <h2><?php echo htmlspecialchars($rearsholder); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($rshoulder)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($rearshoulder); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
    <?php } ?>



    <?php if (strtolower($muscle) == 'biceps') { ?>
        <div class="container">
            <?php if (mysqli_num_rows($lhead) > 0) { ?>
                <h2><?php echo htmlspecialchars($longhead); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($lhead)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($longhead); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($shead) > 0) { ?>
                <h2><?php echo htmlspecialchars($shorthead); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($shead)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($shorthead); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        
    <?php } ?>


    <?php if (strtolower($muscle) == 'triceps') { ?>
        <div class="container">
            <?php if (mysqli_num_rows($tlonghead) > 0) { ?>
                <h2><?php echo htmlspecialchars($tricepslonghead); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($tlonghead)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($tricepslonghead); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($tmedialghead) > 0) { ?>
                <h2><?php echo htmlspecialchars($tricepsmedialhead); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($tmedialghead)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($tricepsmedialhead); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($tlaterialhead) > 0) { ?>
                <h2><?php echo htmlspecialchars($tricepslaterialhead); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($tlaterialhead)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($tricepslaterialhead); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
    <?php } ?>




    <?php if (strtolower($muscle) == 'legs') { ?>
        <div class="container">
    <?php if (mysqli_num_rows($flegs) > 0) { ?>
        <h2><?php echo htmlspecialchars($fleg); ?></h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Video</th>
                <th>Sets</th>
                <th>Users with same exercise (Morning)</th>
                <th>Users with same exercise (Evening)</th>
                <th>Action</th>
            </tr>
            <?php 
            $recommendedExercises = ['barbell squat']; // Define your recommended exercises
            while ($row = mysqli_fetch_assoc($flegs)) {
                $inputId = 'sets-' . htmlspecialchars($row['exname']);
                $exerciseName = htmlspecialchars($row['exname']);
                $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                // Determine if the exercise is recommended for gaining muscle
                $recommendationHtml = '';
                if (in_array($exerciseName, $recommendedExercises) && $goal == 'Gain Muscles') {
                    $recommendationHtml = "<div class='recommendation'><img src='firelogo.jpeg' alt='fire icon' class='fire-icon'>Recommended for gaining muscles</div>";
                }

                // Determine background color based on user count
                $morningBgColor = '';
                $eveningBgColor = '';
                if ($morningCount > 4) {
                    $morningBgColor = 'background-color: red;';
                } elseif ($morningCount > 2) {
                    $morningBgColor = 'background-color: yellow;';
                } elseif ($morningCount >= 1) {
                    $morningBgColor = 'background-color: green;';
                }
                if ($eveningCount > 4) {
                    $eveningBgColor = 'background-color: red;';
                } elseif ($eveningCount > 2) {
                    $eveningBgColor = 'background-color: yellow;';
                } elseif ($eveningCount >= 1) {
                    $eveningBgColor = 'background-color: green;';
                }
            ?>
            <tr>
                <td>
                    <?php echo $recommendationHtml; ?> <!-- Display the recommendation -->
                    <div><?php echo $exerciseName; ?></div>
                </td>
                <td><video width="320" height="240" controls>
                        <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </td>
                <td>
                    <div class="input-container">
                        <div>
                            <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                            <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                            <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                        </div>
                        <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                    </div>
                </td>
                <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                <td>
                    <form method="POST" action="add_exercise.php">
                        <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                        <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                        <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($fleg); ?>">
                        <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                        <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                        <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                        <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No exercises found for the specified muscle part.</p>
    <?php } ?>
</div>
        <div class="container">
            <?php if (mysqli_num_rows($hamst) > 0) { ?>
                <h2><?php echo htmlspecialchars($hamstrings); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($hamst)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($hamstrings); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        
    <?php } ?>



    <?php if (strtolower($muscle) == 'abs') { ?>
        <div class="container">
            <?php if (mysqli_num_rows($sabs) > 0) { ?>
                <h2><?php echo htmlspecialchars($sideabs); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($sabs)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($sideabs); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($labs) > 0) { ?>
                <h2><?php echo htmlspecialchars($lowrabs); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($labs)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($lowrabs); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (mysqli_num_rows($uabs) > 0) { ?>
                <h2><?php echo htmlspecialchars($upperabs); ?></h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Video</th>
                        <th>Sets</th>
                        <th>Users with same exercise (Morning)</th>
                        <th>Users with same exercise (Evening)</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($uabs)) {
                        $inputId = 'sets-' . htmlspecialchars($row['exname']);
                        $exerciseName = htmlspecialchars($row['exname']);
                        $morningCount = isset($exerciseCounts[$exerciseName][$day]['morning']) ? $exerciseCounts[$exerciseName][$day]['morning'] : 0;
                        $eveningCount = isset($exerciseCounts[$exerciseName][$day]['evening']) ? $exerciseCounts[$exerciseName][$day]['evening'] : 0;

                        // Determine background color based on user count
                        $morningBgColor = '';
                        $eveningBgColor = '';
                        if ($morningCount > 4) {
                            $morningBgColor = 'background-color: red;';
                        } elseif ($morningCount > 2) {
                            $morningBgColor = 'background-color: yellow;';
                        } elseif ($morningCount >= 1) {
                            $morningBgColor = 'background-color: green;';
                        }
                        if ($eveningCount > 4) {
                            $eveningBgColor = 'background-color: red;';
                        } elseif ($eveningCount > 2) {
                            $eveningBgColor = 'background-color: yellow;';
                        } elseif ($eveningCount >= 1) {
                            $eveningBgColor = 'background-color: green;';
                        }
                        ?>
                        <tr>
                            <td><?php echo $exerciseName; ?></td>
                            <td><video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($row['exvedio']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video></td>
                            <td>
                                <div class="input-container">
                                    <div>
                                        <button type="button" onclick="decrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">-</button>
                                        <input type="number" name="sets" id="<?php echo $inputId; ?>" value="1" min="1">
                                        <button type="button" onclick="incrementValue('<?php echo $inputId; ?>', '<?php echo htmlspecialchars($goal); ?>')">+</button>
                                    </div>
                                    <div id="rec-<?php echo $inputId; ?>" class="recommendation"></div>
                                </div>
                            </td>
                            <td style="<?php echo $morningBgColor; ?>"><?php echo $morningCount; ?></td>
                            <td style="<?php echo $eveningBgColor; ?>"><?php echo $eveningCount; ?></td>
                            <td>
                                <form method="POST" action="add_exercise.php">
                                    <input type="hidden" name="exercise" value="<?php echo $exerciseName; ?>">
                                    <input type="hidden" name="muscle" value="<?php echo htmlspecialchars($muscle); ?>">
                                    <input type="hidden" name="musclepart" value="<?php echo htmlspecialchars($upperabs); ?>">
                                    <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                                    <input type="hidden" name="exvedio" value="<?php echo htmlspecialchars($row['exvedio']); ?>">
                                    <input type="hidden" name="sets" id="sets-hidden-<?php echo $exerciseName; ?>">
                                    <input type="submit" class="button" value="ADD" onclick="document.getElementById('sets-hidden-<?php echo $exerciseName; ?>').value = document.getElementById('<?php echo $inputId; ?>').value;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No exercises found for the specified muscle part.</p>
            <?php } ?>
        </div>
    <?php } ?>
</body>

</html>
