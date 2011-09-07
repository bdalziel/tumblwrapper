<?php

class TumblrBlogRenderer {

  const BLOG_PAGE_GET_PARAM = 'page';
  const BLOG_POST_ID_GET_PARAM = 'post_id';

  protected $blog;
  protected $currentPage;
  protected $postId;

  protected $isSinglePost = false;

  protected $posts;

  public function __construct (TumblrBlog $blog, $currentPage = 1, $postId = null) {
    $this->blog = $blog;
    $this->currentPage = $currentPage;
    if ($postId && is_numeric($postId)) {
      $this->isSinglePost = true;
      $this->postId = strval($postId);
    }
  }

  public function renderPosts () {
    $postsMarkup = '';
    $posts = $this->getPosts();
    foreach ($posts as $post) {
      $postRenderer = new TumblrPostRenderer($post, $this->isSinglePost);
      $postsMarkup .= $postRenderer->render();
    }
    return $postsMarkup;
  }

  public function getPageTitle () {
    $page_title = $this->blog->getBlogName();
    if ($this->isSinglePost) {
      $blogUrl = $this->blog->getUrl();
      foreach ($this->getPosts() as $post) {
	$page_title = $post->getTitle() . ". <small><a href=\"" . $blogUrl . "\">" . $this->blog->getBlogName() . " &rarr;</a></small>";
	break;
      }
    }
    return $page_title;
  }

  protected function getPosts () {
    if (!$this->posts) {
      try {
	if ($this->isSinglePost) {
	  $posts = $this->blog->getPost($this->postId);
	}
	else {
	  $posts = $this->blog->getPosts($this->currentPage);
	}
      }
      catch (Exception $e) {
	
      }
      $this->posts = $posts;
    }
    return $this->posts;
  }

  public function renderPagination () {
    $paginationMarkup = '';
    $pageCount = $this->blog->getPageCount();
    if ($pageCount > 1) {
      $pageTabs = $this->renderPaginationTabs($pageCount, $this->currentPage);

      $prevUrl = $this->getPageUrl((int) ($currentPage - 1));
      $nextUrl = $this->getPageUrl((int) ($currentPage + 1));
      $prevClasses = array('prev');
      $nextClasses = array('next');
      if ($currentPage == 1) {
	$prevClasses[] = 'disabled';
	$prevUrl = '#';
      }
      if ($currentPage == $pageCount) {
	$nextClasses[] = 'disabled';
	$nextUrl = '#';
      }
      $prevClasses= implode(' ', $prevClasses);
      $nextClasses= implode(' ', $nextClasses);
      $paginationMarkup .= <<<HTML
<div class="pagination">
  <ul>
    <li class="{$prevClasses}"><a href="{$prevUrl}">&larr; Previous</a></li>
    {$pageTabs}
    <li class="{$nextClasses}"><a href="{$nextUrl}">Next &rarr;</a></li>
  </ul>
</div>
HTML;
    }
    return $paginationMarkup;
  }

  protected function renderPaginationTabs ($pageCount, $currentPage) {
    $tabsMarkup = '';
    for ($i = 1; $i <= $pageCount; $i++) {
      $pageUrl = $this->getPageUrl($i);
      $tabClasses = array();
      if ($i == $currentPage) {
	$tabClasses[] = 'active';
	$pageUrl = '#';
      }
      $tabClasses = implode(' ', $tabClasses);
      $tabsMarkup .= <<<HTML
    <li class="{$tabClasses}"><a href="{$pageUrl}">{$i}</a></li>
HTML;
    }
    return $tabsMarkup;
  }

  protected function getPageUrl ($pageNumber) {
    return '/blog.php?' . self::BLOG_PAGE_GET_PARAM . '=' . $pageNumber;
  }

}

?>
