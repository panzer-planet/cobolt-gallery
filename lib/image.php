<?php
	/**
	 * Image
	 *
	 * Class used to encapsulate image data
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

	class Image{
		public $id, $fullsize_loc, $pagesize_loc, $thumbnail_loc, $title, $caption, $disk_usage;
		
		public function __construct($fullsize_loc,$pagesize_loc,$thumbnail_loc){
			$this->fullsize_loc = $fullsize_loc;
			$this->pagesize_loc = $pagesize_loc;
			$this->thumbnail_loc = $thumbnail_loc;
		}
		
	}
?>