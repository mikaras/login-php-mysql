

<?php
session_start();

$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = "";
$DATABASE_NAME = "login";


$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check if the data from the login form was submitted.
if ( !isset($_POST['username'], $_POST['password']) ) {
	die ('Please fill both the username and password field!');
	//header('Location: index.html');	
}

if ($stmt = $con->prepare('SELECT uid, password FROM users WHERE username = ?')) {
	
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();


if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $password);
	$stmt->fetch();
	// Account exists, now check the password.
	//if (password_verify($_POST['password'], $password)) {
	if ($_POST['password'] === $password) {
		// User has logged in and now create sessions 
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		header('Location: home.php');
		//echo 'Welcome ' . $_SESSION['name'] . '!';
	} else {
		
		echo "<p>Incorrect password!</p";
		header('Location: index.html');
		exit();
	}
} else {
	echo 'Incorrect username!';
	header('Location: index.html');
	exit();
}
$stmt->close();
}
