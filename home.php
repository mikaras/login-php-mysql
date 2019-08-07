<?php
// Start session
session_start();

// If not logged redirect to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = "";
$DATABASE_NAME = "login";
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$con->set_charset("utf8"); 

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
//$stmt = $con->prepare('SELECT password, email, name FROM users WHERE uid = ?');
$stmt = $con->prepare("SELECT users.name, users.height, users.weight, stats.cooper, stats.clean FROM users INNER JOIN stats ON users.uid = stats.uid WHERE users.uid =?");
//We can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();

$stmt->bind_result($name, $height, $weight, $cooper, $clean);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="styles.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"> 
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Trainer</h1>
				<a href="home.php"><i class="fas fa fa-home"></i>Dashboard</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Dashboard</h2>
			<!--<p>Welcome back, <?=$_SESSION['name']?>!</p>-->
		</div>
		<div class="content">
			<div>
				<p>Your details are below:</p>
				<table>
					<tr>
						<td>Name:</td>
						<td><?=$name?></td>
					</tr>
						<td>Height:</td>
						<td><?=$height?> cm</td>
					</tr>
					<tr>
						<td>Weight:</td>
						<td><?=$weight?> kg</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="content">
			<div>
				<p>Test results:</p>
				<table>
					<tr>
						<td>Cooper:</td>
						<td><?=$cooper?> m</td>
					</tr>
					<tr>
						<td>Clean:</td>
						<td><?=$clean?> kg</td>
					</tr>
				</table>
			</div>
		</div>		

	</body>
</html>