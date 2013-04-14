<?php
	/**
	 * Database preperation script
	 *
	 * Navigate to this script to prepare a datbase for use with CoboltGallery
	 * WARNING: If you do not delete this file after using it to configure the
	 * 			database, it could be used maliciously. This file should be deleted
	 * 			after use for security.
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
	require_once $_SERVER['DOCUMENT_ROOT'] . '/cobolt-gallery/lib/prepare_database.php';
	
	//Initialise the objects we make use of
	$settings = new DbSettings();
	$prepare = new PrepareDatabase(
		$settings->db_host,
		$settings->db_user,
		$settings->db_password,
		$settings->db_name
	);
	
	//This is how tables are made
	$prepare->make_table(
		"CREATE TABLE images(
		id INT NOT NULL AUTO_INCREMENT,
		fullsize_file_name VARCHAR(200) NOT NULL,
		thumbnail_file_name VARCHAR(200) NOT NULL,
		pagesize_file_name VARCHAR(200) NOT NULL,
		title VARCHAR(100) NULL,
		caption TEXT NULL,
		disk_usage VARCHAR(10) NOT NULL,		
		PRIMARY KEY(id))"
	);
	
	
	$prepare->make_table(
		"CREATE TABLE settings(
		id INT NOT NULL AUTO_INCREMENT,
		setting VARCHAR(50) NOT NULL,
		value VARCHAR(50) NOT NULL,
		PRIMARY KEY(id))"
	);
	
	$prepare->make_table("
		CREATE TABLE icons(
		id INT NOT NULL AUTO_INCREMENT,
		icon_set VARCHAR(25) NOT NULL,
		icon_filename VARCHAR(25) NOT NULL,
		icon_name VARCHAR(25) NOT NULL,
		PRIMARY KEY(id))");
	
	//These are the default(initial) settings for the gallery
	$settings_data = array(
		array('setting' => 'x_thumbs',
				'value' => '3'),
		array('setting' => 'y_thumbs',
				'value' => '3'),
		array('setting' => 'max_file_size',
				'value' => '200000'),
		array('setting' => 'thumbs_width',
				'value' => '150'),
		array('setting' => 'pagesize_width',
				'value' => '710'),
		array('setting' => 'image_storage_location',
				'value' => '/cobolt-gallery/gallery_images/')
		
	);
	$prepare->insert_settings($settings_data);
	
	$icon_data = array(
		array(  'icon_set' => 'glyph',
				'icon_name' => 'next_arrow',
				'icon_filename' => 'next_arrow.png'),
		array(  'icon_set' => 'glyph',
				'icon_name' => 'previous_arrow',
				'icon_filename' => 'previous_arrow.png'),
		array(	'icon_set' => 'glyph',
				'icon_name' => 'admin_button',
				'icon_filename' => 'admin_button.png'),
		array(	'icon_set' => 'glyph',
				'icon_name' => 'view_thumbnails',
				'icon_filename' => 'view_thumbnails.png'),
		array(	'icon_set' => 'glyph',
				'icon_name' => 'previous_page',
				'icon_filename' => 'previous_page.png'),
		array(	'icon_set' => 'glyph',
				'icon_name' => 'next_page',
				'icon_filename' => 'next_page.png'),
		array(	'icon_set' => 'glyph',
				'icon_name' => 'log_out',
				'icon_filename' => 'log_out.png')
		);	
	
	
	$prepare->insert_icons($icon_data);
        
        
	echo "<br/>Script finished. IF YOU ARE FINISHED WITH THIS SCRIPT DELETE IT FOR SECURITY!<br/>";
?>