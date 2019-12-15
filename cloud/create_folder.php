<?php 
	if(!isset($_COOKIE['username'])) {
		header("Location: ../index.php"); die();
	}
	
	// deep into filesystem
	$userPath = 'users' . DIRECTORY_SEPARATOR . $_COOKIE['username'] . DIRECTORY_SEPARATOR;
						
	if(isset($_GET['folder'])) {
		$userPath .= $_GET['folder'] . DIRECTORY_SEPARATOR;
	}
	
	if(is_array($_POST) && count($_POST) > 0) {
		$success = false;
		$folderPath = $userPath . $_POST['folder'];

		if(!is_dir($folderPath)) {
			mkdir($folderPath, 0777, true);
			$success = true;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	</head>

	<body>
		<div>
			<div>
				<a href="../logout.php">Wyloguj!</a>
			</div>
			
			<br /><br />
			
			<div>
				<a href="index.php"><button>Wróć</button></a>
			</div>
			
			<?php if(isset($success)): ?>
				<br /><br />
				
				<div>
					<?php if($success): ?>
						Dodano folder
					<?php else: ?>
						Taki folder już istnieje
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<br /><br />
			
			<form method="POST">
				Nazwa folderu:
				<br />
				<input type="text" name="folder" />
				<br /><br />
				<button type="submit">Dodaj</button>
			</form>
		</div>
	</body>
</html>