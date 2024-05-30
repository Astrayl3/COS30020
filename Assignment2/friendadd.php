<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FriendAdd</title>
</head>
<body>
    <?php
    session_start();

    // Check if the user is logged in
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

    // Pagination variables
    $limit = 5; // Number of names per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page
    $start = ($page - 1) * $limit; // Offset

    // Fetch users who are not friends of the current user with pagination
    $sql_users = "SELECT f.profile_name, f.friend_email
        FROM friends f 
        WHERE f.friend_email != ? 
        AND f.friend_id NOT IN (
            SELECT mf.friend_id2 
            FROM myfriends mf 
            INNER JOIN friends f ON mf.friend_id2 = f.friend_id 
            WHERE mf.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?)
        ) 
        ORDER BY f.profile_name 
        LIMIT ?, ?";

    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("ssii", $email, $email, $start, $limit);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();

    if ($result_users->num_rows > 0) {
        // Display list of users
        echo "<h2>Welcome, $profile_name!</h2>";
        echo "<h3>Add Friends:</h3>";
        echo "<ul>";
        while ($row = $result_users->fetch_assoc()) {
            $friend_profile_name = $row["profile_name"];
            $friend_email = $row["friend_email"];
            echo "<li>$friend_profile_name
                <form action='friendadd.php' method='post'>
                    <input type='hidden' name='friend_email' value='$friend_email'>
                    <input type='submit' value='Add Friend'>
                </form>";

            // Query and display mutual friend count for each user
            $sql_mutual_friends = "SELECT 
            COUNT(*) AS mutual_friends
            FROM myfriends mf1
            JOIN myfriends mf2 ON mf1.friend_id2 = mf2.friend_id2
            JOIN friends f ON mf2.friend_id1 = f.friend_id
            WHERE mf1.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?)
            AND mf2.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?)
            AND mf2.friend_id1 != mf2.friend_id2
            AND mf2.friend_id1 = f.friend_id";
        
            $stmt_mutual_friends = $conn->prepare($sql_mutual_friends);
            $stmt_mutual_friends->bind_param("ss", $email, $email);
        
            $stmt_mutual_friends->execute();
            $result_mutual_friends = $stmt_mutual_friends->get_result();

            if ($result_mutual_friends->num_rows > 0) {
                $mutual_friends_row = $result_mutual_friends->fetch_assoc();
                $mutual_friends_count = $mutual_friends_row["mutual_friends"];
                echo " (Mutual Friends: $mutual_friends_count)";
            } else {
                echo " (Mutual Friends: 0)";
            }

            echo "</li>";

            $stmt_mutual_friends->close();
        }
        echo "</ul>";
    } else {
        echo "<p>No users available to add as friends.</p>";
    }

    // Process adding a friend
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["friend_email"]) && !empty($_POST["friend_email"])) {
            $friend_email = $_POST["friend_email"];

            // Prepare SQL statement to insert a new friend into myfriends table
            $sql_insert_friend = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES (?, (SELECT friend_id FROM friends WHERE friend_email = ?))";

            $stmt_insert_friend = $conn->prepare($sql_insert_friend);
            $stmt_insert_friend->bind_param("ss", $friend_id1, $friend_email);

            $friend_id1 = $_SESSION["id"];

            if ($stmt_insert_friend->execute()) {
                // Friend added successfully
                echo "Friend added successfully.";
                // Refresh the page to update friend count
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "Error adding friend. Please try again later.";
            }

            $stmt_insert_friend->close();
        }
    }

    // Check if previous page link should be displayed
    $prev_page = $page - 1;
    if ($prev_page > 0) {
        echo "<a href='friendadd.php?page=$prev_page'>Previous</a>";
    }

    // Check if next page link should be displayed
    $sql_count = "SELECT COUNT(*) FROM friends WHERE friend_email != ? AND friend_id NOT IN (SELECT mf.friend_id2 FROM myfriends mf INNER JOIN friends f ON mf.friend_id2 = f.friend_id WHERE mf.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?))";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("ss", $email, $email);
    $stmt_count->execute();
    $stmt_count->bind_result($total_records);
    $stmt_count->fetch();
    $total_pages = ceil($total_records / $limit);
    $next_page = $page + 1;
    if ($next_page <= $total_pages) {
        echo "<a href='friendadd.php?page=$next_page'>Next</a>";
    }

    // Display total number of friends
    echo "<p>You have $friend_count friends.</p>";

    // Close the statement
    $stmt_count->close();

    // Close the database connection
    $conn->close();
    ?>
    <p><a href="friendlist.php">Friend List</a> | <a href="logout.php">Log Out</a></p>
</body>
</html>
