<?php

namespace cw\wp\custom;

class MetaBox{
  public $id;
  public $title;
  public $screen       = null;
  public $context      = 'side';
  public $priority     = 'default';
  public $callback;
  public $callbackArgs = null;
  public $type         = 'html';
  public $typeOptionDefinition = [];
  public $default      = '';
  public $description   = '';

  public function __construct($id){
    $this->id = $id;
    add_action('add_meta_boxes', [$this, 'publish'] );
    add_action('save_post', [$this, 'save'], 10, 2 );
  }

  public function title($name){
    $this->title = $name;
    return $this;
  }

  public function description($description){
    $this->description = $description;
    return $this;
  }

  public function setDefault($value){
    $this->default = $value;
    return $this;
  }

  public function screen($which){
    $this->screen = $which;
    return $this;
  }

  // which could be post, page, link etc. or custom post type name
  public function forType($which){
    return $this->screen($which);
  }

  public function context($which){
    $this->context = $which;
    return $this;
  }

  public function contextNormal(){
    return $this->context('normal');
  }

  public function contextSide(){
    return $this->context('side');
  }

  public function contextAdvanced(){
    return $this->context('advanced');
  }

  public function priority($value='default'){
    $this->priority = $value;
    return $this;
  }

  public function priorityHigh(){
    return $this->priority('high');
  }

  public function priorityLow(){
    return $this->priority('low');
  }

  public function priorityCore(){
    return $this->priority('core');
  }

  public function typeHtml(){
    $this->type = 'html';
    return $this;
  }

  public function typeUpload(){
    $this->type = 'media';
    return $this;
  }

  public function typeText(){
    $this->type = 'text';
    return $this;
  }

  public function typeDate(){
    $this->type = 'date';
    return $this;
  }

  public function typeSelect($options){
    $this->type = 'select';
    $this->typeOptionDefinition = $options;
    return $this;
  }

  protected $inlineHtml;
  public function typeInlineHtml($input){
    $this->type = 'inline-html';
    $this->inlineHtml = $input;
    return $this;
  }

  public function metaId(){
    return 'meta_' . $this->id . '_content';
  }

  public function content($postOrId = null){
    if($postOrId === null){
      global $post;
      $postOrId = $post;
    }

    if(is_object($postOrId))
      $postOrId = $postOrId->ID;

    $content = get_post_meta($postOrId, $this->metaId(), true);

    if($content !== ''){
      if($this->type == 'date')
        $content = new \DateTime($content);

      return $content;
    }

    return $this->default;
  }

  public function contentAsImage($size=null){
    return wp_get_attachment_image_src($this->content(), $size);
  }

  public function show($post, $metabox){
    wp_nonce_field(plugin_basename(__FILE__), $this->metaId().'_nounce');

    $content = $this->content($post);
    if($this->description)
      echo '<p class="description">'.$this->description.'</p>';

    switch($this->type){
      case 'html':
        wp_editor($content, $this->metaId(), array(
          'wpautop'       => true,
          'media_buttons' => false,
          'textarea_name' => $this->metaId(),
          'textarea_rows' => 10,
          'teeny'         => true
        ) );
      break;
      case 'select':
        echo '<select name="'.$this->metaId().'">';
        foreach($this->typeOptionDefinition as $value => $name){
          $selected = ($content == $value) ? "selected='selected'" : '';
          echo "<option value='$value' $selected>$name</option>";
        }

        echo '</select>';
      break;
      case 'media':
        echo '<button type="button" class="button" id="'.$this->metaId().'_button" data-media-uploader-target="#'.$this->metaId().'">upload</button>';
        echo '<input type="hidden" id="'.$this->metaId().'" name="'.$this->metaId().'" value="'.$content.'">';
        echo '<a href="#" id="'.$this->metaId().'_button_remove" class="button" style="display:inline-block; float: right; padding-left: 10px">Löschen</a>';
        if($content)
          $image = $this->contentAsImage('50x50');
        else
          $image = [0 => 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='];

        echo '<img id="'.$this->metaId().'_image" style="width: 50px; height: auto; display: block;" src="'.$image[0].'"/>';

        add_action( 'admin_footer', function(){
          ?><script type="text/javascript">
            jQuery(function($){
              $('body').on('click', '#<?= $this->metaId().'_button' ?>', function(e){
                e.preventDefault();

            var button = $(this),
                custom_uploader = wp.media({
                  title: 'Insert image',
                  library : {
                    uploadedTo : wp.media.view.settings.post.id,
                    type : 'image'
                  },
                  button: {
                    text: 'Use this image' // button label text
                  },
                  multiple: false
                }).on('select', function() {
                  var attachment = custom_uploader.state().get('selection').first().toJSON();
                  $('#<?= $this->metaId().'_image' ?>').attr('src', attachment.url);
                  $('#<?= $this->metaId() ?>').val(attachment.id);
                })
                .open();
              });

              $('body').on('click', '#<?= $this->metaId().'_button_remove' ?>', function(){
                $('#<?= $this->metaId().'_image' ?>').attr('src', '#');
                $('#<?= $this->metaId() ?>').val('');
                return false;
              });
            });
          </script><?
        });

      break;
      case 'text':
      case 'date':
        if(is_a($content, 'DateTime'))
          $content = $content->format('Y-m-d');

        echo '<input type="'.$this->type.'" name="'.$this->metaId().'" value="'.$content.'">';
      break;
      case 'inline-html':
        if(is_callable($this->inlineHtml)){
          $tmp = $this->inlineHtml;
          echo $tmp();
        }else
          echo $this->inlineHtml;
      break;
    }
  }

  public function save($post_id, $post){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return ;

    if(!current_user_can('edit_page', $post_id))
       return ;

    if(!wp_verify_nonce($_POST[$this->metaId().'_nounce'], plugin_basename(__FILE__)))
      return ;

    if($_POST[$this->metaId()] == ''){
      delete_post_meta($post_id, $this->metaId());
      return ;
    }

    if($this->type != 'upload'){
      update_post_meta($post_id, $this->metaId(), $_POST[$this->metaId()]);
      return ;
    }

    if(empty($_FILES[$this->metaId()]['name']))
      return ;

    $upload = wp_upload_bits($_FILES[$this->metaId()]['name'], null, file_get_contents($_FILES[$this->metaId()]['tmp_name']));

    if(isset($upload['error']) && $upload['error'] != 0) {
      wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
      return ;
    }

    update_post_meta($post_id, $this->metaId(), $upload);
  }

  public function publish(){
    add_meta_box(
              $this->id,
              $this->title,
              [$this, 'show'],
              $this->screen,
              $this->context,
              $this->priority);

    return $this;
  }
}
