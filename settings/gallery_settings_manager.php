<?php
	/**
	 * GallerySettings
	 *
	 * Similar to a model, this class is used to get and set the gallery settings.
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
	 
	 // document root only gets localhost or
	require_once $_SERVER['DOCUMENT_ROOT'] . '/cobolt-gallery/settings/db_settings.php';
	
	class GallerySettingsManager{
		
		private $mysqli;
		
		function __construct(){
			//Connect to database
			$settings = new DbSettings();
			$this->mysqli = new mysqli($settings->db_host,
				$settings->db_user,
				$settings->db_password,
				$settings->db_name);
			if ($this->mysqli->connect_errno) {
				//Database connetion FAILED
				die ("Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error);
			}
		}
		
		/*Change a setting in the database.
		 *expects 1: the name of the setting in the database
		 *		  2: the value to change the setting to
		 *returns: true/false (success)
		 */
		function change_setting($setting_name, $settings_value){
			$sql = "UPDATE settings SET value='{$settings_value}' WHERE setting='{$setting_name}'";
			$this->mysqli->query($sql);
			if($this->mysqli->error || $this->mysqli->errno){
				echo "Error changing gallery settings: " . $this->mysqli->error;
				return false; 
			}else if($this->mysqli->affected_rows == 0){
				return false;
				//Lets create it?
			}else{
				return true;
			}
			
		}
		
		/*Get the setting value in the database by specifying the setting name
		 * expects: setting name
		 * returns: setting value or error message
		 */ 
		function get_setting($setting_name){
			$sql = "SELECT value FROM settings WHERE setting = '{$setting_name}'";
			$result = $this->mysqli->query($sql);
			if($this->mysqli->error || $this->mysqli->errno){
				echo "Error geting gallery setting: " . $this->mysqli->error;
				return 'ERROR';
			}else{
				if($result->num_rows > 0){
					$row = $result->fetch_assoc();
				return $row['value'];
				}else{
					return "Error you specified an invalid or non-existing setting in GallerySettingsManager::getsetting()";
				}
				
			}
		}
	}
?>
