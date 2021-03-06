<?

namespace cw\wp;

class Assets{
  use \cw\php\core\traits\Singleton;

  protected $_view;

  public function theme(){
    return Theme::getInstance();
  }

  public function scripts(){
    return assets\Scripts::getInstance();
  }

  public function styles(){
    return assets\Styles::getInstance();
  }

  public function view(\cw\wp\View $view = null){
    if($view === null)
      return $this->_view;

    $this->_view = $view;
    return $this;
  }

  public function expand($uri){
    if(strpos($uri, '://') !== false
    || strpos($uri, '//')  === 0)
      return $uri;

    if($this->version)
      $uri = str_replace('{{version}}', $this->version, $uri);

    return get_stylesheet_directory_uri() . '/' . $uri;
  }

  public function expandPath($uri){
    if(strpos($uri, '://') !== false
    || strpos($uri, '//')  === 0)
      return $uri;

    if($this->version)
      $uri = str_replace('{{version}}', $this->version, $uri);

    return get_stylesheet_directory() . '/' . $uri;
  }

  function getContent($relativePathOrUri){
    return file_get_contents($this->expandPath($relativePathOrUri));
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

  private $version = null;
  public function version($set=null){
    if($set === null)
      return $this->version;

    $this->version = $set;

    return $this;
  }
}
