<!-- <?php
include 'db_connection.php';
session_start(); 
$con = OpenCon();
$q = "SELECT * FROM allexercise WHERE exname='bench press'";
$userResult = mysqli_query($con, $q); // Assuming $con is your database connection

if (strtolower($muscle) == 'chest') { ?>
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
<?php } ?> -->
