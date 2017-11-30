<?php

namespace cw\wp\admin;

class Dashboard{
  public static function addMainWidget($id, $name, $callback, $priotirty='core'){
    self::addWidget($id, $name, $callback, 'normal', $priotirty);
  }

  public static function addSideWidget($id, $name, $callback, $priotirty='core'){
    self::addWidget($id, $name, $callback, 'side', $priotirty);
  }

  public static function addWidget($id, $name, $callback, $where='normal', $priotirty='core'){
    add_action('wp_dashboard_setup', function() use($id, $name, $callback, $where, $priotirty){
      add_meta_box($id, $name, $callback, 'dashboard', $where, $priotirty);
    }, 1000);
  }

  public static function removeWidgets(){
    add_action('wp_dashboard_setup', function(){
      global $wp_meta_boxes;
      $wp_meta_boxes['dashboard']['side'] = ['core' => []];
      $wp_meta_boxes['dashboard']['normal'] = ['core' => []];
    }, 1000);
  }

  public static function replaceWelcomePanel($input){
    add_action( 'admin_footer', function() use($input){
      if(is_callable($input))
        $input = $input();

      $input = str_replace(array("\r", "\n"), '', $input);
      $input = addcslashes($input, '"');

      echo '<script>(function($){$("#welcome-panel").html("'.$input.'");})(jQuery);</script>';
    }, 1000);
  }

  public static function appendToWelcomePanel($input){
    add_action( 'admin_footer', function() use($input){
      if(is_callable($input))
        $input = $input();

      $input = str_replace(array("\r", "\n"), '', $input);
      $input = addcslashes($input, '"');

      echo '<script>(function($){$("#welcome-panel").append("'.$input.'");})(jQuery);</script>';
    }, 1000);
  }

  public static function hideWidgetArea(){
    add_action( 'admin_footer', function(){
      echo '<script>(function($){$("#dashboard-widgets-wrap").html("");})(jQuery);</script>';
    }, 101 );
  }
}
