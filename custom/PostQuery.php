<?php

namespace cw\wp\custom;

class PostQuery{
  public $args;

  public function __construct($args=[]){
    $this->args = $args;
  }

  public function postsPerPage($input){
    $this->args['posts_per_page'] = $input;
    return $this;
  }

  public function limit($limit){
    return $this->postsPerPage($limit);
  }

  public function offset($input){
    $this->args['offset'] = $input;
    return $this;
  }

  public function category($input){
    $this->args['category'] = $input;
    return $this;
  }

  public function custom($key, $value){
    $this->args[$key] = $value;
    return $this;
  }

  public function categoryName($input){
    $this->args['category_name'] = $input;
    return $this;
  }

  public function orderBy($input){
    $this->args['orderby'] = $input;
    return $this;
  }

  public function order($input){
    $this->args['order'] = $input;
    return $this;
  }

  public function includeIds($input){
    $this->args['include'] = $input;
    return $this;
  }

  public function excludeIds($input){
    $this->args['exclude'] = $input;
    return $this;
  }

  public function metaKey($input){
    $this->args['meta_key'] = $input;
    return $this;
  }

  public function metaValue($input){
    $this->args['meta_value'] = $input;
    return $this;
  }

  public function type($input){
    $this->args['post_type'] = $input;
    return $this;
  }

  public function mimeType($input){
    $this->args['post_mime_type'] = $input;
    return $this;
  }

  public function parent($input){
    $this->args['post_parent'] = $input;
    return $this;
  }

  public function author($input){
    $this->args['author'] = $input;
    return $this;
  }

  public function authorName($input){
    $this->args['author_name'] = $input;
    return $this;
  }

  public function status($input){
    $this->args['post_status'] = $input;
    return $this;
  }

  public function statusPublished(){
    return $this->status('publish');
  }

  public function suppressFilters($input){
    $this->args['suppress_filters'] = $input;
    return $this;
  }

  public function execute(){
    return get_posts($this->args);
  }
}
