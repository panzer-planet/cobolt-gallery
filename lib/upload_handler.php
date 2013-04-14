<?php
	/**
	 * UploadHandler
	 *
	 * The upload handler class deals with files that need to be added to
	 * the gallery system. The upload handler ensures that only valid file
	 * extensions may be uploaded and catches common errors.
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
	 require_once 'lib/file_name_coder.php';
	 require_once 'lib/util.php';
	 
	class UploadHandler{
		//Holds the uploaded file array and desired types array once set
		private $uploaded_file, $desired_types;
		//Holds information on the file once save_file is complete
		private $filename, $tmp_name, $identified_type, $mime_type, $size;
		private $last_error = 'no error';
		
		/* You can add types here, even custom types
		 * just be sure to define their allowed extensions below
		 */
		private $supported_types = array ('archive', 'image','video');
		
		/* Type lists:
		 * These arrays hold the valid file extensions for each file type
		 * Please add more if you want.
		 * NOTE: I have chosen to do it like this instead of mimetype because people can more easily
		 * add file extensions than mime-types which have to be extracted or found online.
		 */
		private $archive = array('zip');
		private $image = array('png','jpg','jpeg','gif');
		private $video = array('mpeg', 'avi');
		private $document = array('txt','rtf','doc','docx','odf','ppt','xls');
		
		function __construct(){
			
		}
		
		/* set_allowed_types
		 * expects:
		 *  1: string array of desired types (see supported types)
		 * returns:
		 *  success (true/false)
		 * description:
		 *  allows the user of this class to set which types of files are allowed for upload
		 *  if this function is not set before save_file() then ALL FILES ARE ALLOWED 
		 */
		public function set_allowed_types($desired_types){
			$this->desired_types = $desired_types;
			if(!$this->validate_desired_types($this->desired_types)){
				$this->last_error = 'UploadHandler Error: One or more desired supported types are invalid<br/>
					Valid types include:<br/>';
				foreach($this->supported_types as $supported_type){
					$this->last_error .= "{$supported_type}<br/>";
				}
				return false;
			}
			return true;
		}
		
		/*save_file
		 * expects:
		 * 	1: $_FILE['filename']
		 *  2: string destination_dir
		 * returns:
		 *  hash of file information
		 * description:
		 *  Use this function to save the file uploaded by the user.
		 */
		public function save_file($uploaded_file,$destination_dir){
			//Not working. removing this seemed to fix a bug
			//Ensure the value passed through is a valid temp stored uploaded file
			/*if(!is_uploaded_file($uploaded_file['tmp_name'])){
				//If not valid store the error in last error	 
				$this->last_error = 'UploadHandler Error: This is not an uploaded file. File upload failed or something.';
				return false;	
			}*/
			//If the file is stored in tmp but another error occured
			if($uploaded_file['error'] > 0){
				//Make the error and return false identifying an error occured
				$this->make_file_error($uploaded_file['error']);
				return false;
			}
			
			//Make these variables properties to give private functions easy access to them
			$this->uploaded_file = $uploaded_file;
			$mime_type = $uploaded_file['type'];
			$this->filename = basename($this->uploaded_file['name']);
			$this->tmp_name = $this->uploaded_file['tmp_name'];
			$this->size = $this->uploaded_file['size'];
			
			//Ensure the user has first set the desired types else, allow all types
			if(!isset($this->desired_types)){
				//This will still prevent malicious upload types as they are not supported by default
				$this->desired_types = $this->supported_types;
			}
			/* Here we ensure that the file extension of the file matches the list of supported types.
			 * Although this is not protecting from malicious user, it will prevent morons etc. from uploading
			 * unsupported file types.
			 */
			if(!$this->valid_file($this->uploaded_file)){
				//We can explain to the programmer what went wrong like this
				$this->last_error = 'UploadHandler Error: One or more specified types not supported <br/>
				use UploadHandler::set_allowed_types(array $allowed_types).<br/>
				Valid types include:<br/>';
				foreach($this->supported_types as $supported_type){
					$this->last_error .= "{$supported_type}<br/>";
				}
				return false;
			}else{
				/* All the specified types were valid and on the supported list
				 * the uploaded file is on the desired list so we can process the
				 * file accordingly. We use make_unique_filename from 
				 */
				 $save_location = $destination_dir . make_unique_filename($this->filename);
				 if(!move_uploaded_file($this->tmp_name,Util::baseDir().$save_location)){
				 	$this->last_error = "UploadHandler Error: Failed to move uploaded file";
				 	return false;
				 }else{
				 	//Successfully stored in permanent location
				 	
				 	//$this->make_safe($save_location); //MUST IMPLEMENT TO AVOID CODE INJECTION
				 	return array ('file_loc' => $save_location,
									'file_type' => $this->identified_type,
									'mime_type' => $this->mime_type,
									'file_size' => $this->size);//filesize is in bytes
				 }
			}
			
		}
		
		//Returns the last error that occured
		public function show_error(){
			echo $this->last_error;
		}
		
		//This just inserts the standard file errors provided by php into our error variable
		private function make_file_error($errors){
			switch($errors){
				case 1:
					$this->last_error = "UploadHandler: File exceeded upload_max_filesize. Server php settings must be changed to resolve this";
					break;
				case 2:
					$this->last_error = "UploadHandler: File exceeded max_file_size";
					break;
				case 3:
					$this->last_error = "UploadHandler: File partially uploaded";
					break;
				case 4:
					$this->last_error = "UploadHandler: No file uploaded";
					break;
				case 6: 
					$this->last_error = "UploadHandler: No temp directory available";
					break;
				case 7:
					$this->last_error = "UploadHandler: Cannot write to disk";
					
			}
		}
		//This method may damage non-text files but removes malicious code injection attempts
		//Currently not implemented
		private function make_safe($file_location){
			$contents = file_get_contents($file_location);
			$contents = strip_tags($contents);
			file_put_contents($this->uploaded_file['name'], $contents);
			
		}
		/* Here we ensure that the list of desired types only
		 * includes defined types in this class
		 */
		private function validate_desired_types($desired_types){
			if(count($desired_types) > 0){
				foreach($desired_types as $type){
					if(!in_array($type, $this->supported_types)){						
						echo 'UploadHandler Error: ' . $type . ' is not a supported type definition';
						return false;
					}
				}
			}else{
				echo 'UploadHandler Error: Desired types array was empty $desired_types: ' . print_r($desired_types); 
				return false;
			}
			return true;
		}
		
		private function valid_file($f_uploaded){
			/* $name_ext[0] holds my_file
			 * $name_ext[1] holds txt
			 * where $f_uploaded was my_file.txt
			 */
			$f_lower = strtolower($f_uploaded['name']);
			$name_ext = explode('.',$f_lower);
			/* We check to make sure the extension of the uploaded file
			 * matches an entry in one of the type listsd
			 */
			$parts = count($name_ext);
			foreach($this->desired_types as $type){
				if(in_array($name_ext[$parts-1],$this->{$type})){
					//We have found the file extension in the specified list of extensions
					$this->identified_type = $name_ext[1];
					return true;
				}
			}
			$this->last_error = 'UploadHandler Error: Desired supported types not yet set<br/>
					use UploadHandler::set_allowed_types(array $allowed_types).<br/>
					Valid types include:<br/>';
			return false;
		}
		
	}

?>
