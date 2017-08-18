<?php 

	session_start();

	if (isset($_POST['submit'])) {
		
		include 'dbh.inc.php';

		$uid = mysqli_real_escape_string($conn, $_POST['uid']);
		$uid = mysqli_real_escape_string($conn, $_POST['pwd']);

		//Error handlers
		//Vê se os inputs estão vazios
		if (empty($uid) || empty($pwd)) {	
			header("Location: ../index.php?login=empty1");
			exit();

		} else {
			//Cria a query que será jogada no banco para achar o usuario digitado
			$sql = "SELECT * FROM users WHERE user_uid='$uid'";
			//Joga a query no banco
			$result = mysqli_query($conn, $sql);
			// Checa se algum resultado foi devolvido (pois o número de colunas da devolução da query será maior que zero)
			$resultCheck = mysqli_num_rows($result);

			//Caso não retorne resultado manda de volta para a tela inicial
			if ($resultCheck < 1) {
				header("Location: ../index.php?login=no_user_found");
				exit();
			

			} else{
				if ($row = mysqli_fetch_assoc($result)) {
					//Decriptografando senha
					$hashedPwdCheck = password_verify($pwd, $row['user_pwd']);

					if ($hashedPwdCheck == false) {
						header("Location: ../index.php?pass=error");
						exit();
					
					} elseif ($hashedPwdCheck == true){
						// Fazer o login do usuário no site
						//O $_SESSION abre uma seção em todas as paginas informando que o usuário está conectado
						$_SESSION['u_id'] = $row['user_id'];
						$_SESSION['u_first'] = $row['user_first'];
						$_SESSION['u_last'] = $row['user_last'];
						$_SESSION['u_email'] = $row['user_email'];
						$_SESSION['u_uid'] = $row['user_uid'];

						header("Location: ../index.php?login=success");
						exit();
					}

				}

			} 

		}






	} else {
		header("Location: ../index.php?login=error");
		exit();
	}