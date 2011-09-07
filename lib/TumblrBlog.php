<?php

require('lib/TumblrPost.php');

class TumblrBlog {

  const TUMBLR_API_HOST    = "http://api.tumblr.com";
  const TUMBLR_API_KEY     = "EonZSbH566lKrQIcXzx8W1Tt3DcQ59tJhcmRTdXsEnjhodX9k2";
  const TUMBLR_PAGE_SIZE   = 20;
  const TUMBLR_API_TIMEOUT = 5;

  protected $blogShortName;

  public function __construct ($blogShortName) {
    $this->blogShortName = $blogShortName;
  }

  public function getPost ($id) {
    $url = $this->getPostsUrl(null, $id);
    $response = $this->executeQuery($url);    
    return $this->parsePost(json_decode($response, true));
  }

  public function getPosts ($page = 1) {
    $url = $this->getPostsUrl($page);
    $response = $this->executeQuery($url);
    return $this->parsePosts(json_decode($response, true));
  }

  protected function parsePost ($data) {
    return new TumblrPost($data);
  }

  protected function parsePosts ($data) {
    $posts = array();
    if (array_key_exists('response', $data) && array_key_exists('posts', $data['response'])) {
      $data = $data['response']['posts'];
      foreach ($data as $post) {
	$post = $this->parsePost($post);
	if ($post) {
	  $posts[] = $post;
	}
      }
    }
    return $posts;
  }


  protected function getPostsUrl ($page = null, $id = null) {
    $apiUrl = implode('/', array($this->getBaseBlogApiUrl(),
				 'posts'));

    $urlParams = array_merge(array('api_key' => self::TUMBLR_API_KEY), 
			     (array) $this->getApiPageParams($page),
			     (array) $this->getApiPostIdParam($id));
    $urlParams = $this->paramsToString($urlParams);
    return implode('?', array($apiUrl, $urlParams));
  }

  protected function getApiPageParams ($page) {
    $params = array();
    if ($page > 1) {
      $params['offset'] = (int) ($page - 1)*self::TUMBLR_PAGE_SIZE;
    }
    return $params;
  }

  protected function getApiPostIdParam ($id) {
    $param = array();
    if ($id && is_numeric($id)) {
      $param['id'] = $id;
    }
    return $param;
  }

  protected function paramsToString ($params) {
    $urlParams = array();
    foreach ($params as $paramKey => $paramValue) {
      $urlParams[] = $paramKey . '=' . $paramValue;
    }
    return implode('&', $urlParams);    
  }

  protected function getBaseBlogApiUrl () {
    return implode('/', 
		   array(self::TUMBLR_API_HOST,
			 'v2',
			 'blog',
			 $this->getBaseHostName()));
  }

  protected function getBaseHostName () {
    return $blogShortName . 'tumblr.com';
  }

  protected function executeQuery ($query, $timeout = 10) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, self::TUMBLR_API_TIMEOUT);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);    

    // Todo: Throw exceptions
    if ($httpcode>=200 && $httpcode<300) {
      return $data;
    }
    return null;
  }

}

?>
