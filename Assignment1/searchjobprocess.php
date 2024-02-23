<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Job Vacancy Results</title>
</head>
<body class="search-job">
    <h1>Job Vacancy Results</h1>

    <?php
    // Validate the existence of the "jobs.txt" file
    $filePath = "xampp\htdocs\Assignment1\jobposts\jobs.txt";
    if (file_exists($filePath)) {
        // Read data from the job vacancy text file
        $jobData = file($filePath, FILE_IGNORE_NEW_LINES);

        // Define today's dates
        $today = date('d/m/y');

        // Filter and sort job vacancies by closing date
        $filteredJobs = array_filter($jobData, function ($line) use ($today) {
            $fields = explode("\t", $line);
            $Date = $fields[3];
            $closingTimestamp = strtotime(str_replace('/', '-', $Date));
            $todayTimestamp = strtotime($today);
            return $closingTimestamp >= $todayTimestamp;
        });

        // Sort job vacancies by closing date
        usort($filteredJobs, function ($a, $b) {
            $fieldsA = explode("\t", $a);
            $fieldsB = explode("\t", $b);
            $closingTimestampA = strtotime(str_replace('/', '-', $fieldsA[3]));
            $closingTimestampB = strtotime(str_replace('/', '-', $fieldsB[3]));
            // Compare closing dates
            return $closingTimestampA - $closingTimestampB;
        });

        // Display job vacancies
        foreach ($filteredJobs as $line) {
            $fields = explode("\t", $line);
            echo "<p>Job Title: $fields[1]</p>";
            echo "<p>Description: $fields[2]</p>";
            echo "<p>Closing Date: $fields[3]</p>";
            echo "<p>Position: $fields[4]</p>";
            echo "<p>Accept Application by: $fields[5]</p>";
            echo "<p>Location: $fields[6]</p><br>";
        }
    } else {
        // Error message if "jobs.txt" file does not exist
        echo "<p>Error: Job vacancy data file not found.</p>";
    }
    ?>

    <a href="index.php"><button href="index.php" class="return-button">Return to Home</button></a>
</body>
</html>
