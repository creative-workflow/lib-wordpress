<?php

namespace cw\wp;

class Helper{
  public static function isLoginPage() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
  }

  public static function isAdmin() {
    return is_admin();
  }
}
