<?php

namespace cw\wp;

class Assets{
  use \cw\php\core\traits\Singleton;

  public function theme(){
    return Theme::getInstance();
  }

  public function scripts(){
    return assets\Scripts::getInstance();
  }

  public function styles(){
    return assets\Styles::getInstance();
  }

  public function expand($uri){
    if(strpos($uri, '://') !== false
    || strpos($uri, '//')  === 0)
      return $uri;

    return get_stylesheet_directory_uri() . '/' . $uri;
  }


  private $published = false;

  // for detecting whether to load default assets or not
  public function published($set=null){
    if($set === null)
      return $this->published;

    $this->published = !!$set;

    return $this;
  }
}
