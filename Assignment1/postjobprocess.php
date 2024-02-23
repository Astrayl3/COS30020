<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Job Process</title>
</head>
<body class="post-job">
    <?php
    // Function to validate date format
    function validateDate($date, $format = 'd/m/y') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    // Function to check uniqueness of Position ID
    function isUniquePositionID($positionID, $filePath) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $fields = explode("\t", $line);
            if ($fields[0] == $positionID) {
                return false;
            }
        }
        return true;
    }

    // Check if directory exists, if not, create it
    $directory = 'xampp\htdocs\Assignment1\jobposts';
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if all fields are provided
        $positionID = $_POST["position_id"];
        $title = $_POST["title"];
        $description = $_POST["description"];
        $closingDate = $_POST["closing_date"];
        $position = $_POST["position"];
        $contract = $_POST["contract"];
        $acceptApplication = isset($_POST["accept_application"]) ? implode(",", $_POST["accept_application"]) : "";
        $location = $_POST["location"];

        if ($positionID && $title && $description && $closingDate && $position && $contract && $acceptApplication && $location) {
            // Validate date format
            if (validateDate($closingDate, 'd/m/y')) {
                // Check uniqueness of Position ID
                $filePath = "$directory/jobs.txt";
                if (isUniquePositionID($positionID, $filePath)) {
                    // Save job vacancy to the text file
                    $jobData = "$positionID\t$title\t$description\t$closingDate\t$position - $contract\t$acceptApplication\t$location";
                    file_put_contents($filePath, $jobData . PHP_EOL, FILE_APPEND | LOCK_EX);

                    // Confirmation message
                    echo "Job vacancy saved successfully.<br>";
                    echo '<a href="index.php">Return to Home Page</a>';
                } else {
                    echo "Position ID is not unique. Please choose a different one.<br>";
                    echo '<a href="postjobform.php">Return to Post Job Page</a>';
                }
            } else {
                echo "Invalid date format. Please use dd/mm/yy format.<br>";
                echo '<a href="postjobform.php">Return to Post Job Page</a>';
            }
        } else {
            echo "All fields are mandatory. Please fill out all required fields.<br>";
            echo '<a href="postjobform.php">Return to Post Job Page</a>';
        }
    } else {
        echo "Invalid request method.";
    }
    ?>
</body>
</html>