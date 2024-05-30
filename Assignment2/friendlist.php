<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friend List</title>
</head>
<body>
    <?php
    session_start();

    // Check if the user is logged in, if not then redirect to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    // Database connection
    $servername = "feenix-mariadb.swin.edu.au"; 
    $username = "s104190714"; // MySQL username
    $password = "241103"; // MySQL password
    $dbname = "s104190714_db"; // MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the current user's email
    $email = $_SESSION["email"];

    // Fetch current user's profile name
    $sql_profile = "SELECT profile_name FROM friends WHERE friend_email = ?";
    $stmt_profile = $conn->prepare($sql_profile);
    $stmt_profile->bind_param("s", $email);
    $stmt_profile->execute();
    $stmt_profile->store_result();
    $stmt_profile->bind_result($profile_name);
    $stmt_profile->fetch();
    $stmt_profile->close();

    // Fetch friends of the current user
    $sql_friends = "SELECT f.profile_name, f.friend_email 
    FROM friends f 
    INNER JOIN myfriends mf ON (f.friend_id = mf.friend_id1 AND mf.friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = ?)) 
                            OR (f.friend_id = mf.friend_id2 AND mf.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?)) 
    WHERE f.friend_id != (SELECT friend_id FROM friends WHERE friend_email = ?) 
    ORDER BY f.profile_name";
    $stmt_friends = $conn->prepare($sql_friends);
    $stmt_friends->bind_param("sss", $email, $email, $email);
    $stmt_friends->execute();
    $result_friends = $stmt_friends->get_result();

    // Display friend list
    echo "<h2>Welcome, $profile_name!</h2>";
    echo "<h3>Your Friends:</h3>";
    echo "<ul>";
    while ($row = $result_friends->fetch_assoc()) {
        $friend_profile_name = $row["profile_name"];
        $friend_email = $row["friend_email"];
        echo "<li>$friend_profile_name 
            <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                <input type='hidden' name='friend_email' value='$friend_email'>
                <input type='submit' name='unfriend' value='Unfriend'>
            </form>
            </li>";
    }
    echo "</ul>";
    
    // Fetch total number of friends for the current user
    $sql_friend_count = "SELECT 
    (SELECT COUNT(*) FROM myfriends WHERE friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?)) +
    (SELECT COUNT(*) FROM myfriends WHERE friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = ?)) AS total_friends";
    $stmt_count = $conn->prepare($sql_friend_count);
    $stmt_count->bind_param("ss", $email, $email);
    $stmt_count->execute();
    $stmt_count->store_result();
    $stmt_count->bind_result($friend_count);
    $stmt_count->fetch();
    $stmt_count->close();

    // Display total number of friends
    echo "<p>Total Friends: $friend_count</p>";

    // Process unfriending
    if (isset($_POST["unfriend"]) && isset($_POST["friend_email"])) {
        $friend_email_to_remove = $_POST["friend_email"];

        // Prepare SQL statement to delete friend from myfriends table
        $sql_delete_friend = "DELETE FROM myfriends WHERE friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?) AND friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = ?)";
        $stmt_delete_friend = $conn->prepare($sql_delete_friend);
        $stmt_delete_friend->bind_param("ss", $email, $friend_email_to_remove);
        if ($stmt_delete_friend->execute()) {
            // Friend removed successfully
            echo "<p>Friend removed successfully.</p>";
        } else {
            echo "<p>Error removing friend. Please try again later.</p>";
        }
        $stmt_delete_friend->close();
    }

    // Close the database connection
    $conn->close();
    ?>
    <p><a href="friendadd.php">Add Friends</a> | <a href="logout.php">Log Out</a></p>
</body>
</html>
