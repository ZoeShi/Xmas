
<?php 
	/*
	*
	*@author nico und sasette
	* 
	* 
	 */
	session_start();
	if (!isset($_SESSION['userId'])){
		echo "<script>";
   		echo " function error() {";
   		echo "var message = 'Benutzername schon vergeben';";
   		echo "alert(message);";
   		echo "return;";
   		echo "};";
   		echo "error();";
		echo "console.log('pjjjjjjw');";
   		echo "window.location = 'http://192.168.10.204/xmas/src/register.php';";
   		echo "</script>";
		
		
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>xmas calendar</title>
	<link type="text/css" rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>
	<div id="xmas">
	<h1>Xmas Calandar</h1>
	</div>
		<div id="date">
			<h1><?php echo "Datum ".date("d,D"); ?></h1>
		</div>
	<div id="background">

	</div>
	<form action="index.php" id="formElement" method="post">
		<p><input type="hidden" name="id" id="inputElement" value="1" /></p>
	</form>

	<?php
		if (isset($_POST['id'])){
			$door = $_POST['id'];
			$date = date("d");
			if ( $door <= $date){
				// update door value in database
				$db = mysqli_connect("localhost","xmas","24GL9C1","xmas");

				mysqli_query($db, "UPDATE doors SET value=1 WHERE name='door{$door}' and user_id={$_SESSION['userId']}");
				
			}else{
				// to do errorhandling für fall datum zu früh
				 echo '<p>Das Datum wurde noch nicht erreicht</p>';
			}
		}

				
		$db = mysqli_connect("localhost","xmas","24GL9C1","xmas");
		$doorsNum = mysqli_query($db, "SELECT * FROM doors WHERE user_id={$_SESSION['userId']}");
		$num = mysqli_num_rows($doorsNum);
		$doors = array();
		
		for($i = 1;$i <= $num;$i++){
			$doors[] = mysqli_fetch_assoc($doorsNum);
		}

		//show doors and open doors
		foreach($doors as $door){
			echo "<div id='{$door['name']}'>";
			if($door['value'] == 0){
				echo "<img src='..{$door['pfad_closed']}' name='{$door['name']}' id='{$door['door_num']}' onClick='javascript:picswitch(this)'>";
			}else{
				if($door['type'] == 'pdf'){
					echo  "<a href='http://192.168.10.204/xmas{$door['pfad_open']}'><img src='http://192.168.10.204/xmas/picture/open/link/link.png'></a>";
				}
				if($door['type'] == 'link'){
					$link = file_get_contents("http://192.168.10.204/xmas/{$door['pfad_open']}");
					echo "<a href='{$link}'><img src='http://192.168.10.204/xmas/picture/open/link/link.png'></a>";
				}
				if($door['type'] == 'picture'){
					echo "<img src='..{$door['pfad_open']}'>";
				}
				
			}
			echo "</div>";
		}

	?>

</body>

<script>
	
	date = new Date();
	var today = date.getDate();	 
	
	function error() {
  		var message = "Das Datum ist noch nicht erreicht";
  		alert(message);
  		return true;
	};

	function picswitch(door){
		var id = door.id;
		if (name != "open"+id){
			if(id <= today){
				// set value and submit
				document.getElementById('inputElement').value = id;
				document.getElementById("formElement").submit();

			}else{
				error();
			}
		}
	}
</script>
</html>