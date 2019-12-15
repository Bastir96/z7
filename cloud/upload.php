<?php 
	if(!isset($_COOKIE['username'])) {
		header("Location: ../index.php"); die();
	}

	if(is_array($_FILES) && count($_FILES) > 0) {
		$MAX_SIZE = 1024 * 1024;
		$tempName = $_FILES['file']['tmp_name'];
		
		if(is_uploaded_file($tempName)) {
			if($_FILES['file']['size'] > $MAX_SIZE) {
				$success = false;
				$error = 'Przekroczono dopuszczalny rozmiar pliku! (1MB)';
			} else {
				$folderPath = 'users' . DIRECTORY_SEPARATOR . $_COOKIE['username'] . DIRECTORY_SEPARATOR;
						
				if(isset($_GET['folder'])) {
					$folderPath .= $_GET['folder'] . DIRECTORY_SEPARATOR;
				}
	
				if(!is_dir($folderPath)) {
					mkdir($folderPath, 0777, true);
				}
		
				$filePath = $folderPath . $_FILES['file']['name'];
				
				if(file_exists($filePath)) {
					$success = false;
					$error = 'Taki plik już istnieje w tym folderze!';
				} else {
					move_uploaded_file($tempName, $filePath);
					$success = true;
				}
			}
		} else {
			$success = false;
			$error = 'Wystąpił błąd przy przesyłaniu danych!';
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
				<a href="index.php<?php echo isset($_GET['folder']) ? '?folder=' . $_GET['folder'] : ''; ?>"><button>Wróć</button></a>
			</div>
			
			<?php if(isset($success)): ?>
				<br /><br />
				
				<div>
					<?php if($success): ?>
						Dodano plik
					<?php else: ?>
						<?php echo $error; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<br /><br />
			
			<form method="POST" enctype="multipart/form-data">
				Wskaż plik:
				<br />
				<input type="file" name="file" />
				<br /><br />
				<button type="submit">Dodaj</button>
			</form>
		</div>
	</body>
</html>