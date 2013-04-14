<?php
	/**
	 * IconManager
	 *
	 * Use this class to change all the icons at once for theming
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
	require_once $_SERVER['DOCUMENT_ROOT'] . '/cobolt-gallery/settings/db_settings.php';
	 
	 class IconManager{
	 	private $mysqli;
	 	public $next_arrow, $previous_arrow, $admin_button, $view_thumbnails, $next_page, $previous_page;
		public $log_out;
		
		public function __construct($icon_set){
			//Retrieve database settings and connect
			$settings = new DbSettings();
			$this->mysqli = new mysqli($settings->db_host,
				$settings->db_user,
				$settings->db_password,
				$settings->db_name);
			if ($this->mysqli->connect_errno) {
				//Database connetion FAILED
				die ("Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error);
			}
			$icon_dir = "/cobolt-gallery/icons/{$icon_set}/";

			$this->next_arrow = $icon_dir . $this->get_icon($icon_set, 'next_arrow');
			$this->previous_arrow = $icon_dir . $this->get_icon($icon_set, 'previous_arrow');
			$this->admin_button = $icon_dir . $this->get_icon($icon_set, 'admin_button');
			$this->view_thumbnails = $icon_dir . $this->get_icon($icon_set, 'view_thumbnails');
			$this->previous_page = $icon_dir . $this->get_icon($icon_set, 'previous_page');
			$this->next_page = $icon_dir . $this->get_icon($icon_set, 'next_page');
			$this->log_out = $icon_dir . $this->get_icon($icon_set, 'log_out');
		}
		
		private function get_icon($icon_set, $icon_name){
			$sql = "SELECT icon_filename FROM icons WHERE icon_set = '{$icon_set}' AND icon_name = '{$icon_name}'";
			$result = $this->mysqli->query($sql);
			if($result->num_rows != 1){
				echo "something went wrong";
				return false;
			}
			$row = $result->fetch_assoc();
			return $row['icon_filename'];
		}
		
		
	 }
?>	