<?php

namespace cw\wp;


class User{
  public function __construct(){
  }

  public function name(){
    $current_user = wp_get_current_user();

    if(!empty($current_user->user_firstname))
      return $current_user->user_firstname ;

    if(!empty($current_user->display_name))
      return $current_user->display_name;

    return $current_user->user_login;
  }
}
