<?

namespace cw\wp\assets;

class Scripts{
  use \cw\php\core\traits\Singleton;

  public function add($handle, $uri=null, $dependencies = [], $version = 1, $inFooter = true, $priority=1){
    if($uri !== null)
      $uri = \cw\wp\Assets::getInstance()->expand($uri);

    add_action('wp_enqueue_scripts', function() use($handle, $uri, $dependencies, $version, $inFooter){
      wp_enqueue_script($handle, $uri, $dependencies, $version, $inFooter);
    }, $priority);

    return $this;
  }

  public function addAdmin($handle, $uri=null, $dependencies = [], $version = 1){
    if($uri !== null)
      $uri = \cw\wp\Assets::getInstance()->expand($uri);

    add_action('admin_enqueue_scripts', function() use($handle, $uri, $dependencies, $version){
      wp_enqueue_script($handle, $uri, $dependencies, $version);
    });

    return $this;
  }

  public function addFooter($handle, $uri, $dependencies = [], $version = 1, $priority=1){
    return $this->add($handle, $uri, $dependencies, $version, $inFooter = true, $priority);
  }

  public function inline($handle, $content = null, $position = 'after', $priority=1){
    if($content === null){
      add_action( 'wp_head', function() use($handle){
        echo "<script>$handle</script>\n";
      }, 0 );
    }
    else{
      add_action('wp_enqueue_scripts', function() use($handle, $content, $position){
        wp_add_inline_script($handle, $content, $position);
      }, $priority);
    }

    return $this;
  }

  public function remove($scripts = []){
    $scripts = func_get_args();

    add_action( 'wp_print_scripts', function() use($scripts){
      foreach($scripts as $script)
        wp_dequeue_script($script);
    }, 100 );

    add_action( 'init', function() use($scripts){
      foreach($scripts as $script)
        wp_deregister_script($script);
    });

    return $this;
  }

  public function jqReady(){
    if(!func_num_args())
      return \cw\php\js\jQuery::getInstance()->toJsWrapped();

    $args = func_get_args();

    \cw\php\js\jQuery::getInstance()->onReady(...$args);

    return $this;
  }

  function inspect() {
    add_action( 'wp_print_scripts', function(){
      global $wp_scripts;

      echo PHP_EOL . '<!-- Script Handles: ';
      echo implode(' || ', $wp_scripts->queue);
      echo ' -->'.PHP_EOL;
    }, 123);
  }

  public function replaceJquery($with){
    if(\cw\wp\Helper::isAdmin() || \cw\wp\Helper::isLoginPage())
      return $this;

    add_action('init', function() use($with){
      wp_deregister_script('jquery');
      wp_register_script('jquery', $with, false, false, true);
      wp_enqueue_script('jquery');
    });

    return $this;
  }

  public function removeEmojiScript(){
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );

    return $this;
  }

  public function conditional($callable){
    add_action('wp_enqueue_scripts', function() use($callable){
      call_user_func($callable, $this);
    });
  }

}
