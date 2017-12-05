<?php

namespace cw\wp;

class Theme{
  use \cw\php\core\traits\Singleton;

  public $header='';
  public $footer='';

  public function touchAfterPostUpdated($file = 'functions.php'){
    if(!is_admin())
      return $this;

    $file = get_stylesheet_directory() . '/' . $file;

    add_action( 'post_updated', function() use($file){
      touch($file);
    }, 10, 3 );

    return $this;
  }

  public function addBodyClass($class){
    add_filter( 'body_class', function($classes) use($class){
      $classes[] = $class;
      return $classes;
    } );

    return $this;
  }

  function addFooterContent($input) {
    add_action( 'wp_footer', function() use($input){
      echo $input;
    }, 101 );
    return $this;
  }

  function addFooterContentJs($input) {
    $input = new \cw\php\js\expression\Wrapper($input);

    return $this->addFooterContent($input);
  }

  function addHeaderContent($input) {
    add_action( 'wp_head', function() use($input){
      echo $input;
    }, 101 );
  }

  function addHeaderContentJs($input) {
    $input = new \cw\php\js\expression\Wrapper($input);

    return $this->addHeaderContent($input);
  }

  public function pageTemplate(){
    return get_query_template('page');
  }

  public function postTemplate(){
    return get_query_template('single');
  }

  public function parentTemplate($which){
    return get_template_directory() . '/' .$which;
  }

  public function header($header=null){
    if($header === null)
      return $this->header;

    $this->header = $header;
    return $this;
  }

  public function footer($footer=null){
    if($footer === null)
      return $this->footer;

    $this->footer = $footer;
    return $this;
  }
}
