<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Job Form</title>
</head>
<body class="post-job">
    <h1>Job Vacancy Posting System</h1>
    <header class="post-job">
    <form action="postjobprocess.php" method="POST">
    <label for="position_id">Position ID:</label><br>
    <input type="text" id="position_id" name="position_id" pattern="PID[0-9]{4}" maxlength="7" required><br>
    
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" maxlength="20" required><br>
    
    <label for="description">Description:</label><br>
    <textarea id="description" name="description" maxlength="250" required></textarea><br>
    
    <label for="closing_date">Closing Date:</label><br>
    <input type="text" id="closing_date" name="closing_date" value="<?php echo date('d/m/y'); ?>" required><br>
    
    <label for="position">Position:</label><br>
    <input type="radio" id="full_time" name="position" value="Full Time" required>
    <label for="full_time">Full Time</label>
    <input type="radio" id="part_time" name="position" value="Part Time" required>
    <label for="part_time">Part Time</label><br>
    
    <label for="contract">Contract:</label><br>
    <input type="radio" id="ongoing" name="contract" value="On-going" required>
    <label for="ongoing">On-going</label>
    <input type="radio" id="fixed_term" name="contract" value="Fixed term" required>
    <label for="fixed_term">Fixed term</label><br>
    
    <label for="accept_application">Accept Application by:</label><br>
    <input type="checkbox" id="post" name="accept_application[]" value="Post">
    <label for="post">Post</label>
    <input type="checkbox" id="email" name="accept_application[]" value="Email">
    <label for="email">Email</label><br>
    
    <label for="location">Location:</label><br>
    <select id="location" name="location">
        <option value="---">---</option>
        <option value="ACT">ACT</option>
        <option value="NSW">NSW</option>
        <option value="NT">NT</option>
        <option value="QLD">QLD</option>
        <option value="SA">SA</option>
        <option value="TAS">TAS</option>
        <option value="VIC">VIC</option>
        <option value="WA">WA</option>
    </select><br><br>
    </header>

    <input type="submit" value="Submit" class="submit"><br><br>
    </form>
    <a href="index.php"><button href="index.php" class="return-button">Return to Home</button></a>
</body>
</html>