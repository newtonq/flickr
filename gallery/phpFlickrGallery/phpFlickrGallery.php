<?php

  include("mdp3_flickr.php");
  
/**
 *
 * @param string $username flickr user to show gallery for
 * @param int $amount_per_page amount of photos per page, try 12
 * @param string $apiKey optional - api key for flickr
 * 
 * Shows a gallery page of photos for username using ?page= in url for nav 
 * uses css div classes gallery, gallerynav, and imagebox.
 * API Key is optional if none is specified, the default code for 
 * Flickr app Simple php Gallery is used. 
 * 
 * http://www.flickr.com/services/apps/72157623498770162/
 * http://www.mdp3.net/
 */
function showGallery($username, $amount_per_page, $apiKey = "f49647de8fbfded702f7dc1511b7da19")
{
  if (isset($_GET['flickr_page'])) $page = $_GET['flickr_page'];
  else $page = 1;
  
  $extras = "description";
  $flickr = new mdp3_flickr($apiKey,'8331d52183136e29');
  
  //caches username and id in session if they havent changed to cut down on flickr api calls
  session_start();
  if ($username == $_SESSION["flickr_username"])
    $userid = $_SESSION["flickr_userid"];
  else
  {
    $userid = $flickr->findByUsername($username);
    $_SESSION["flickr_username"] = $username;
    $_SESSION["flickr_userid"] = $userid;
  }
  $photos = $flickr->getPublicPhotosForUserNoAuth($userid, $amount_per_page, $page, $extras);
  $totalpages = $photos['pages'];
  $thispage = $_SERVER["PHP_SELF"];
  
  echo "<div class='flickr_gallery'>\n";
  //print_r($photos);
  
  echo "<div class='flickr_gallerynav'>";
  if ($page > 1)  //show prev and next buttons if needed
    echo "<a href='$thispage?flickr_page=" . (intval($page)-1) . "' id='flickr_prev'>Prev</a>\n";
  if ($page < $totalpages)
    echo " <a href='$thispage?flickr_page=" . (intval($page)+1) . "' id='flickr_next'>Next</a>\n";
  echo "</div><br>\n";
  
  //loop through images
  for ($i = 0; $i < count($photos['photo']); $i++)
  {
    echo "<div class='flickr_imagebox'>";
    $imgurl = $flickr->makePhotoImageURLfromArray($photos['photo'][$i], "m");
    $photo_id = $photos['photo'][$i]['id'];
    
    //pass the photo array thats already loaded, its alot faster than querying flickr for each photo page
    $imagepage = $flickr->makePhotoPageUrl($photo_id, $userid);
    $title = $photos['photo'][$i]['title'];
    $fulldescription = $photos['photo'][$i]['description']['_content'];
    $fulldescription = strip_tags($fulldescription);
    if (strlen($fulldescription) > 75)  //show only first 75 chars w/o tags
      $description = substr($fulldescription, 0, 75) . "...";
    else $description = $fulldescription;
    echo "<a href=\"$imagepage\">";
    echo "<img src=\"$imgurl\">";
    echo "</a>";
    echo "<h4>$title</h4>";
    echo "<p>$description</p>";
    echo "</div>\n";
  }
  echo "</div>\n";
  echo "<div class=\"flickr_clear\"></div>";
}
?>
