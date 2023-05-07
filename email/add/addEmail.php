<?php 
    if(!isset($_POST["username"]) || !isset($_POST["password"])) die("Missing Input");

    $username = strtolower($_POST['username']);
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Variables
    require('../../config.php');

    // Conect to database
    $con = mysqli_connect(config_db_host, config_db_user, config_db_password, config_db_name);
    if (mysqli_connect_errno()) exit("Error connecting to our database! Please try again later."); 

    // Check if email is real
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) exit("wrongemail");

    // Check if password is correct
    session_start();
    if(!password_verify($password, $_SESSION['password'])) exit("wrongpass");
    if($username != $_SESSION['username']) exit("wronguser");

    // Update email in database
    if ($stmt = $con->prepare("UPDATE accounts SET email = ? WHERE username = ? AND id = ?")) {
        $stmt->bind_param('sss', $email, $username, $_SESSION['id']);
        $stmt->execute();
        $stmt->store_result();

        // Check if email was updated
        if($stmt->affected_rows == 0) exit("not-affected");
        session_destroy();
        exit("success");
    }
?>