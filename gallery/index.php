<?php
  include("phpFlickrGallery/phpFlickrGallery.php");

  $username = 'niveshsaharan';	//replace with any username
  $amount_per_page = 8;	//number of photos per page
?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="phpFlickrGallery/phpFlickrGallery.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Nivesh's Gallery</title>
  </head>
  <body>
    <? showGallery($username, $amount_per_page); ?>
  </body>
</html>
