<?php
	/**
	 * CoboltGallery
	 *
	 * Include this file where you want the gallery to be place. This is the class the user
	 * and browser interacts with and is similar to a view in MVC.
	 *
	 * ---------------------------------------------------------------------------
	 * cobolt-gallery : Simple, configurable and easy to deploy php photo gallery 
	 * http://code.google.com/p/cobolt-gallery/
	 * Copyright 2012, Werner Roets and the cobolt-gallery contributors
	 *
	 * Licensed under GNU General Public License v3
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @author		  Werner Roets (cobolt.exe@gmail.com)
	 * @copyright     Copyright 2012, Werner Roets and the cobolt-gallery contributors
	 * @link          http://code.google.com/p/cobolt-gallery/
	 * @version		  0.1beta
	 * @since         0.1alpha
	 * @license       GNU GPL v3 (http://www.gnu.org/copyleft/gpl.html)
	 */
	require_once 'gallery_components.php';
	
	class CoboltGallery{
		//Contains the GalleryComponents class
		private $component;
		//Contains the html for <div id="cobolt-gallery_header">
		private $header_html = "";

		//Constructor
		function __construct(){
			$this->component = new GalleryComponents();
			$this->run();
		}
		
		//Start the gallery
		function run(){
			//Start the session
			
			$action = null;//changed from '' to null ditto below
			$content = null;
			//If action is not set we take you to the home page of the gallery
			if(isset($_REQUEST['action'])){
				$action = $_REQUEST['action'];
			}else{
				//Homepage
				$action = 'gallery_home';
			}
			if(!isset($_SESSION['loggedin'])){
				$_SESSION['loggedin'] = false;
			}
			
			//The action tells us what to do
			switch($action){
				
				//?action=view
				case 'view':
					//$view_mode = '';
					if(isset($_GET['view_mode']) && isset($_GET['id'])){
						$this->render($this->component->make_header($action,''),
									$this->component->show_image($_GET['id'],$_GET['view_mode']));	
						
					}else{
						//some query string missing...
						echo 'query string missing';
					}
				break;
				
				//?action=admin
				case 'admin':
					$this->render($this->component->make_header($action,''),
									$this->component->show_admin($_SESSION['loggedin']));			
				break;
				
				//?action=login
				case 'login':
					if(isset($_POST['password'])){
						#if(sha1($_POST['password']) == "48181acd22b3edaebc8a447868a7df7ce629920a"){
						if($_POST['password'] == '123'){
							if(!isset($_SESSION)){
								
							}
							
							$_SESSION['loggedin'] = true;
							$action = 'admin';
							
							$this->render($this->component->make_header($action,'Logged in successfully'),
									$this->component->show_admin($_SESSION['loggedin']));
									
						}else{
							$this->render($this->component->make_header($action, 'Log in details incorrect'),
									$this->component->show_admin($_SESSION['loggedin']));
						}
					}
				break;
				
				//?action=logout
				case 'logout':
					$_SESSION['loggedin'] = false;
					$action = 'gallery_home';
					$this->render($this->component->make_header($action,'You logged out'),
									$this->component->show_home(0));
				break;
				
				//?action=upload
				case 'upload':
					if(isset($_FILES['filename']) ){
						if($this->component->upload($_FILES['filename'])){
							//Show success and then back to admin page i rate
							$action = 'admin';
							$this->render($this->component->make_header($action,'File uploaded'),
										$this->component->show_admin($_SESSION['loggedin']));
						}
						else{
							$this->render($this->component->make_header($action,'An error occured while uploading the file'),
										$this->component->show_admin($_SESSION['loggedin']));
						}
					}else{
						//Show error and delay redirect/link redirect to admin again to retry
						$this->render($this->component->make_header($action,'An error occured while uploading the file'),
										$this->component->show_admin($_SESSION['loggedin']));
					}
					
				break;
				
				//?action=save_settings
				case 'save_settings':
					/*$x_thumbs = $_REQUEST['x_thumbs'];
					$y_thumbs = $_REQUEST['y_thumbs'];*/
					
					if($this->component->save_settings() > 0){
						//unset($_REQUEST);
						$action = 'admin';
						$this->render($this->component->make_header($action,'Settings saved'),
									$this->component->show_admin($_SESSION['loggedin']));
					}else{
						//Saving settings failed or nothing was changed
						$action = 'admin';
						$this->render($this->component->make_header($action,'No settings changed'),
									$this->component->show_admin($_SESSION['loggedin']));
					}
				break;
				
				//?action=gallery_home
				case 'gallery_home':
					//Home
					$page = 0;
					if(isset($_GET['page'])){
						$page = $_GET['page'];
					}
					$this->render($this->component->make_header($action,''),
									$this->component->show_home($page));
				break;
				
				default:
					//unknown action
					echo "unknown action";
				break;
			}
		}
		
		//Creates the appropriate header based on the action
		/*function make_header($action,$message){
			$header_html = "<div id='cobolt-gallery_header'>";
			if($action == 'admin'){
				if($_SESSION['loggedin'] == 'true'){
					$header_html .= "<a class='gallery_logout_button' href='" . $_SERVER['PHP_SELF'] . "?action=logout'>Logout</a>";
				}
				$header_html .= "<a href='" .$_SERVER['PHP_SELF'] . "?action=gallery_home'>View Gallery</a>";
			}else{
				$header_html .= "<a href='" . $_SERVER['PHP_SELF'] . "?action=admin'>Admin</a>";
			}
			$header_html .= "<span class='cobolt-gallery_message'>{$message}</span><hr/>";
			$header_html .= "</div>";
			return $header_html;
		}*/
		//Renders our view for the user
		function render($header_html, $content){
			echo "<div id='cobolt-gallery'>";
			echo $header_html;
			echo "<div id='cobolt-gallery_content'>";
			echo $content;
			echo "</div>
			</div>";
		}
	}
	//session_start();	
	$gallery = new CoboltGallery();
?>
