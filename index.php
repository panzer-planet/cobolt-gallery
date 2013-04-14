<?php

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
		<script type="text/javascript"><!-- fix button value property change -->
			<!-- 
				function toggleSettings(id) {
					var e = document.getElementById(id);
					if(e.style.display == 'block') {
					} else {
					}
				}
				function toggleValue(id) {
					var e = document.getElementById(id);
					
					if(e.value == 'Show Advanced') {
						e.value = 'Hide Advanced';
					} else {
						e.value = 'Show Advanced';
					}
				}
			//-->
		</script>
	</head>
	<body>

		<?php include $_SERVER['DOCUMENT_ROOT'].'/cobolt-gallery/cobolt_gallery.php'; ?>

	</body>
</html>
