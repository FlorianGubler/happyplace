<?php 
    include "navbar.php";
    
    if(isset($_POST['submit-paswd'])){
        $oldpasswordhash = hash("sha256", $_POST['oldpassword']);
        $newpasswordhash = hash("sha256", $_POST['newpassword']);

        $sql_paswd = "SELECT passwordhash FROM users where username='".$username_login."';";
        $query_paswd = $conn->query($sql_paswd);
        $result_paswd = $query_paswd->fetch_array(MYSQLI_BOTH);
		
        if(isset($_POST['submit-usr'])){
        	$newusername = $_POST['newusername'];
        	$sql_newusr = "UPDATE users SET username='".$newusername."' WHERE username='".$username_login."';";
        	$query_newusr = $conn->query($sql_newusr);
            
            $newpasswordforuser = hash("sha256", $newusername.$result_paswd[0]);
            $sql_newpwd = "UPDATE users SET passwordhash='".$newpasswordforuser."' WHERE username='".$newusername."';";
        	$query_newpwd = $conn->query($sql_newpwd);
            
            setcookie('session-name', $username, 0, "/", "", true, true);
            setcookie('session-id', $username, 0, "/", "", true, true);
    	}
        
        if($oldpasswordhash == $result_user[0]){
            if($newpasswordhash == hash("sha256", $_POST['newpasswordrepeat'])){
                $sql_paswd = "UPDATE users SET passwordhash='".$newpasswordhash."' WHERE username='".$username_login."';";
                $query_paswd = $conn->query($sql_paswd);
            }
            else{
                echo "<script>document.getElementById('getnewpasswordrepeat').style.backgroundColor='red';</script>";
            }
        }
        else{
            echo "<script>document.getElementById('getoldpassword').style.backgroundColor='red';</script>";
        }
    }
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/configuser.css">
<script>document.title = "Mein Profil"; document.getElementById('site-title').innerHTML = "Profil konfigurieren - <?php echo $username_login; ?>"</script>
<h1>Benutzerkonfiguration von <?php echo $username_login; ?></h1>
<div id="userprofile">
    <img src="<?php echo $rootpath; ?>/assets/profilepictures/<?php echo $profilepicture; ?>.png" alt="Profile Picture">
    <a href="<?php echo $rootpath; ?>/getprofilepic.php">Change Profile Picture</a>
    <form id="newusername" action="<?php echo $rootpath ?>/config" method="post" class="newuserinput">
        <label>New Username</label>
        <input id="getnewusername" name="newusername" placeholder="New Username">
        <button name="submit-usr" type="submit" >Neuer Username Speichern</button>
    </form>
    <form id="newpasswordf" action="<?php echo $rootpath ?>/config" method="post" class="newuserinput">
        <label>Neues Passwort</label>
        <input id="getnewusername" name="newpassword" placeholder="New Password">
        <label>Neues Passwort wiederholen</label>
        <input id="getnewpasswordrepeat" name="newpasswordrepeat" placeholder="New Password">
        <label>Altes Passwort eingeben</label>
        <input id="getoldpassword" name="oldpassword" placeholder="Old Password">
        <button type="submit" name="submit-paswd">Neues Passwort Speichern</button>
    </form>
</div>

<?php $adminpath = $rootpath."/admin"; if($login && $username_login == "root"){ echo "<a href='".$adminpath."' style='cursor:pointer;'><p class='dash-icon'><i class='fas fa-users-cog'></i></p><a>Admintools</a></a>";} ?>    <div onclick="location.href='<?php echo $rootpath; ?>/config';" style="cursor:pointer;"><p class="dash-icon"><i class="fas fa-users-cog"></i></p><a>Profil Konfiguration</a></div>
