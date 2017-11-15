# creative-workflow/lib-wordpress

### Setup
```
git submodule add https://github.com/creative-workflow/lib-wp.git ./wordpress/wp-content/themes/child/lib/cw/wp
git submodule add https://github.com/creative-workflow/lib-php.git ./wordpress/wp-content/themes/child/lib/cw/php
git submodule add https://github.com/creative-workflow/lib-sass.git ./wordpress/wp-content/themes/child/lib/cw/sass
```

### functions.php
```php
<?php

define('CW_LIB_FOLDER',           __DIR__ . '/lib');
define('CW_DIVI_MODULES_FOLDER',  __DIR__ . '/modules');
define('CW_WP_SHORTCODES_FOLDER', __DIR__ . '/shortcodes');

foreach(glob(__DIR__ . '/initializers/*.php') as $file)
  require $file;

```

### initializers/
##### 01_autoload.php
```php
<?php

require CW_LIB_FOLDER.'/cw/php/core/Autoloader.php';

cw\php\core\Autoloader::registerNamespaceLoader(
                            CW_LIB_FOLDER,
                            CW_DIVI_MODULES_FOLDER
                          );


```

##### 03_options.php
```php
<?php

global $wpOptions;

$wpOptions = new \cw\wp\admin\Options('child_options');

$wpOptions->adminBarName('Page-Options')
             ->typeText('global_footer_post_id',
                        'ID des Footer-Posts (Divi-Bibliothek)')

             ->typePlain('color_info',
                         'Farbinfo',
                         '<div class="theme-color green">
                           <div class="color-monitor" style="background-color: #72ac4d"></div>
                           <b>hex:</b> #72ac4d <br>
                           <b>rgb:</b> rgba(114, 172, 77, 1)
                         </div>');

```

##### 04_assets.php
```php
<?php

global $wpOptions, $jQuery, $wpAssets;

$jQuery   = \cw\php\js\jQuery::getInstance();
$wpAssets = \cw\wp\Assets::getInstance();

$wpAssets->scripts()
            ->add('main-js', 'js/main.js', ['jquery'], 1, true)
            ->inline('main-js', $wpOptions->toJs())
            ->jqReady(
                $jQuery->getScript(
                  $wpAssets->expand('js/app/loader.js')
                )
              )
            ->replaceJquery('//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js')
            ->removeEmojiScript()
            ->remove(
              'google-maps-api',
              'divi-fitvids',
              'waypoints',
              'magnific-popup',
              'hashchange',
              'salvattore',
              'easypiechart',
              'magnific-popup',
              'wp-embed'
            );


$wpAssets->styles()
            ->addParent()
            ->addAdmin('eve-admin-css', 'admin.css')
            ->conditional(function($wpAssests){
              if(!current_user_can( 'update_core' )) // no admin
                $wpAssests->remove('dashicons');
            });

$wpAssets->theme()
            ->touchAfterPostUpdated() // can be used for browser-sync reload
            ->addFooterContent($jQuery); // when will render onReady content when __toString is called

```

##### 06_menu.php
```php
<?php
$childMenu = new \cw\wp\Menus();

$childMenu->addMenu('footer-1-menu')
          ->addMenu('footer-2-menu')
          ->addMenu('footer-3-menu');
```

##### 07_custom_post_types.php
```php
<?php

$postType = new \cw\wp\custom\PostType('job_post');
$postType->typePage()
         ->isPublic()
         ->slug('jobs')
         ->hasArchive(false)
         ->isHierarchical(false)
         ->isPubliclyQueryable()
         ->showInUi()
         ->showInMenu()
         ->supportsTitle()
         ->supportsEditor()
         ->supportsThumbnail()
         ->supportsRevisions()
         ->supportsPageAttributes()
         ->supportsPostFormats()
         ->menuPositionBelowPosts()
         ->name('Job-Board')
         ->singularName('Stellenanzeige')
         ->menuName('Job-Board')
         ->labelAddNew('Anzeige erstellen')
         ->adminBarName('Job-Board')
         ->addMetaBox(
             (new \cw\wp\custom\MetaBox('task'))
               ->title('Aufgaben')
               ->typeHtml()
         )
         ->publish();

$taxanomy = new \cw\wp\custom\Taxanomy('job_place');
$taxanomy->setObjectType($postType)
         ->isHierarchical(false)
         ->showInUi()
         ->showAdminColumn()
         ->queryVar(true)
         ->slug('place')
         ->name('Standorte')
         ->publish();

$taxanomy = new \cw\wp\custom\Taxanomy('job_category');
$taxanomy->setObjectType($postType)
         ->isHierarchical(false)
         ->showInUi()
         ->showAdminColumn()
         ->queryVar(true)
         ->slug('category')
         ->name('Kategorien')
         ->publish();

$taxanomy = new \cw\wp\custom\Taxanomy('job_position');
$taxanomy->setObjectType($postType)
         ->isHierarchical(false)
         ->showInUi()
         ->showAdminColumn()
         ->queryVar(true)
         ->slug('position')
         ->name('Positionen')
         ->publish();
```

##### hallo-world/css/module.sass
```sass
@import "variables"

@import "mixins/css/css3"
@import "mixins/css/positioning"
@import "mixins/helper/helper"

@import "mixins/grid/mediaqueries"
@import "mixins/grid/grid"

@import "mixins/wordpress/divi"
@import "mixins/wordpress/post"


+custom-divi-module('cw-module-hallo-world')
  .image
    display: none
    +min-width-sm
      +block
      +absolute
      right: -40px
      bottom: 0

  .content-wrapper
    [...]
```
