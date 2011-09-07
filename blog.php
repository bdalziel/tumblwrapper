<?php

require_once('template.php');
require_once('lib/TumblrBlog.php');
require_once('lib/TumblrPost.php');
require_once('lib/TumblrPostRenderer.php');
require_once('lib/TumblrBlogRenderer.php');

$getPostId   = htmlspecialchars($_GET["post_id"]);
$currentPage = htmlspecialchars($_GET["page"]);
if (!$currentPage) {
  $currentPage = 1;
}

$tBlog            = new TumblrBlog('bendalziel');
$blogRenderer     = new TumblrBlogRenderer($tBlog, 
					   (($currentPage) ? $currentPage : 1), 
					   (($getPostId) ? $getPostId : null));
$pageTitle        = $blogRenderer->getPageTitle();
$postsMarkup      = $blogRenderer->renderPosts();
$paginationMarkup = $blogRenderer->renderPagination();

$page_content = <<<HTML
  <section>
    <div class="page-header">
      <h1>{$pageTitle}</h1>
    </div>
    <div id="blog-container">
      <ul>
        {$postsMarkup}
      </ul>
    </div>
    {$paginationMarkup}
  </section>
HTML;

print render_page($pageTitle, strip_tags($pageTitle), 'blog', $page_content);

?>
