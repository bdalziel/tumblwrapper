<?php

class TumblrPost {

  protected $id;
  protected $date;
  protected $title;
  protected $body;

  public function __construct ($post) {
    $this->id    = $post['id'];
    $this->date  = $post['timestamp'];
    $this->title = $post['title'];
    $this->body  = $post['body'];
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

  public function toHtml ($h = 3) {
    return <<<HTML
<h{$h}>{$this->getTitle()}. <small><a href="{$this->getUrl()}">{$this->getFormattedDate()}</a></small></h{$h}>
{$this->getBody()}
HTML;
  }

}

?>
