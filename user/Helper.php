<?php

namespace cw\wp\user;

class Helper{
  public static function name(){
    $current_user = wp_get_current_user();

    if(!empty($current_user->user_firstname))
      return $current_user->user_firstname.' '.$current_user->user_lastname ;

    if(!empty($current_user->display_name))
      return $current_user->display_name;

    return $current_user->user_login;
  }
}
