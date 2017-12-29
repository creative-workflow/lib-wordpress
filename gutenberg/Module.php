<?

namespace cw\wp\gutenberg;

class Module{
  protected $view   = null;
  protected $uri    = null;
  public    $path   = null;

  public function __construct($path = null){
    $this->path = $path;
    $this->uri  = '/modules/' . $this->moduleFolderName();
  }

  protected function moduleDisplayName(){
    $tmp = explode('-', $this->moduleFolderName());
    $tmp = array_map('ucfirst', $tmp);
    return implode(' ', $tmp);
  }
  protected function moduleFolderName(){
    return array_pop(explode('/', $this->path));
  }
}
