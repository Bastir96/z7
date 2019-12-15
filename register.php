<?php
	$flag = true; $passwords = true;
			
	if(isset($_COOKIE['username'])) {
		header("Location: index.php"); die();
	}
			
	if(is_array($_POST) && count($_POST) > 0) {
		$servername = "hosting1906292.online.pro";
		$username = "00262962_bazahaszczlab7";
		$password = "bazahaszcz123";
		$db = "00262962_bazahaszczlab7";

		$conn = mysqli_connect($servername, $username, $password, $db);
		mysqli_set_charset($conn, 'UTF8');
		
		$sqlCheck = "SELECT COUNT(*) as `exists` FROM users WHERE username LIKE '%s';";
		$queryCheck = sprintf($sqlCheck, $_POST['username']);
		$result = mysqli_query($conn, $queryCheck);

		if($_POST['password'] != $_POST['password2']) {
			$passwords = false;
		} else {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					if($row['exists'] > 0) {
						$flag = false;
					}
				}
			}

			if($flag) {
				$sqlAdd = "INSERT INTO users (username, password) VALUES ('%s', '%s');";
				$queryAdd = sprintf($sqlAdd, $_POST['username'], hash_hmac('sha256', $_POST['password'], 'utp@@rlz'));
				mysqli_query($conn, $queryAdd);
				
				$userPath = 'cloud' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $_POST['username'] . DIRECTORY_SEPARATOR;
				
				if(!is_dir($userPath)) {
					mkdir($userPath, 0777, true);
				}
				
				header("Location: index.php?register=true");
			}
		}
				
		mysqli_close($conn);
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
			<?php if(!$passwords): ?>
				<div>
					<div>Błąd!</div>
					<div>Hasła nie są takie same!</div>
				</div>
			<?php endif; ?>

			<?php if(!$flag): ?>
				<div>
					<div>Błąd!</div>
					<div>Użytkownik o podanej nazwie istnieje!</div>
				</div>
			<?php endif; ?>
	
			<form method="POST">
				<h2>Rejestracja</h2>

				<div>
					<label>Nazwa użytkownika</label><br />
					<input type="text" value="" name="username" required="required" />
				</div>

				<div>
					<label>Hasło</label><br />
					<input type="password" value="" name="password" required="required" />
				</div>

				<div>
					<label>Powtórz hasło</label><br />
					<input type="password" value="" name="password2" required="required" />
				</div>
					
				<br />
					
				<div style="display: inline;" class="row">
					<button type="submit">Zarejestruj</button>
				</div>
			</form>
		</div>
	</body>
</html>
