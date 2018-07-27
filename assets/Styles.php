<?php

namespace cw\wp\assets;

class Styles{
  use \cw\php\core\traits\Singleton;

  public function add($handle, $uri=null, $dependencies = [], $version = 1){
    if($uri !== null)
      $uri = \cw\wp\Assets::getInstance()->expand($uri);

    add_action('wp_enqueue_scripts', function() use($handle, $uri, $dependencies, $version){
      wp_enqueue_style($handle, $uri, $dependencies, $version);
    });

    return $this;
  }

  public function addAdmin($handle, $uri=null, $dependencies = [], $version = 1){
    if($uri !== null)
      $uri = \cw\wp\Assets::getInstance()->expand($uri);

    add_action('admin_enqueue_scripts', function() use($handle, $uri, $dependencies, $version){
      wp_enqueue_style($handle, $uri, $dependencies, $version);
    });

    return $this;
  }

  public function addParent($style = 'style.css', $dependencies = [], $version = 1){
    $uri = get_template_directory_uri() . '/' . $style;

    $this->add('parent-theme', $uri, $dependencies, $version);

    return $this;
  }

  public function remove(){
    $styles = func_get_args();

    add_action( 'wp_print_styles', function() use($styles){
      foreach($styles as $style)
        wp_dequeue_style($style);
    }, 100 );

    return $this;
  }

  function inspect() {
    add_action( 'wp_print_scripts', function(){
      global $wp_styles;

      echo PHP_EOL . '<!-- Style Handles: ';
      echo implode(' || ', $wp_styles->queue);
      echo ' -->'.PHP_EOL;
    }, 123);

    return $this;
  }

  public function conditional($callable){
    add_action('wp_enqueue_scripts', function() use($callable){
      call_user_func($callable, $this);
    });

    return $this;
  }
}
