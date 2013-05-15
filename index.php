
<?php
session_start();

	/**
	 * Deployment example
	 *
	 * This example shows you how easy the gallery is to deploy. You can use
	 * the software stand alone by navigating to this page.
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
?>
<html>
	<head>
		<link rel='stylesheet' href='styles/default.css'/>
		<title>Cobolt galllery test</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
		</script>
	</head>
	<body>

		<?php include 'cobolt_gallery.php'; ?>

	</body>
</html>
