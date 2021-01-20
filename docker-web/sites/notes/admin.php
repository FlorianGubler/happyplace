<?php 
	/*if($_COOKIE['session-name'] != "root"){
		header("Location: ".$rootpath."/home");
	}*/

	include "navbar.php";

	$tabels = array("bms_bwl", "bms_che", "bms_fr", "bms_frvo", "bms_ge", "bms_mat", "bs", "lap", "uek");

	$sql_count_users = "SELECT COUNT(id) FROM users";
	$result_count_users = $conn->query($sql_count_users);
	$count_users = $result_count_users->fetch_array(MYSQLI_BOTH);
	$userscount = $count_users[0]+1;

	if(isset($_POST['submit-btn'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		for($a=0;$a < count($tabels); $a++){
			$sql_make = "INSERT INTO ".$tabels[$a]." (id) VALUES($userscount);";
			$result_make = $conn->query($sql_make);
		}

		$passwordhash = hash('sha256', $username.$password);

		$sql_newuser = "INSERT INTO users (id, username, passwordhash, bms_bwl_id, bms_che_id, bms_fr_id, bms_frvo_id, bms_ge_id, bs_id, lap_id, uek_id, bms_mat_id) VALUES($userscount, '$username', '$passwordhash', $userscount, $userscount, $userscount, $userscount, $userscount, $userscount, $userscount, $userscount, $userscount);";
		$result_newuser = $conn->query($sql_newuser);
	}

?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/admin.css">
<script>document.title = "Home"; document.getElementById('site-title').innerHTML = "Home"</script>
<h1>Admintools</h1>
<div id="userlist">
	<h2>Userlist</h2>
	<table>
		<tr>
			<th>Username</th>
			<th>Password</th>
		</tr>
		<?php
			for($i=1; $i < $userscount; $i++){
				echo "<tr>";
				$sql_getusers = "SELECT username, passwordhash FROM users WHERE id=".$i.";";
				$result_getusers= $conn->query($sql_getusers);
				$getusers = $result_getusers->fetch_array(MYSQLI_BOTH);
				echo "<td>".$getusers[0]."</td>";
				echo "<td>".substr($getusers[1], 0 , 30)."...</td>";
				echo "</tr>";
			}
		?>
	</table>
</div>
<div id="adduser-container">
	<h2>Benutzer hinzufügen</h2>
	<form id="adduser" action="<?php echo $rootpath; ?>/admin.php" method="post">
		<input placeholder="Username" type="text" name="username">
		<input placeholder="Passwort" type="password" name="password">
		<button type="submit" name="submit-btn">Neuen Benutzer erstellen</button>
	</form>
</div>