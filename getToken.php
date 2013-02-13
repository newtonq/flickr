<?php
    /* Last updated with phpFlickr 1.4
     *
     * If you need your app to always login with the same user (to see your private
     * photos or photosets, for example), you can use this file to login and get a
     * token assigned so that you can hard code the token to be used.  To use this
     * use the phpFlickr::setToken() function whenever you create an instance of 
     * the class.
     */

    require_once("phpFlickr.php");
    $apiKey = "a4840fc6baa541638018076693406ceb";
    $secret = "ec7a5957ac8a4e4b";
    $perms = "write";
    $f = new phpFlickr($apiKey, $secret);
    
    //change this to the permissions you will need
    if(!$_GET['frob']){
        $f->auth($perms);
    }else {
        $tokenArgs = $f->auth_getToken($_GET['frob']);
        echo "<pre>"; var_dump($tokenArgs); echo "</pre>";
    }
    
?>
