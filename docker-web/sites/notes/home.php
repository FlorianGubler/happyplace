<?php include "navbar.php";?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/home.css">
<script>document.title = "Home"; document.getElementById('site-title').innerHTML = "Home"</script>
<h1>Hallo, <?php echo $username_login; ?></h1>
<div id="notes-container">
	<h2>Notenberechnung</h2>
	<div><p class="dash-icon"><i class="far fa-clipboard"></i></p><a>BMS Noten</a></div>
	<div><p class="dash-icon"><i class="far fa-clipboard"></i></p><a>LAP Noten</a></div>
</div>
<div id="else-container">
	<h2>Sonstiges</h2>
    <div><p class="dash-icon"><i class="fas fa-users-cog"></i></p><a href="<?php echo $rootpath; ?>/config/user/<?php echo urlencode($username_login); ?>">Profil Konfiguration</a></div>
</div>
<div id="chat">
	<p><i class="far fa-comments"></i> Chat - not implemented</p>
	<form id="sendmsg">
		<input placeholder="Sende eine Nachricht" name="message" >
		<button type="submit"><i class="far fa-paper-plane"></i></button>
	</form>
</div>

