<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
    session_start();

    // Check if the user is already logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: friendlist.php");
        exit;
    }

    // Database connection
    $servername = "feenix-mariadb.swin.edu.au"; 
    $username = "s104190714"; // MySQL username
    $db_password = "241103"; // MySQL password
    $dbname = "s104190714_db"; // MySQL database name

    $conn = new mysqli($servername, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Define variables and initialize with empty values
    $email = $password = "";
    $email_err = $password_err = "";

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check if email is empty
        $email_input = trim($_POST["email"]);
        if (empty($email_input)) {
            $email_err = "Please enter email.";
        } else {
            $email = $email_input;
        }
        

        // Check if password is empty
        $password_input = trim($_POST["password"]);
        if (empty($password_input)) {
            $password_err = "Please enter your password.";
        } else {
            $password = $password_input;
        }
        
        // Validate credentials
        if (empty($email_err) && empty($password_err)) {
            // Prepare a select statement
            $sql = "SELECT friend_id, friend_email, password FROM friends WHERE friend_email = ?";

            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = $email;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Store result
                    $stmt->store_result();

                    // Check if email exists, if yes then verify password
                    if ($stmt->num_rows == 1) {
                        // Bind result variables
                        $stmt->bind_result($id, $email, $db_password);
                        if ($stmt->fetch()) {
                            // Verify the password
                            if ($password === $db_password) {
                                // Password is correct, so start a new session
                                session_start();
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;

                                // Redirect user to friendlist.php
                                header("location: friendlist.php");
                            } else {
                                // Display an error message if password is not valid
                                $password_err = "The password you entered was not valid.";
                            }
                        }
                    } else {
                        // Display an error message if email doesn't exist
                        $email_err = "No account found with that email.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
    }

    // Close connection
    $conn->close();
    ?>
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up now</a>.</p>
    <p>Return to <a href="index.php">Home Page</a>.</p>
</body>
</html>
