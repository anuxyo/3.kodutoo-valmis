<?php
class User {
	
	private $connection;
	function __construct($mysqli) {
		
	//this viitab klassile (this == User)
		$this->connection = $mysqli;	
	}
	
	/*TEISED FUNKTSIOONI*/
	function signUp ($username, $email, $password, $age, $gender)	{
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample (username, email, password, age, gender) VALUES (?, ?, ?, ?, ?)");
		echo $this->connection->error;
		$stmt->bind_param("sssss", $username, $email, $password, $age, $gender);
		
		if($stmt->execute()) {
			echo "Success!";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		$stmt->close();
		$this->connection->close();
	}
	
	
	function login ($username, $password) {
		
		$error = "";

		$stmt = $this->connection->prepare("
		SELECT id, username, password, created 
		FROM user_sample
		WHERE username = ?");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $username);
		
		$stmt->bind_result($id, $usernameFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		if($stmt->fetch()){
			
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				//echo "Kasutaja logis sisse ".$id;
				
				$_SESSION["userId"] = $id;
				$_SESSION["userName"] = $usernameFromDb;
				
				$_SESSION["message"] = "<h1>Welcome!!</h1>";
				
				header("Location: books.php");
				exit();
				
			}else {
				$error = "Wrong password!";
			}
		} else {
			$error = "Username does not exist!";
		}
		return $error;
	}
}
?>