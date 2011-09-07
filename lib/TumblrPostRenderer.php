<?php

class TumblrPostRenderer {

  protected $post;
  protected $isSinglePost;

  public function __construct ($post, $isSinglePost = true) {
    $this->post = $post;
    $this->isSinglePost = $isSinglePost;
  }

  public function render () {
    $postMarkup = (!$this->isSinglePost) ? $this->post->toHtml() : $this->post->bodyToHtml();
    $tags = $this->renderTags();
    $comments = $this->renderComments();
    $postMarkup = <<<HTML
        <li class="well">
          <div>
            {$postMarkup}
            {$tags}
            {$comments}
          </div>
        </li>
HTML;
    return $postMarkup;
  }

  public function renderComments () {
    $url = urlencode($this->getPostUrl());

    if ($this->isSinglePost) {
      $comments = <<<HTML
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:comments href="{$url}" num_posts="5" width="860"></fb:comments>
HTML;
    }
    else {
      $comments = <<<HTML
<iframe src="http://www.facebook.com/plugins/comments.php?href={$url}&permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:16px;" allowTransparency="true"></iframe>
HTML;
    }
    return $comments;
  }

  public function renderTags () {
    $tags = $this->post->getTags();
    $tagsMarkup = implode(' | ' , $tags);
    return <<<HTML
<h5>Tags: <small>{$tagsMarkup}</small></h5>
HTML;
  }

  protected function getPostUrl () {
    $url = "bendalziel.com/blog.php?post_id=" . $this->post->getId();
    return $url;
  }

}

?>
