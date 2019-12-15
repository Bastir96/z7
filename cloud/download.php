<?php
	$file = $_GET['file'];
	$userPath = 'users' . DIRECTORY_SEPARATOR . $_COOKIE['username'] . DIRECTORY_SEPARATOR;
	
	if(isset($_GET['folder'])) {
		$filepath = $userPath . $_GET['folder'] . DIRECTORY_SEPARATOR . $file;
	} else {
		$filepath = $userPath . $file;
	}

	if(file_exists($filepath)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filepath));
		readfile($filepath);
	}
?>