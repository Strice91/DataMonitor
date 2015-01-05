<?php
//Namespace der Klasse
namespace System;

/**
* Die Option Klasse
*
*/
 
class Message
{
	/**
	*	Erstellt einen Bearbeiten Button
	*
	*	@param int ID des zu bearbeitenden Eintrags
	*
	*	@param varchar Tabelle in der etwas geändert wird
	*/
	
	public function __construct($user, $title, $content, $time, $date)
	{
		$this->DB = $GLOBALS['DB'];
		$this->user = $user;
		$this->title = $title;
		$this->content = $content;
		$this->time = $time;
		$this->date = $date;
	}
	
	public function save()
	{
		$sql = "INSERT INTO `messages` (`user`, `title`, `content`, `time`, `date`) 
			VALUES ('".$this->user."','".$this->title."','".$this->content."','".$this->time."','".$this->date."');";
		$this->DB->query($sql);	
	}
	
	public function send()
	{
		
		require_once 'Mail/PHPMailerAutoload.php';
 
		$results_messages = array();
		 
		//Create a new PHPMailer instance
		$mail = new \PHPMailer(true);
		$mail->CharSet = 'utf-8';
		 
		//class phpmailerAppException extends phpmailerException {}
		 
		try {
		$to = 'stefan.roehrl@tum.de';
		if(!\PHPMailer::validateAddress($to)) {
		  throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
		}
		$mail->isSMTP();
		$mail->SMTPDebug  = 2;
		$mail->Host       = "smtp.strato.de";
		$mail->Port       = "587";
		$mail->SMTPSecure = "tls";
		$mail->SMTPAuth   = true;
		$mail->Username   = "stefan.roehrl@rh00.de";
		$mail->Password   = "ihmpv13.9";
		$mail->addReplyTo("stefan.roehrl@rh00.de", "Strice Roehrl");
		$mail->From       = "stefan.roehrl@rh00.de";
		$mail->FromName   = "Strice Roehrl";
		$mail->addAddress("helmut.roehrl@rh00.de", "Helmut Röhrl");
		$mail->Subject  = $this->title;
		$body = $this->generateBody();
		$mail->WordWrap = 78;
		$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
		
		
		try {
		  $mail->send();
		  $results_messages[] = "Message has been sent using SMTP";
		}
		catch (phpmailerException $e) {
		  throw new phpmailerAppException('Unable to send to: ' . $to. ': '.$e->getMessage());
		}
		}
		catch (phpmailerAppException $e) {
		  $results_messages[] = $e->errorMessage();
		}
		
		
		// Show Connection Details
		/* 
		if (count($results_messages) > 0) {
		  echo "<h2>Run results</h2>\n";
		  echo "<ul>\n";
		foreach ($results_messages as $result) {
		  echo "<li>$result</li>\n";
		}
		echo "</ul>\n";
		}*/

	}
	
	private function generateBody(){
		
		$sql = "SELECT * FROM `users` WHERE `ID`='".$this->user."'";
		$userData = $this->DB->query_first($sql);
		$userData = $userData[0];
		$username = $userData['prename']." ".$userData['surname'];
		$body = "Neue Nachricht von ".$username.":</br>";
		$body .= "</br>";
		$body .= "<h3>### ".$this->title." ###</h3>";
		$body .= "</br>";
		$body .= $this->content;
		$body .= "</br>";
		$body .= "</br>";
		$body .= "-----------------------------------------------</br>";
		$body .= "Datum: ".date('d.m.Y',$this->date)."</br>";
		$body .= "Stunden: ".$this->time."</br>";
		$body .= "Person: ".$username."</br>";
		$body .= "-----------------------------------------------</br>";
		$body .= "</br>";
		$body .= "Diese Email wurde Automatisch generiert.</br>";
		$body .= "Bitte nicht auf diese Email antworten.";
		
		echo $body;
		
		return $body;
	}
	
	
}