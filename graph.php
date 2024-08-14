<?php
session_start();
include 'db_connection.php';
include 'navbar.footer.php';

$con = OpenCon(); // Open database connection

$currentYear = date('Y');
$currentMonth = date('m');
$today = date('Y-m-d');

// Get the selected year and month or default to the current year and month
$selectedYear = isset($_POST['year']) ? (int)$_POST['year'] : $currentYear;
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : $currentMonth; // Note: $selectedMonth can now be 'all'

if ($selectedMonth == 'all') {
    $query = "SELECT date, points FROM fainalstats WHERE username = ? AND YEAR(date) = ? AND date <= ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sis", $_SESSION['username'], $selectedYear, $today);
} else {
    $selectedMonth = (int)$selectedMonth;
    if ($selectedYear == $currentYear && $selectedMonth == $currentMonth) {
        $query = "SELECT date, points FROM fainalstats WHERE username = ? AND YEAR(date) = ? AND MONTH(date) = ? AND date <= ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("siis", $_SESSION['username'], $selectedYear, $selectedMonth, $today);
    } else {
        $query = "SELECT date, points FROM fainalstats WHERE username = ? AND YEAR(date) = ? AND MONTH(date) = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sii", $_SESSION['username'], $selectedYear, $selectedMonth);
    }
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
$totalPoints = 0;
$totalDays = 0;

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    $totalPoints += $row['points'];
    $totalDays++;
}

$averagePoints = $totalDays > 0 ? $totalPoints / $totalDays : 0;

$stmt->close();
CloseCon($con); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Stats Graph</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 20px;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        select, button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #555;
        }

        p {
            text-align: center;
            font-size: 18px;
            margin: 5px 0;
        }

        #statsChart {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        canvas {
            width: 1000px !important;
            height: 600px !important;
        }

        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: sticky;
            bottom: 0;
            width: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>Your Stats for 
            <?php 
            if ($selectedMonth == 'all') {
                echo "All Months $selectedYear";
            } else {
                echo date('F Y', strtotime("$selectedYear-$selectedMonth-01")); 
            }
            ?>
            </h1>

            <form method="post">
                <label for="month">Month:</label>
                <select name="month" id="month">
                    <option value="all" <?php if ($selectedMonth == 'all') echo 'selected'; ?>>All Months</option>
                    <?php
                    for ($i = 1; $i <= $currentMonth; $i++) {
                        $selected = ($i == $selectedMonth) ? 'selected' : '';
                        echo "<option value=\"$i\" $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . "</option>";
                    }
                    ?>
                </select>

                <label for="year">Year:</label>
                <select name="year" id="year">
                    <?php for ($i = $currentYear - 5; $i <= $currentYear; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if ($i == $selectedYear) echo 'selected'; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <button type="submit"><i class="fas fa-chart-bar"></i> View Stats</button>
            </form>

            <h2>Total Stats</h2>
            <p>Total Points: <?php echo $totalPoints; ?></p>
            <p>Total Days: <?php echo $totalDays; ?></p>
            <p>Average Points per Day: <?php echo number_format($averagePoints, 2); ?></p>

            <div id="statsChart">
                <canvas id="chartCanvas"></canvas>
            </div>
        </div>
        
        <footer>
            &copy; 2024 My Gym. All rights reserved.
        </footer>
    </div>

    <script>
        const ctx = document.getElementById('chartCanvas').getContext('2d');
        const chartData = {
            labels: <?php echo json_encode(array_map(function($date) {
                return date('Y-m-d', strtotime($date));
            }, array_column($data, 'date'))); ?>,
            datasets: [{
                label: 'Points',
                data: <?php echo json_encode(array_column($data, 'points')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
</body>
</html>
