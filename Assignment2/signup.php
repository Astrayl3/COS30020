<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <?php
    session_start();

    // Initialize variables
    $email = $email_err = $profile_name = $profile_name_err = $password = $password_err = $confirm_password = $confirm_password_err = "";

    // Database connection
    $servername = "feenix-mariadb.swin.edu.au";
    $username = "s104190714"; // MySQL username
    $db_password = "241103"; // MySQL password
    $dbname = "s104190714_db"; // MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate email
        $email_input = trim($_POST["email"]);
        if (empty($email_input)) {
            $email_err = "Please enter an email.";
        } elseif (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        } else {
            $email = $email_input;
            // Check if email is already taken
            $sql = "SELECT friend_id FROM friends WHERE friend_email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows != 0) {
                $email_err = "This email is already taken.";
            }
            $stmt->close();
        }

        // Validate profile name
        $profile_name_input = trim($_POST["profile_name"]);
        if (empty($profile_name_input)) {
            $profile_name_err = "Please enter a profile name.";
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $profile_name_input)) {
            $profile_name_err = "Profile name must contain only letters and spaces.";
        } else {
            $profile_name = $profile_name_input;
        }

        // Validate password
        $password_input = trim($_POST["password"]);
        if (empty($password_input)) {
            $password_err = "Please enter a password.";
        } elseif (strlen($password_input) < 6) {
            $password_err = "Password must have at least 6 characters.";
        } else {
            $password = $password_input;
        }

        // Validate confirm password
        $confirm_password_input = trim($_POST["confirm_password"]);
        if (empty($confirm_password_input)) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = $confirm_password_input;
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }

        // Check input errors before inserting into database
        if (empty($email_err) && empty($profile_name_err) && empty($password_err) && empty($confirm_password_err)) {

            // Prepare an insert statement
            $sql = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends) VALUES (?, ?, ?, CURDATE(), 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $param_email, $param_password, $param_profile_name);

            $param_email = $email;
            $param_password = $password; // Store the password as plain text
            $param_profile_name = $profile_name;

            if ($stmt->execute()) {
                $_SESSION["loggedin"] = true;
                $_SESSION["email"] = $email;
                header("location: friendadd.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }

        // Close connection
        $conn->close();
    }
    ?>
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required>
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Profile Name</label>
            <input type="text" name="profile_name" value="<?php echo $profile_name; ?>">
            <span><?php echo $profile_name_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Register">
        </div>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
    <p>Return to <a href="index.php">Home Page</a>.</p>
</body>
</html>
