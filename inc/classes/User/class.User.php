<?php
//Namespace der Klasse
namespace System;

/**
* Die User Klasse
*
*/
 
class User
{
	public function __construct($ID = 0, $login = "", $prename = "", $surname = "", $active = 0, $lastlogin = ""){
		if($ID == 0){
			$this->ID = $ID;
			$this->login = "";
			$this->prename = "";
			$this->surname = "";
			$this->acitve = 0;
			$this->lastlogin = "";
		}
		else{
			$this->ID = $ID;
			$this->getUserData();
		}
	}
	
	public function getUserData(){
		$DB = $GLOBALS['DB'];
		$sql = "SELECT * FROM `users` WHERE `ID`='".$this->ID."'";
		$data = $DB->query_first($sql);
		if(!empty($data)){
			//echo "<pre>";
			//print_r($data);
			//echo "</pre>";
			$this->login = $data['login'];
			$this->prename = $data['prename'];
			$this->surname = $data['surname'];
			$this->active = $data['active'];
			$this->lastlogin = $data['lastlogin'];
		}
		else{
			return false;
		}
	}
}
?>