<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
//$targetFolder = '/uploads'; // Relative to the root
$targetFolder = $_POST['targetFolder']; // wp upload directory
$dir = str_replace('\\','/',dirname(__FILE__));

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = $dir.$targetFolder;
    $targetPath = $targetFolder;
    $fileName = $_POST['user_id'].'_'.$_FILES['Filedata']['name'];
	$targetFile = rtrim($targetPath,'/') . '/' . $fileName;
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Invalid file type.';
	}
}
?>