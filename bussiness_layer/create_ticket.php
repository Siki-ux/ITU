<?php
if(session_id() == "")
  session_start();
//default anonymous user for tickets registred by non registred users
$author = "user@user.com";
//if user is logged, use his email
if (isset($_SESSION["email"])){
  $author = $_SESSION["email"];
}
include_once("../data_layer/db_tickets.php");
//handling of upload of image:
$target_dir = "../img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//if no image was posted, use placeholdeer
if($target_file=="../img/"){
  echo "<script>alert('Nový tiket je nahratý bez obrázku.')</script>";
  upload_new_ticket($_POST["category"],$_POST["lng"],$_POST["lat"],$target_file."placeholder-image.png",$author);
  header("refresh:0.1;../index.php");
  exit();
}
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "<script>alert('Súbor je obrázok - " . $check["mime"] . ".')</script>";
    $uploadOk = 1;
  } else {
    echo "<script>alert('Súbor nieje obrázok.')</script>";
    $uploadOk = 0;
  }
}

// Check if file already exists, if it exists create unique name for file
if (file_exists($target_file)) {
    $tmpFile = $target_file;
    $pos = strlen($target_file) - strlen($imageFileType) -1;
    for($i=0;file_exists($tmpFile);$i++){
        $tmpFile = substr_replace($target_file,$i,$pos,0);
    }
    $target_file = $tmpFile;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "<script>alert('Ospravedňujeme sa ale váš obrázok je príliš veľký')</script>";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  echo "<script>alert('Povolené su iba formáty jpg, ong, a jpeg.')</script>";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<script>alert('Ospravedlňujeme sa, ale nepodarilo sa nahrať váš súbor.')</script>";
    header("refresh:0.1;../index.php");
// if everything is ok, try to upload file, else redirect
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    chmod($target_file, 0755);
    echo "<script>alert('Nový tiket je nahratý s obrákom ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). ".')</script>";
    upload_new_ticket($_POST["category"],$_POST["lng"],$_POST["lat"],$target_file,$author);
    header("refresh:0.1;redirect.php");
  } else {
    echo "<script>alert('Ospravedlňujeme sa nastala chyba s vaším súborom.)</script>";
    header("refresh:0.1;../index.php");
  }
}

?>