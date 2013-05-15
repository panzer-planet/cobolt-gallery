cobolt-gallery
==============
Simple, configurable and easy to deploy php photo gallery

requires:
php5
php5-gd
mysql-server
jQuery 1.9+

Welcome.

I was not satisfied with other open source php gallery software, so I decided to write my own. cobolt-gallery is designed to be highly configurable, very easy to install and use and of course ensures that images are always displayed at top quality!

Features:

-Upload all popular image formats.

-Ultra simple installation. Simply change the database settings in settings/db_settings.php to match yours, upload to web server, navigate to prepare/index.php (then delete it for security) and lastly add include(/cobolt-gallery/cobolt_gallery.php); in your php document where you want the gallery to appear and you're ready to upload images, create users and view!

-Automatic image re-factoring. Images are automatically copied resized and stored to improve download times and ensure no quality loss when showing thumbnails or page sized images. You can also set the size of these images on the admin page to ensure they fit into your site perfectly!

-Set max file upload size in admin panel.

-Security prevents code injection and upload of unsupported or malicious file types.

-Log in as the admin or create limited accounts for upload only and give as many people upload rights as you need! (partially implemented)

-Administrate users by viewing logs, deleting their uploads or accounts as the admin. (not yet implemented)

-Change thumbnail layout. Supports 1 x 1 to 5 x 5 at the moment.

-Upload multiple files using the .zip archive format (not yet implemented).

-Disk space usage analyser (not yet implemented)

-Sort images into folders (not yet implemented)

-Delete images and edit image info while logged in simply by clicking the edit button(not yet implemented)

