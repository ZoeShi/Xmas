<?php
	/*
	*
	*@author nico und sasette
	* 
	* 
	 */
	session_start();
	if(!isset($_POST['name']) && !isset($_POST['password'])){
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body id="background">
	<h1>Login</h1>
	<p>Noch nicht registriert?</p>
	<a id="register" href="register.php">registrieren</a>
	<form action="login.php" method="post">
		<div id="regblock">
			<input class="napw" id="name" name="name">Name
			<input class="napw" id="password" type="password" name="password">Passwort
			<input id="submit" type="submit" name="submit" value="Login">
		</div>
	</form>
</body>
</html>
<?php
	} 
?>


<?php
	if(isset($_POST['name']) && isset($_POST['password'])){
		$name = $_POST['name'];
		$password = $_POST['password'];
		$password = md5($password);

		$db = mysqli_connect("localhost","xmas","24GL9C1","xmas");
		$query = "SELECT id FROM users Where name='$name' and password='$password'";

		$res = mysqli_query($db, $query);
		$num = mysqli_affected_rows($db);
		
		// validate login data
		if($num != 0){
			$res = mysqli_fetch_assoc($res);
			$_SESSION['userId'] = $res['id'];
			header("Location: http://192.168.10.204/xmas/src/index.php");
		}else{
			echo "<script>";
   			echo " function error() {";
   			echo "var message = 'Benutzername oder Passwort falsch';";
   			echo "alert(message);";
   			echo "return;";
   			echo "};";
   			echo "error();";
   			echo "window.location = 'http://192.168.10.204/xmas/src/login.php';";
   			echo "</script>";
		}
	} 
?>	
