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


  // for detecting whether to load default assets or not
  private $published = false;
  public function published($set=null){
    if($set === null)
      return $this->published;

    $this->published = !!$set;

    return $this;
  }

  protected $defaultCallable;
  public function setDefault($callable){
    $this->defaultCallable = $callable;
  }

  public function publishDefault(){
    if(!is_callable($this->defaultCallable))
      return ;

    $tmp = $this->defaultCallable;
    $tmp($this);
  }
}
