<?php
	/*
	*
	*@author nico und sasette
	* 
	* 
	 */
		if(!isset($_POST['name']) && !isset($_POST['password'])){
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/login.css">
	</head>
		<body id="background">
			<h1>Registrieren</h1>
			
				<form action="register.php" method="post">
					<div id="regblock">
						<input class="napw" id="name" name="name">Name
						<input class="napw" id="password" type="password" name="password">Passwort
						<br>
						<input id="submit" type="submit" name="submit" value="Registrieren">
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
		
		echo "<script>";
		echo "console.log('oaefjulasbrhjq+iwhn')";
		echo "message = password.value;";
		echo "messageCount = message.length;";
		echo "if (messageCount == 0){";
		echo "console.log(messageCount)";
   		echo " function error() {";
   		echo "var message = 'bitte Passwort setzen';";
   		echo "alert(message);";
   		echo "return;";
   		echo "};";
   		echo "error();";
   		echo "window.location = 'http://192.168.10.204/xmas/src/register.php';";
   		echo "}";
   		echo "</script>";
		

		$db = mysqli_connect("localhost","xmas","24GL9C1","xmas");
		$query = "SELECT * FROM users Where name='$name'";
		
		mysqli_query($db, $query);
		$num = mysqli_affected_rows($db);

		
		// create random doors and fill into database
		if ($num == 0){
			$query = "INSERT INTO users (name, password) VALUES('$name','$password')";
			$tmp = mysqli_query($db, $query);
			header("Location: http://192.168.10.204/xmas/src/login.php");
			$res = mysqli_query($db, "SELECT id FROM users WHERE name='$name'");
			$res = mysqli_fetch_assoc($res);
		

			$item = array();
			$config  = parse_ini_file('../config/content.ini');
			$pdfCount = $config['pdf'];
			$pictureCount = $config['picture'];
			$linkCount = $config['link'];

			// pdf as open door
			$pdfPool = "../pdf";
			$pdfPool = opendir($pdfPool);
			$pdfs = array();
			while (($pdf = readdir($pdfPool)) !== false){
				$pdfs[] = $pdf;
			}


			for($num=1;$num<=$pdfCount;$num++){
				$pdfIndex = array_rand($pdfs);
				$pdf = ($pdfs[$pdfIndex]);
				if($pdf == "." || $pdf == ".."){
					$num--;
					continue;
				}else{
					$item[] = array("value"=>$pdf, "type"=>"pdf");
				}

				unset($pdfs[$pdfIndex]);
			}

			// picture as open door
			$picturePool = "../picture/open";
			$picturePool = opendir($picturePool);
			$pictures = array();
			while (($picture = readdir($picturePool)) !== false){
				$pictures[] = $picture;
			}


			for($num=1;$num<=$pictureCount;$num++){
				$pictureIndex = array_rand($pictures);
				$picture = ($pictures[$pictureIndex]);
				if($picture == "." || $picture == ".." || $picture == "link"){
					$num--;
					continue;
				}else{
					$item[] = array("value"=>$picture, "type"=>"picture");
				}

				unset($pictures[$pictureIndex]);
			}

			// link as open door
			$linkPool = "../link";
			$linkPool = opendir($linkPool);
			$links = array();
			while (($link = readdir($linkPool)) !== false){
				$links[] = $link;
			}


			for($num=1;$num<=$linkCount;$num++){
				$linkIndex = array_rand($links);
				$link = ($links[$linkIndex]);
				if($link == "." || $link == ".."){
					$num--;
					continue;
				}else{
					$item[] = array("value"=>$link, "type"=>"link");
				}

				unset($links[$linkIndex]);
			}

			shuffle($item);
			
			// write doors into database
			$count = 0;
			$num = 1;
			foreach($item as $value => $type) {
				if($item[$count]['type'] == "pdf"){
					$b = mysqli_query($db, "INSERT INTO doors (name, value, type, pfad_open, pfad_closed, user_id, door_num) VALUES('door{$num}', 0, 'pdf', '/pdf/{$item[$count]['value']}', '/picture/closed/door{$num}.png', {$res['id']},{$num})");
				}elseif ($item[$count]['type'] == "picture") {
					$b = mysqli_query($db, "INSERT INTO doors (name, value, type, pfad_open, pfad_closed, user_id, door_num) VALUES('door{$num}', 0, 'picture', '/picture/open/{$item[$count]['value']}', '/picture/closed/door{$num}.png', {$res['id']},{$num})");
				}elseif ($item[$count]['type'] == "link") {
					$b = mysqli_query($db, "INSERT INTO doors (name, value, type, pfad_open, pfad_closed, user_id, door_num) VALUES('door{$num}', 0, 'link', '/link/{$item[$count]['value']}', '/picture/closed/door{$num}.png', {$res['id']},{$num})");
				}

				$num ++;
				$count ++;
			}

		}else{
			echo "<script>";
   			echo " function error() {";
   			echo "var message = 'Benutzername schon vergeben';";
   			echo "alert(message);";
   			echo "return;";
   			echo "};";
   			echo "error();";
   			echo "window.location = 'http://192.168.10.204/xmas/src/register.php';";
   			echo "</script>";
		}
	} 
	


?>
