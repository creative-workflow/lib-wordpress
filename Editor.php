<?php

namespace cw\wp;

class Editor{
  public static function disableAutomaticPAddition() {
    remove_filter ('the_content', 'wpautop');
  }
}
