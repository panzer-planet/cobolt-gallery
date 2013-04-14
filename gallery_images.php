<?php
	/**
	 * GalleryImages
	 *
	 * Gallery resource and database interaction class.
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
	/**
	 * GalleryImages is used to retrieve and store database information about images.
	 *
	 * This class allows for several ways to get and set information about images
	 * to the correct database columns. Simply specify the filesystem location
	 * of all the required filesizes to add images. N.B This class does not
	 * add images to the server filesystem, it is merely used to store their location
	 * in the database.
	 */
	class GalleryImages{
		/**
		 * mysqli object
		 *
		 * @var mysqli
		 * @access private
		 */
		private $mysqli = null;
		/**
		 * Construct and set up GalleryImages for use of its methods.
		 * Die on fail.
		 */
		function __construct(){
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
		/**
		 * Add an image to the database by specificying the least information
		 * required (file locations). The image is then also automatically assigned
		 * an id in the database.
		 *
		 * @param string $fullsize_loc filesystem location of the full sized image
		 * @param string $pagesize_loc filesystem location of the page sized image
		 * @param string $thumbnail_loc filesystem location of the thumbnail sized image
		 * @return boolean true on success
		 */
		public function add_image($fullsize_loc, $pagesize_loc, $thumbnail_loc){
			$thumbnail_loc = basename($thumbnail_loc);
			$pagesize_loc = basename($pagesize_loc);
			$fullsize_loc = basename($fullsize_loc);
			$sql = "INSERT INTO images (fullsize_file_name, pagesize_file_name, thumbnail_file_name) VALUES ('{$fullsize_loc}', '{$pagesize_loc}', '{$thumbnail_loc}')";
			$this->mysqli->query($sql);
			if($this->mysqli->error || $this->mysqli->errno){
				echo "Error adding image to image table: " . $this->mysqli->error;
				return false; 
			}else{
				return true;
			}
		}
		/**
		 * Get an image by its id in the database
		 * 
		 * @param int id of the image
		 * @return array all details of the image or false if failed.
		 */
		public function get_image_by_id($id){
			$sql = "SELECT * FROM images WHERE id={$id}";
			$result = $this->mysqli->query($sql);
			if($this->mysqli->error || $this->mysqli->errno){
				echo "Error getting gallery image: " . $this->mysqli->error;
				return false;
			}else{
				if($result->num_rows > 0){
					$row = $result->fetch_assoc();
					return $row;
				}else{
					//no rows returned
					return false;
				}
				
			}
		}	
		
		/**
		 * Get the id of the next image in the database
		 * 
	 	 * @return id of next image in database
		 */
		 public function get_next_id_by_current($id){
		 	//Select less than this id because gallery shows newest first
		 	$sql = "SELECT id FROM images WHERE id < {$id}";
			$result = $this->mysqli->query($sql);
			if($result->num_rows < 1){
				return false;
			}
			$first = $result->num_rows-1;
			$row = $result->data_seek($first);
			$row = $result->fetch_assoc();
			return $row['id'];
		 }
		 
		/**
		 * Get the id of previous image in the database
		 * 
		 * @return id of previous image in database
		 */ 
		public function get_previous_id_by_current($id){
			//Select more than this id because gallery shows newest first
			$sql = "SELECT id FROM images WHERE id > {$id}";
			$result = $this->mysqli->query($sql);
			if($result->num_rows < 1){
				return false;
			}
			//echo "num rows {$result->num_rows}<br/>";
			$last = $result->num_rows-1;
			//echo "last $last <br/>";
			$row = $result->data_seek(0);
			$row = $result->fetch_assoc();
			return $row['id'];
		}
		
		/**
		 * Get all images in the database and all their details
		 * 
		 * @return array of arrays of image details
		 */
		 public function get_all_images(){
		 	$sql = "SELECT * FROM images ORDER BY id DESC";
			$result = $this->mysqli->query($sql);
			$all_images = array();
			if($this->mysqli->error || $this->mysqli->errno){
				echo "Error getting all gallery images";
				return false;
			}else{
				if($result->num_rows > 0){
					do{
						$row = $result->fetch_assoc();
						array_push($all_images,$row);
						
					}while($row);
					return $all_images;
				}else{
					//no rows returned hi
					return false;
				}
			}
		 }
	}
?>