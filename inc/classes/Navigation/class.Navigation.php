<?php 
//Namespace der Klasse 
namespace System; 

/** 
 * Navigation
 *  
 */ 
class Navigation
{
	/**
	*	Lädt die entsprechende Seite nach dem Request content
	*
	*/
	public static function getpage()
	{
		//Prüfe ob content gesetzt ist
		if(!isset($_REQUEST['content']))
		{	//Wenn nicht lade Startseite
			$content = 'home';
		}
		else
		{
			$content = $_REQUEST['content'];
		}
		
		//Wähle zu ladende Seite aus
		switch($content)
		{
			case'home':
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/home.php";
				break;	
			
			case'new':
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/new.php";
				break;
				
			case'overview':
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/overview.php";
				break;
				
			case'user':
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/user.php";
				break;
				
			case'userNew':
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/user_new.php";
				break;
				
			case'passEdit':
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/pass_edit.php";
				break;
				
			default:
				$page = PROJECT_DOCUMENT_ROOT."/inc/pages/home.php";
				break;
		}
		return $page;
	}
	
	
	/**
	*	Baut die Navigations links auf und Prüft, ob sie ausgewählt werden --> highliting
	*
	*	@param varchar Seite zu der Verlinkt wird
	*/
	
	public static function getregister($page)
	{
		//Prüfe ob content gesetzt ist
		if(!isset($_REQUEST['content']))
		{	//Wenn keine seite die alktuelle ist
			$content = '';
		}
		else
		{
			$content = $_REQUEST['content'];	
		}
		

		$is_current = "";
		//Prüfe ob der jetzt zuladende Link gerade angezeigt wird
		if($page == $content)
		{

			$is_current = "current";
			
		}
		//Setzte Link
		$registerContent = 'href="main.php?content='.$page.'" title="" class="'.$is_current.'"';
		
		return $registerContent;
	}
	
	/**
	*	Lädt die entsprechende Seite nach einem Dropdownmenü
	*
	*	@param verchar ausgewählter Parameter im Dropdownmenü
	*/
	/*
	public function refresh()
	{
		//Prüfe ob content gesetzt ist
		if(isset($_REQUEST['content']))
		{	
			if(isset($_POST['sel']))
			{
				$content = $_REQUEST['content'];	
				$select = $_POST['sel'];
				echo "<script type='text/javascript'>document.location.href='main.php?content=home&sel='".$select."';</script>";
				echo "huhu";
			}
			else
			{
					echo "hehe";
			}
		}
		else
		{//Wenn nicht lade Startseite
			echo "<script type='text/javascript'>document.location.href='main.php?content=home';</script>";	
		}
	}
	*/
}	
?>