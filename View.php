<?

namespace cw\wp;

class View{
  use \cw\php\view\html\traits\Html{
    image as protected _original_image;
  }

  public $viewPath;
  public $imagePath;

  public function __construct($viewPath ='template-parts',
                              $imagePath ='assets/images'){
    $this->viewPath = $viewPath;
    $this->imagePath = $imagePath;
  }

  public function render($template, $variation=null){
    get_template_part("$this->viewPath/$template", $variation);
  }

  // affects also public function img
  public function image($src, $options){
    if(strpos($src, '://') !== false
    || strpos($src, '//')  === 0)
      return $this->_original_image($src, $options);

    $src = implode('/',[
                          get_stylesheet_directory_uri(),
                          $this->imagePath,
                          $src
                        ]);

    return $this->_original_image($src, $options);
  }
}
