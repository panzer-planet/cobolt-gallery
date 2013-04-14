<?php
	/**
	 * DbSettings
	 *
	 * Enter your database settings here and this class will do the rest.
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
	class DbSettings{
		//Database settings
		public $db_host, $db_user, $db_name, $db_password;
		
		function __construct(){
			$this->db_host = 'localhost';
			$this->db_user = 'web';
			$this->db_name = 'cobolt_gallery';
			$this->db_password = 'web';
		}
	}
?>
