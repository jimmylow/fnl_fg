<?php
    
     $varscu = 1;
    //make sure the uploaded file transfer was successful
    if ($_FILES['uploadfile']['error'] != UPLOAD_ERR_OK) {
		switch ($_FILES['uploadfile']['error']) {
	    case UPLOAD_ERR_INI_SIZE:
	        echo "<script>";
	        echo 'alert("The uploaded file exceeds the upload_max_filesize directive in php.ini.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
                        break;
		case UPLOAD_ERR_FORM_SIZE:
		        echo "<script>";
	        echo 'alert("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
			break;
		case UPLOAD_ERR_PARTIAL:
		        echo "<script>";
	        echo 'alert("The uploaded file was only partially uploaded.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
			break;
		case UPLOAD_ERR_NO_FILE:
		        echo "<script>";
	        echo 'alert("No file was uploaded.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
		        echo "<script>";
	        echo 'alert("The server is missing a temporary folder.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
			break;
		case UPLOAD_ERR_CANT_WRITE:
		        echo "<script>";
	        echo 'alert("The server failed to write the uploaded file to disk.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
			break;
		case UPLOAD_ERR_EXTENSION:
		        echo "<script>";
	        echo 'alert("File upload stopped by extension.! Image No Save")';
	        echo "</script>";
	        $varscu = 0;
			break;
		}
	}

        if  ($_FILES['uploadfile']['size'] > 1048576){
         echo "<script>";
	     echo 'alert("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.! Image No Save")';
	     echo "</script>";
	     $varscu = 0;
    }    
      
    if ($varscu == 1) {  
	//get info about the image being uploaded
	list($width, $height, $type, $attr) =
	getimagesize($_FILES['uploadfile']['tmp_name']);
	
	// make sure the uploaded file is really a supported image
	switch ($type) {
	case IMAGETYPE_GIF:
		$image = imagecreatefromgif($_FILES['uploadfile']['tmp_name']) or
		die('The file you uploaded was not a supported filetype.');
		$ext = '.gif';
		break;
	case IMAGETYPE_JPEG:
		$image = imagecreatefromjpeg($_FILES['uploadfile']['tmp_name']) or
		die('The file you uploaded was not a supported filetype.');
		$ext = '.jpg';
		break;
	case IMAGETYPE_PNG:
		$image = imagecreatefrompng($_FILES['uploadfile']['tmp_name']) or
		die('The file you uploaded was not a supported filetype.');
		$ext = '.png';
	break;
		default:
		die('The file you uploaded was not a supported filetype.');
	}
	
	
	$imagename = $imgnm.$ext;
	// update the image table now that the final filename is known.
	 
	//save the image to its final destination
	switch ($type) {
	case IMAGETYPE_GIF:
		imagegif($image, $dir .'/'. $imagename);
		break;
	case IMAGETYPE_JPEG:
		imagejpeg($image, $dir .'/' . $imagename, 100);
		break;
	case IMAGETYPE_PNG:
		imagepng($image, $dir .'/'. $imagename);
	break;
	}
	imagedestroy($image);
	}
?>
