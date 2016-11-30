<form action="img_upload.php" method="post" enctype="multipart/form-data">
    Välj get-porträttering att ladda upp:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input id="add" type="submit" value="Upload Image" name="submit">
</form>

<?php

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo ($target_file, PATHINFO_EXTENSION);

if (isset($_POST["submit"])) {
	$check = getimagesize ($_FILES["fileToUpload"]["tmp_name"]);
	if ($check !== false) {
		echo "Korrekt djurart: " . $check["mime"] . ".";
		$uploadOk =1;
	} else {
		echo "Filen är inte en get.";
		$uploadOk = 0;
	}	
}

if (file_exists($target_file)) {
	echo "Denna get finns redan!";
	$uploadOk = 0;
}


if ($_FILES["fileToUpload"]["size"] > 5000000) {
	echo "Tyvärr, geten är för stor.";
	$uploadOk = 0;
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
	echo "Bara getter med efternamn JPG, JPEG, PNG & GIF finns på denna gård.";
	$uploadOk = 0;
}

if ($uploadOk == 0) {
	echo "Tyvärr, geten hittade inte fram.";
} else {
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		echo " Geten: ". "'" . basename ( $_FILES["fileToUpload"]["name"]) . "'" . " har laddats upp.";
	} else {
		echo "Tyvärr, geten blev förvirrad på vägen och försvann.";
	}
}


?>