<?php
  //http://www.flickr.com/services/api/response.php.html

/**
 * Class: mdp3_flickr
 * 
 * Class used to query flickr api and can make galleries for output on a page
 * 
 * http://www.mdp3.net
 */
class mdp3_flickr
{
  var $apiKey;
  var $secret;
  
  /**
   *
   * @param string $apiKey your api key
   * @param string $secret your api secret key
   * 
   * Creates new object w/ api keys
   */
  function mdp3_flickr($apiKey, $secret = "")
  {
    $this->apiKey = $apiKey;
    $this->secret = $secret;
  }
  
  /**
   *
   * @param array $params params array for query url
   * @return array an array of strings from php unserialized api response
   * 
   * Queries flickr api with url built from params array for info see flickr API
   */
  private function query($params)
  {
    $encoded_params = array();

    foreach ($params as $k => $v)
      $encoded_params[] = urlencode($k).'='.urlencode($v);

    $url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

    $rsp = file_get_contents($url);
    $rsp_obj = unserialize($rsp);
    return $rsp_obj;
  }
  
  /**
   *
   * @param string $username username to search
   * @return string nsid for user
   * 
   * Searches flickr by username to get the userid or nsid number
   */
  public function findByUsername($username)
  {
    $params = array(
      'api_key'   => $this->apiKey,
      'method'    => 'flickr.people.findByUsername',
      'username'  => $username,
      'format'    => 'php_serial',
      );
    
    $rsp_obj = $this->query($params);
    
    if ($rsp_obj['stat'] == 'ok')
      return $rsp_obj['user']['nsid'];
    else
      return false;
  }
  
  /**
   *
   * @param string $photo_id
   * @return array result of photoinfo query
   * 
   * queries w/ info necessary to get photo info for a photo id number and 
   * returns the array of info
   */
  function loadPhotoInfo($photo_id)
  {
    $params = array(
    'api_key'	=> $this->apiKey,
    'method'	=> 'flickr.photos.getInfo',
    'photo_id'	=> $photo_id,
    'format'	=> 'php_serial',
    );
    
    $rsp_obj = $this->query($params);

    if ($rsp_obj['stat'] == 'ok')
      return $rsp_obj;
    else
      return false;
  }
  
  /**
   *
   * @param string $photo_id
   * @param array photoInfo photo info aray returned by load photo info
   * @return string url for photo page
   * 
   * if photoInfo is false or empty, it loads photo info for photo id, if not
   * it looks in the array and gets the url for the photo page from it
   */
  function getPhotoUrl($photo_id, $photoInfo = false)
  {
    if ($photoInfo) 
      $photo = $photoInfo;
    else
      $photo = $this->loadPhotoInfo($photo_id);
    $url = $photo['photo']['urls']['url'][0]['_content'];
    return $url;
  }

  /**
   *
   * @param <string> $photo_id flickr photo id
   * @param <string> $user_id flickr user id number
   * @return <string> photo page url
   * 
   * Simple function to create the photo page url with a photo id and user id
   */
  function makePhotoPageUrl($photo_id, $user_id)
  {
    $url = "http://www.flickr.com/photos/$user_id/$photo_id";
    return $url;
  }
  
  /**
   *
   * @param string $user_id user id nsid of user
   * @param int $per_page photos per page
   * @param int $page page number
   * @param string $extras extra fields to get see flickr api
   * @return array response array of photo info
   * 
   * Gets the public photos for user_id returns response array
   */
  function getPublicPhotosForUserNoAuth($user_id, $per_page = 100, $page = 1, $extras = "")
  {
    $params = array(
        'api_key'   => $this->apiKey,
        'method'    => 'flickr.people.getPublicPhotos',
        'user_id'   => $user_id,
        'per_page'  => $per_page,
        'page'      => $page,
        'extras'    => $extras,
        'format'    => 'php_serial',
        );
    
    $rsp_obj = $this->query($params);
    //print_r($rsp_obj['photos']['photo']);
    return $rsp_obj['photos'];
  }
  
  /**
   *
   * @param array $photoAr array of photo info
   * @param string $suffix flickr file suffix for image size to get
   * @return string url to image
   * 
   * Returns a url string to the img from an array of photo info from getPublicPhotos
   */
  function makePhotoImageURLfromArray($photoAr, $suffix = "")
  {
    $farmid = $photoAr['farm'];
    $serverid = $photoAr['server'];
    $photoid = $photoAr['id'];
    $secret = $photoAr['secret'];
    
    return $this->makePhotoImageURL($farmid, $serverid, $photoid, $secret, $suffix);
  }

  /**
   *
   * @param string $farmid 
   * @param string $serverid
   * @param string $photoid
   * @param string $secret
   * @param string $suffix
   * @param string $fileType
   * @return string url to photo 
   * 
   * Builds a flickr image url w/ all necessary parts
   */
  function makePhotoImageURL($farmid, $serverid, $photoid, $secret, $suffix = "", $fileType = "jpg")
  {
    if ($suffix != "")
      $suffix = "_" . $suffix;
    $url = "http://farm" . $farmid . ".static.flickr.com/" . $serverid . 
            "/" . $photoid . "_" . $secret . $suffix . "." . $fileType;
    return $url;
  }
}