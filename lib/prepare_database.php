<?php
	/**
	 * PrepareDatabase
	 *
	 * This class is used for quick preperation of database tables, it supports
	 * creating tables and inserting data forr settings.
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
	class PrepareDatabase{
		
		//public $db_host, $db_user, $db_name, $db_password;
		private $mysqli;
		
		//Constructor
		function __construct($db_host,$db_user,$db_password,$db_name){
			$this->mysqli = new mysqli($db_host,$db_user,$db_password,$db_name);
		}
		//Here you can pass through a CREATE TABLE SQL statement
		function make_table($sql){
			if(!$this->mysqli->query($sql)){
				echo "Error making table: Errno: {$this->mysqli->errno} Error: {$this->mysqli->error}";
			}else{
				echo "The table was prepared successfully<br/>";
			}
		}
		
		//Here you can pass through some insertion data
		function insert_settings($settings_data){
			foreach($settings_data as $setting){
				$sql = "INSERT INTO settings (setting, value) VALUES('".$setting['setting']."','".$setting['value']."')";
				if(!$this->mysqli->query($sql)){
					echo "Error inserting data into table: Errno: {$this->mysqli->errno} Error: {$this->mysqli->error}";
				}else{
					echo "Data inserted successfully<br/>";
				}
			}

		}
		function insert_icons($icon_data){
			foreach($icon_data as $icon){
				$sql = "INSERT INTO icons (icon_set, icon_filename, icon_name) VALUES('".$icon['icon_set']."','".$icon['icon_filename']."','".$icon['icon_name']."')";
				if(!$this->mysqli->query($sql)){
					echo "Error inserting data into table: Errno: {$this->mysqli->errno} Error: {$this->mysqli->error}";
				}else{
					echo "Data inserted successfully<br/>";
				}
			}

		}
		
		
	}
?>