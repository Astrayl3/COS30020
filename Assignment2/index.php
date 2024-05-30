<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friend System - Assignment Home Page</title>
</head>
<body>
    <h1>My Friend System</h1>
    <h2>Assignment Home Page</h2>

    <?php
    // Your details
    $name = "Duong Ha Duc Anh";
    $email = "104190714@student.swin.edu.au";
    $student_id = "104190714";

    echo "<p>Name: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Student ID: $student_id</p>";

    echo "<p>I declare that this assignment is my individual work. I have not worked collaboratively nor have I copied from any other student’s work or from any other source.</p>";

    // Database connection
    $servername = "feenix-mariadb.swin.edu.au";
    $username = "s104190714"; // MySQL username
    $password = "241103"; // MySQL password

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database
    $dbname = "s104190714_db";
    $create_db_sql = "CREATE DATABASE IF NOT EXISTS $dbname";

    // Select database
    $conn->select_db($dbname);

    // Create friends table
    $create_friends_table_sql = "CREATE TABLE IF NOT EXISTS friends (
        friend_id INT AUTO_INCREMENT PRIMARY KEY,
        friend_email VARCHAR(50) NOT NULL,
        password VARCHAR(20) NOT NULL,
        profile_name VARCHAR(30) NOT NULL,
        date_started DATE NOT NULL,
        num_of_friends INT UNSIGNED
    )";

    // Create myfriends table
    $create_myfriends_table_sql = "CREATE TABLE IF NOT EXISTS myfriends (
        friend_id1 INT NOT NULL,
        friend_id2 INT NOT NULL,
        FOREIGN KEY (friend_id1) REFERENCES friends(friend_id),
        FOREIGN KEY (friend_id2) REFERENCES friends(friend_id),
        UNIQUE (friend_id1, friend_id2)
    )";

    // Populate friends table with sample records
    $populate_friends_sql = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
    VALUES
    ('user1@gmail.com', 'password1', 'John Doe', '2022-01-01', 10),
    ('user2@gmail.com', 'password2', 'Jane Smith', '2022-01-02', 15),
    ('user3@gmail.com', 'password3', 'Alice Johnson', '2022-01-03', 20),
    ('user4@gmail.com', 'password4', 'Bob Brown', '2022-01-04', 5),
    ('user5@gmail.com', 'password5', 'Emily Davis', '2022-01-05', 12),
    ('user6@gmail.com', 'password6', 'Michael Wilson', '2022-01-06', 8),
    ('user7@gmail.com', 'password7', 'Sophia Martinez', '2022-01-07', 17),
    ('user8@gmail.com', 'password8', 'David Anderson', '2022-01-08', 11),
    ('user9@gmail.com', 'password9', 'Emma Taylor', '2022-01-09', 14),
    ('user10@gmail.com', 'password10', 'James Thomas', '2022-01-10', 9),
    ('user11@gmail.com', 'password11', 'Daniel Roberts', '2022-01-11', 5),
    ('user12@gmail.com', 'password12', 'Olivia Garcia', '2022-01-12', 19),
    ('user13@gmail.com', 'password13', 'Liam Miller', '2022-01-13', 2),
    ('user14@gmail.com', 'password14', 'Isabella Lopez', '2022-01-14', 13),
    ('user15@gmail.com', 'password15', 'Noah Lee', '2022-01-15', 15),
    ('user16@gmail.com', 'password16', 'Ava Hernandez', '2022-01-16', 7),
    ('user17@gmail.com', 'password17', 'Ethan Adams', '2022-01-17', 8),
    ('user18@gmail.com', 'password18', 'Mia Carter', '2022-01-18', 9),
    ('user19@gmail.com', 'password19', 'Benjamin Perez', '2022-01-19', 9),
    ('user20@gmail.com', 'password20', 'Charlotte King', '2022-01-20', 9)";


    // Populate myfriends table with sample records
    $populate_myfriends_sql = "INSERT INTO myfriends (friend_id1, friend_id2)
    VALUES
    (1, 16),
    (1, 3),
    (2, 17),
    (4, 5),
    (13, 6),
    (5, 6),
    (18, 8),
    (7, 9),
    (8, 17),
    (9, 10),
    (10, 1),
    (12, 4),
    (3, 5),
    (5, 16),
    (11, 8),
    (8, 20),
    (1, 4),
    (14, 5),
    (3, 19),
    (4, 7)";


    $conn->close();
    ?>

    <a href="signup.php">Sign-Up</a>
    <a href="login.php">Log-In</a>
    <a href="about.php">About</a>
</body>
</html>
