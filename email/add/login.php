<?php 
    if(!isset($_POST["username"]) || !isset($_POST["password"])) die("Missing Input");

    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    // Variables
    require('../../config.php');

    // Conect to database
    $con = mysqli_connect(config_db_host, config_db_user, config_db_password, config_db_name);
    if (mysqli_connect_errno()) exit("Error connecting to our database! Please try again later."); 

    // Get id, salt and password hash from database
    if ($stmt = $con->prepare("SELECT id, password, account_version, email FROM accounts WHERE username = ?")) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $password_db, $account_version, $email);
        $stmt->fetch();

        // Get version
        if($account_version < 3) header("Location: https:\/\/accounttools.noten-app.de/version_updater/");

        // Check if user exists
        if($stmt->num_rows == 0) exit("wrongpass");

        // Check if email is already set
        if($email != "") exit("emailset");

        // Check if password is correct
        if(!password_verify($password, $password_db)) exit("wrongpass");

        // Store pw in session to check later when adding email
        session_start();
        $_SESSION['password'] = $password_db;
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $id;
        exit("success");
    }
?>