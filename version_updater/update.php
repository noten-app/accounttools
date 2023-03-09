<?php 

$backlink_string = " <br> <a href='#' onclick='location.assign(\"/version_updater/#s2\")'>Go back</span>";

if(!isset($_POST["username"]) || !isset($_POST["password"])) die("Please enter a username and password.". $backlink_string);

$username = strtolower($_POST['username']);
$password = $_POST['password'];

// Variables
require('../config.php');

// Conect to database
$con = mysqli_connect(config_db_host, config_db_user, config_db_password, config_db_name);
if (mysqli_connect_errno()) exit("Error connecting to our database! Please try again later."); 

// Check if account exists
$stmt = $con->prepare("SELECT id FROM accounts WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) exit("Account $username not found". $backlink_string);

// Get id, salt and password hash from database
if ($stmt = $con->prepare("SELECT id, displayname, salt, password, account_version FROM accounts WHERE username = ?")) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $displayname, $salt, $password_hash, $account_version);
    $stmt->fetch();

    // Get version
    switch ($account_version) {
        case 1 : 
            if (!password_verify($password, $password_hash)) exit("Wrong password". $backlink_string);
            break;
        case 2 : 
            if (!password_verify($salt.$password, $password_hash)) exit("Wrong password". $backlink_string);
            break;
        case 3 :
            exit("Account $username is already up to date". $backlink_string);
            break;
    }

    // Update version
    $account_version = 3;
    $salt = "";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if($stmt = $con->prepare("UPDATE accounts SET account_version = ?, salt = ?, password = ? WHERE id = ?")) {
        $stmt->bind_param('isss', $account_version, $salt, $hashed_password, $id);
        $stmt->execute();
        exit("success");
    }
}

?>