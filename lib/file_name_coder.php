<?php
	/**
	 * file_name_coder.php
	 *
	 * Provides a single function: make_unique_filename($filename)
	 * making use of this function ensures that no two files have duplicate names so
	 * users will never upload over a file or experience those sort of issues. This also
	 * ensures that hackers will not know the url of a file they upload and therefore will
	 * find it harder to access. To ensure that duplicate files are not uploaded an md5 checksum
	 * can be used.
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
	 
	 function make_unique_filename($filename){
	 	//replace white spaces with nothing
	 	$filename = preg_replace('#[^a-zA-Z0-9]#', '', $filename);
		//return filename
	 	return substr(md5(rand (0 , 10000) . rand (0 , 10000) . $filename), rand(5,20), 35) . $filename;
	 }
?>
