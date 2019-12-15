<?php 
	if(!isset($_COOKIE['username'])) {
		header("Location: ../index.php"); die();
	}
	
	function ip_details($ip) {
		$json = file_get_contents("http://ipinfo.io/{$ip}/geo");
		$details = json_decode($json);
		
		return $details;
	}
					
	$servername = "hosting1906292.online.pro";
	$username = "00262962_bazahaszczlab7";
	$password = "bazahaszcz123";
	$db = "00262962_bazahaszczlab7";

	$conn = mysqli_connect($servername, $username, $password, $db);
	mysqli_set_charset($conn, 'UTF8');

	$queryUserId = "SELECT id FROM users WHERE username = '%s' LIMIT 1;";
	$queryLastInvalidLogin = "SELECT * FROM logs WHERE user_id = '%d' AND status = 0 ORDER by created_at DESC LIMIT 1";

	$resultUserId = mysqli_query($conn, sprintf($queryUserId, $_COOKIE['username']));
	
	if(mysqli_num_rows($resultUserId) > 0) {
		$rowUserId = mysqli_fetch_array($resultUserId);
		$userId = $rowUserId['id'];
		
		$resultLastInvalidLogin = mysqli_query($conn, sprintf($queryLastInvalidLogin, $userId));

		if(mysqli_num_rows($resultLastInvalidLogin) > 0) {
			$lastInvalidLogin = mysqli_fetch_array($resultLastInvalidLogin);
			$details = ip_details($lastInvalidLogin['ip_address']);
		}
	}

	mysqli_close($conn);
	
	// deep into filesystem
	$userPath = 'users' . DIRECTORY_SEPARATOR . $_COOKIE['username'] . DIRECTORY_SEPARATOR;
						
	if(isset($_GET['folder'])) {
		$userPath .= $_GET['folder'];
	}
						
	$pathElements = explode(DIRECTORY_SEPARATOR, $userPath);
	array_shift($pathElements);
						
	if(!is_dir($userPath)) {
		mkdir($userPath, 0777, true);
	}
	
	$userFiles = array_diff(scandir($userPath), array('.', '..'));
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
			
			<?php if(isset($lastInvalidLogin)): ?>
				<div>
					<div>Wykryto błędne próby logowania na Twoje konto!</div>
					<div>
						Ostatnia z nich miała miejsce <?php echo $lastInvalidLogin['created_at']; ?> i wykonana była z następującego adresu IP: <?php echo $lastInvalidLogin['ip_address']; ?>  (<?php echo $details -> country; ?>, <?php echo $details -> region; ?>, <?php echo $details -> city; ?>, <?php echo $details -> loc; ?>)
					</div>
				</div>
				
				<br /><br />
			<?php endif; ?>
			
			<div>
				<a href="upload.php<?php echo isset($_GET['folder']) ? '?folder=' . $_GET['folder'] : ''; ?>"><button>Wgraj plik</button></a>
				<?php if(!isset($_GET['folder'])): ?>
					<a href="create_folder.php"><button>Utwórz katalog</button></a>
				<?php else: ?>
					<a href="index.php"><button>Cofnij</button></a>
				<?php endif; ?>
			</div>
			
			<br /><br />
			
			<?php if(isset($userFiles) && is_array($userFiles) && count($userFiles)): ?>
				<div style="display: flex; flex-direction: row;">
					<?php foreach($userFiles as $file): ?>
						<?php $isDir = is_dir($userPath . DIRECTORY_SEPARATOR . $file); ?>
						<div style="width: 96px; height: 96px; display: flex; align-content: center; text-align: center;">
						<?php if(!isset($_GET['folder']) || !$isDir): ?>
							<a <?php echo (!$idDir) ? 'target="_blank"' : ''; ?> href="<?php echo $isDir ? 'index.php?folder=' . $file : 'download.php?file=' . $file . (isset($_GET['folder']) ? '&folder=' . $_GET['folder'] : ''); ?>" title="<?php echo $isDir ? 'Otwórz' : 'Pobierz'; ?>">
						<?php endif; ?>	
								<div style="width: 64px; height: 64px; background: url('img/<?php echo $isDir ? 'folder.png' : 'file.png'; ?>');"></div>
								<?php echo $file; ?>
						<?php if(!isset($_GET['folder']) || !$isDir): ?>
							</a>
						<?php endif; ?>	
						</div>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<h2>BRAK PLIKÓW I FOLDERÓW</h2>
			<?php endif; ?>
		</div>
	</body>
</html>