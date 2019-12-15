<?php
	$servername = "hosting1906292.online.pro";
	$username = "00262962_bazahaszczlab7";
	$password = "bazahaszcz123";
	$db = "00262962_bazahaszczlab7";

	$conn = mysqli_connect($servername, $username, $password, $db);
	mysqli_set_charset($conn, 'UTF8');
	$error = false;
	$succes = false;
	$dmess = '';
	$username = $_COOKIE['username'];
			
	$query = "SELECT * FROM users WHERE username LIKE '%s';";
	$queryLogged = "INSERT INTO logs VALUES(null, '%d', CURRENT_TIMESTAMP, '%s', '%s', '%d')";
	$queryCheck = "SELECT COUNT(*) as invalid FROM `logs` WHERE user_id = '%d' and status = 0 and timestampdiff(minute, created_at, CURRENT_TIMESTAMP) < 10";

	if(isset($_COOKIE['username'])) {
		header("Location: cloud/index.php"); die();
	}
		
	if(is_array($_POST) && count($_POST) > 0) {
		$queryFilled = sprintf($query, $_POST['username']);
		$result = mysqli_query($conn, $queryFilled);
		$username = $_POST['username'];
		
		if(mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$check = mysqli_fetch_array(mysqli_query($conn, sprintf($queryCheck, $row['id'])));
				
				if($check['invalid'] >= 3) {
					$error = true;
					$dmess = "Konto zablokowane z powodu błędnych logowań. Odczekaj do 10 minut i spróbuj ponownie.";
				} else {
					$ipaddress = $_SERVER["REMOTE_ADDR"];
					$additional = $_SERVER['HTTP_USER_AGENT'];
						
					if($row['username'] == $username && $row['password'] == hash_hmac('sha256', $_POST['password'], 'utp@@rlz')) {
						setcookie('username', $username, time() + 3600);
						mysqli_query($conn, sprintf($queryLogged, $row['id'], $ipaddress, $additional, 1));

						header("Location: cloud/index.php"); die();
					} else {
						mysqli_query($conn, sprintf($queryLogged, $row['id'], $ipaddress, $additional, 0));
						
						$error = true;
						$dmess = "Podano nieprawidłowy login i / lub hasło!";
					}
				}
			}
		} else {
			$error = true;
			$dmess = "Podano nieprawidłowy login i / lub hasło!";
		}
	}
		
	mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	</head>

	<body>
		<div>		
			<?php if($error): ?>
				<div>
					<div>Wystąpił błąd!</div>
					<div><?php echo $dmess; ?></div>
				</div>
			<?php elseif($_GET['register'] == true): ?>
				<div>
					<div>Sukces!</div>
					<div>Zostałeś zarejestrowany!</div>
				</div>
			<?php endif; ?>
					
			<form method="POST">
				<h2>Logowanie</h2>
				
				<div>
					<label>Nazwa użytkownika</label><br />
					<input type="text" value="" name="username" required="required" />
				</div>
								
				<div>
					<label>Hasło</label><br />
					<input type="password" value="" name="password" required="required" />
				</div>
							
				<br />
							
				<div style="display: inline;">
					<button type="submit">Zaloguj</button>
					<a href="register.php">Zarejestruj się</a>
				</div>
			</form>
		</div>
	</body>
</html>
