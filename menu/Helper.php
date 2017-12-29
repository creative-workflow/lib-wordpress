<?

namespace cw\wp\menu;

class Helper{
  public static function parentFromCurrentMenuItem($menuId, $postId=null) {
    $currentMenuItem = self::currentMenuItem($menuId, $postId);
    if($currentMenuItem === null || !$currentMenuItem->menu_item_parent)
      return null;

    $menuItems = wp_get_nav_menu_items($menuId, [
       'posts_per_page' => -1,
       'page_id' => $currentMenuItem->menu_item_parent
    ]);

    return isset($menuItems[0]) ? $menuItems[0] : null;
  }

  public static function currentMenuItem($menuId, $postId=null) {
    global $post;
    if($postId === null)
      $postId = $post->ID;

    $menuItems = wp_get_nav_menu_items($menuId, [
       'posts_per_page' => -1,
       'meta_key' => '_menu_item_object_id',
       'meta_value' => $postId
    ]);

    if(!isset($menuItems[0]) )
      return null;

    // return child, if parent is also it self
    foreach($menuItems as $menuItem)
      if($menuItem->menu_item_parent)
        return $menuItem;

    return $menuItems[0];
  }
}
