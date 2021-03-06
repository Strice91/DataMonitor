<?php 
// Namespace festlegen
namespace Scripts; 

class Login
{ 
     
   /** 
    * Stellt ein Login-Formular dar. 
    *  
    * @param varchar Pfad des Testskriptes für den Login 
    */
	
	public function printLoginForm($checkScript = null)
	{
		?>
        <section id="loginBox">
            <div style="float:left; width:300px;">
                <h2>Management</h2>
            </div>
            <div>
                <img src="images/style/logo.png"  />
            </div>
            <div>
                <form method="post" class="minimal" action="<?php echo $checkScript ?>" method="post">
                    <label for="username">
                        Username:
                        <input type="text" name="login" id="username" required="required" />
                    </label>
                    <label for="password">
                        Passwort:
                        <input type="password" name="password" id="password" required="required" />
                    </label>
                    <button type="submit" class="btn-minimal">Login</button>
                </form>
            <div>
		</section>
        
        <!--
   			 <fieldset style="padding:2px;width:180px;border:1px solid steelblue;"> 
   			 <legend>Login</legend> 
       			<form id="noSpaces" action="<?php echo $checkScript ?>" method="post"> 
         
      				Benutzername:<br /> 
      				<input type="text" class="standardField" name="login" size="20" maxLength="100"><br /> 
       				Passwort:<br /> 
       				<input type="password" class="standardField" name="password" size="20" maxLength="100"><br /> 
       				<input type="submit" onFocus="blur();" class="standardSubmit" name="doLogin" value="Anmelden"> 
       				<input type="reset" onFocus="blur();" class="standardSubmit" name="reset" value="Löschen"> 
       			</form> 
    		</fieldset>
          -->
        <?php
	}
	
	
	/** 
    * Prüft, ob eine korrekte Benutzername-Password-Kombination 
    * eingegeben wurde. 
    *  
    * @return boolean Gibt zurück, ob der Login erfolgreich war oder nicht 
    */
	
	public function checkLoginData()
	{
		//Erster Buchstabe des Benutzernamens
		$firstChar = substr($_POST['login'],0,1);
		
		//Datenbankabgleich
		$sql = "SELECT * FROM users WHERE login LIKE '".$firstChar."%'";
		
		// SQL string übergeben, Daten als array zurück fordern
		$result = $GLOBALS['DB']->query($sql);
		
		//Eingaben trimmen und führende Leerzeichen abschneiden
		$login = trim(substr($_POST['login'],0,100));
		$password = trim(substr($_POST['password'],0,100));
		
		if(!empty($result))
		{
			foreach($result as $data)
			{	
				// Prüfe Benutzername und Passwort
				if(($login == $data['login']) && (md5($password) == $data['password']))	
				{
					//Session_id neu setzen: gegen SESSION FIXATION
					session_regenerate_id();
					
					//Daten des Benutzers in die Session eintragen
					$_SESSION['login'] = $login;
					$_SESSION['userid'] = $data['ID'];
					$_SESSION['loggedInSince'] = date("d.m.Y H:i", time());
					$_SESSION['admin'] = $data['admin'];
					return true;
				}
			}
		}
		// Keine Übereinstimmung mit der Datenbank gefunden
		return false;
	}	
}
?>