<?php

class TumblrPost {

  protected $post;

  protected $id;
  protected $date;
  protected $title;
  protected $body;

  public function __construct ($post) {
    $this->id    = $post['id'];
    $this->date  = $post['timestamp'];
    $this->title = $post['title'];
    $this->body  = $post['body'];

    $this->post  = $post;
  }

  public function getId () {
    return $this->id;
  }

  public function getDate () {
    return $this->date;
  }

  public function getFormattedDate () {
    $month_day = date("l F j", $this->date);
    $year = (date("Y", $this->date) !== date("Y")) ? date(", Y", $this->date) : '';
    $time = date("ga", $this->date);

    $date = $month_day . $year . ', ' . $time;
    return $date;
  }

  public function getTitle () {
    return $this->title;
  }

  public function getBody () {
    return $this->body;
  }

  public function getTags () {
    return $this->post['tags'];
  }

  public function toHtml ($h = 3) {
    $title = $this->getTitle();
    $date  = $this->getFormattedDate();
    $url   = '?post_id=' . $this->getId();
    $body  = $this->getBody();
    return <<<HTML
<h{$h}>{$title}. <small><a href="{$url}">{$date} &rarr;</a></small></h{$h}>
{$body}
HTML;
  }

  public function bodyToHtml ($h = 3) {
    $title = $this->getTitle();
    $date  = $this->getFormattedDate();
    $url   = '?post_id=' . $this->getId();
    $body  = $this->getBody();
    return <<<HTML
<h{$h}><small>{$date}</small></h{$h}>
{$body}
HTML;
  }

}

?>
