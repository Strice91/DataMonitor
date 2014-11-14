<?php 
session_start(); 
?> 

<?php 
if(!isset($_SESSION["username"])) 
   	{ 
	?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Unbenanntes Dokument</title>
			</head>
			
			<body>
			
			<form action="login.php" method="post">
			Dein Username:<br>
			<input type="text" size="24" maxlength="50"
			name="username"><br><br>
			
			Dein Passwort:<br>
			<input type="password" size="24" maxlength="50"
			name="password"><br>
			
			<input type="submit" value="Login">
			</form>
			
			</body>
			</html>'; 
<?
   	} 
else
	{
			echo('Das ist ein Test');
	}
?> 

