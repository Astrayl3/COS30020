<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Home Page</title>
</head>
<body>
    <?php 
    $name = "Duong Ha Duc Anh";
    $IDNumber = "104190714";
    $email = "ducanhprocsgoplayer@gmail.com";
    ?>
    
    <nav class="navbar navbar-expand-lg bg-body-black">
    <div class="container-fluid">
    <img src="style/huh.png" alt="Bootstrap" width="30" height="24">
        <a class="navbar-brand" href="index.php"><b>Home Page</b></a>
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="postjobform.php">Post Job Vacancy</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="searchjobform.php">Search Job Vacancy</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>
    <header class="index">
    <h1>My Job Vacancy System</h1>
    <p><b> &nbsp Name:</b> <?php echo $name; ?></p>
    <p><b> &nbsp Student ID:</b> <?php echo $IDNumber; ?></p>
    <p><b> &nbsp Email:</b> <?php echo $email; ?></p>
    <p> &nbsp I declare that this assignment is my individual work. I have not worked collaboratively, nor have I copied from any other student’s work or from any other source.</p>
    </header>

    <br><img src="style\kurumitokisaki-template by hayami.png" alt="" class="index">
</body>
</html>
