<?php 

if (isset($_POST['submit'])) {
	
	include_once 'dbh.inc.php';

	$first = mysqli_real_escape_string($conn, $_POST['first']);
	$last = mysqli_real_escape_string($conn, $_POST['last']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error Handlers
	//Não há campos vazios
	if (empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd)) {

		header("Location: ../signup.php?signup=empty");
		exit();

	}else {

		//Vê se os caracteres do input são validos
		if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)) {

			header("Location: ../signup.php?signup=invalid");
			exit();

		} else {
			//Checa se o email é valido
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				
				header("Location: ../signup.php?signup=email");
				exit();

			} else {
				//Impede que a pessoa coloque um usuario existente
				$sql = "SELECT * FROM users WHERE user_uid='$uid'";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				
				if ($resultCheck > 0) {
					header("Location: ../signup.php?signup=empty");
					exit();
				
				} else {
					//Criptografando a senha
					$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

					//Colocar o usuário no banco de dados
					$sqlsend = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd');";

					mysqli_query($conn, $sqlsend);

					header("Location: ../signup.php?signup=success");
					exit();

				}

			}
		}
	}

}else{
	header("Location: ../signup.php");
	exit();
}