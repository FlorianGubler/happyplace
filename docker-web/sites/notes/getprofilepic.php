<?php 
    include "navbar.php";

    if(isset($_POST["submit"])) {
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
        } 
        else {
          echo "File is not an image.";
          $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        }
        $path = $rootpath."/assets/profilepictures/pb_".$username_login.".".$imageFileType;

        if ($uploadOk != 0) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $path)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            } 
            else {
                echo "Sorry, there was an error uploading your file.";
            }
        } 
      }
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/configuser.css">
<script>document.title = "Dein Profilbild"; document.getElementById('site-title').innerHTML = "Profilbild auswählen"</script>
<form action="getprofilepic.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
</form>
<script>

</script>