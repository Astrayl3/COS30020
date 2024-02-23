<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Advanced Search Job Vacancy</title>
</head>
<body class="search-job">
    <h1>Advanced Search Job Vacancy</h1>
    <header class="search-job">
    <form action="searchjobprocess.php" method="GET">
        <label for="job_title">Job Title:</label><br>
        <input type="text" id="job_title" name="job_title" class="job-title"><br>

        <label for="position" class="job-title">Position:</label><br>
        <select id="position" name="position">
            <option value="">Any</option>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
        </select><br>   

        <label for="contract">Contract:</label><br>
        <select id="contract" name="contract" class="job-title">
            <option value="">Any</option>
            <option value="On-going">On-going</option>
            <option value="Fixed term">Fixed term</option>
        </select><br>

        <label for="application_type">Application Type:</label><br>
        <input type="checkbox" id="post" name="application_type[]" value="Post">
        <label for="post">Post</label><br>
        <input type="checkbox" id="email" name="application_type[]" value="Email">
        <label for="email">Email</label><br>

        <label for="location">Location:</label><br>
        <select id="location" name="location" class="job-title">
            <option value="">Any</option>
            <option value="ACT">ACT</option>
            <option value="NSW">NSW</option>
            <option value="NT">NT</option>
            <option value="QLD">QLD</option>
            <option value="SA">SA</option>
            <option value="TAS">TAS</option>
            <option value="VIC">VIC</option>
            <option value="WA">WA</option>
        </select><br><br>

        <input type="submit" value="Search" class="search-job">
    </form>
    </header><br>
    <a href="index.php"><button href="index.php" class="return-button">Return to Home</button></a>
</body>
</html>
