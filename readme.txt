**************************************************************************************************
CHANGELOG
**************************************************************************************************
23.8.46 21-09-22
- GIT Implementation
- Template Part Component
- Icon and text component -> font-size with both icon and image 
- icon shortcode load again for fallback compatibility

23.8.45 21-09-13
- Slider component has layout setting
- Icon and text component -> font-size just if icon element is selected

23.8.44 21-09-09
- Icon_And_Text Alineacion default
- wp_enqueue_style( 'fa-styles', $assets_url . '/assets/css/all.css', array(), THEME_VERSION); 
  removed in admin_stuff

23.8.43 21-09-08
- js pinned-block just if viewport > 768

23.8.42 21-09-07
- if IS_MULTILANGUAGE include idiomas shortocode
- #content min-height: 100vh removed

23.8.41 21-09-06
- Icon_And_Text Component hide icon on mobile

23.8.40 21-09-02
- Icon_And_Text_Widget dont load anymore
- button Icon in wysiwyg editor insert [i name="fa-icon"]
- icon shortcode dont load anymore
- Icon and text alignment changes

23.8.39 21-09-01
- admin scripts and styles always load from root theme

23.8.38 21-08-30
- Single Archive Page redirect to connected term/postyype
- admin-scripts.js enqueue 
- admin-styles correction: map autocomplete address z-index

23.8.37 21-08-28
- Remove Custom Fields Metabox for performance
- Remove Postypes from nav menus
- DISABLE_PAGE_HEADER_IN CONSTANT
- Megamenu Implementation

23.8.36 21-08-27
- Hide editor on post and pages, theme option: show_editor_in
- Reusable Component Single Preview, and show default page header

23.8.35 21-08-26
- Icon and text alignment changes, default=center
- editor styles load from parent in TEST_MODE
- Botones style formats dropdown
- $menu-breakpoint sass var
- header pointer-events: none -> removed
- changes in minipost searchresult and search.php
- PAGE_HEADER_TEXT_COLOR Constant
- removed inputs focus border color

23.8.34 21-08-25
- v23ToggleBox.js v '3.8.24'
- Accordion Component Changes -> css,php
- Columnas Component DO need margin settings (Columnas Internas ?)

23.8.33 21-08-23
- columnas.js and sass correction

23.8.32 21-08-21
- Commented: add_filter( 'the_content', 'mv23_filter_ptags_on_images' );
- Component Separador corrections

23.8.31 21-08-16
- Colorbox -> replace .zoom 

23.8.30 21-08-15
- footer_code action 
- Walker_Nav_Menu correction when is empty: if (is_array($args)) return $output;
- MENU_ITEM_DATA_LOCATIONS constant
- .accordion -> overflow: hidden; border-radius: 4px (?) commented
- Accordion Component View correction: data-desktoptemplate

23.8.29 21-08-14
- Columnas, Columnas Internas Components dont need margin settings
- Card &[style*='border-radius'] overflow:hidden
- Card no add-padding
- global "actions" components setting
- Card Component admin view modifications
- Pushpin implementation on columns
- Pahe Header dosnt load featured image anymore

23.8.28 21-08-13
- Seciones Reusables load "columnas internas"
- Icon and text position center

23.8.27 21-08-12
- Accordion Component can load "secciones reusables"
- define ('ITEMS_GRID', false);

23.8.26 21-08-09
- Columnas Internas Component dosnt have .componente class
- Icon and text -> Text and Icon
- .columnas-1 -> display: grid para evitar margin collapse y overflow:hidden
- define ('CONTENT_SLIDER', false);
- accordion component content -> page

23.8.25 21-08-05
- Segundo Intento de Optimizacion: wp_objects commnted in header, accordion y accordion options
- Page Header Modifications
- Implementation of Archive_Page Class
- Search Page Modifications

23.8.24 21-08-04
- Primer Intento de Optimizacion

23.8.23 21-08-02
- He borrado varios componentes: .... Este podria ser el fin del hombre araña

23.8.22 21-07-31
- header.php modifications: is_post_type_archive() function

23.8.21 21-07-30
- New column's width: 1/2 1/4 1/4 & 1/4 1/4 1/2
- Columnas Internas components delete empty array field & content tab open first 

23.8.20 21-07-26
- Column Componenent accepts 1 in quantity and video background
- Content slider Component dosnt load columnas simples component

23.8.19 21-07-25
- Refactorization of componentes fields to Repeater Groups
- set define ('WP_POST_REVISIONS', false); in wp-config.php

23.8.18 21-07-21
- css columnas -> .alignment-flex-end > div width:100%
- smooth-anchors.js
- slider component

23.8.17 21-07-20
- Improvement: Icon Settings + image

23.8.16 21-07-19
- Extender columnas laterales

23.8.15 21-07-13
- Row Component is re-added with all components using ini_set('memory_limit','900M'); 

23.8.14 21-07-06
- Row Component is re-added with simple components repeater 

23.8.13 21-07-01
- Fixed: Card setttings position
- Card Component Code'refactorization
- IS_MULTILANGUAGE constant used in footer options

23.8.12 21-06-30
- class .special-title-1 commented
- Improvement: Archive Page Template / options and shortcodes
- Implementation .page-numbers-wrapper in pagination, for ajax filter
- Header Options Container moved to Page Header Container

23.8.11 21-06-29
- Load Icon and Text Widget
- Improvenments: Header options, Page Header can be replaced in child
- Fixed: Card, Accordion and columns fields configuration errors
- Comments template load inside a page module
- Grid items -> components_margin default 0
- Improvements: style formats for tinymce can be edited in child theme

23.8.10 21-06-22
- merge_component_fields() function deleted 

23.8.9 21-06-21
- merge_component_fields() refactorized -> array_merge to array_push 

23.8.8 21-06-20
- Improvement: page header show featured image (with multiply) if page_header_bgi is empty
- Improvement: Constant UF_POSTTYPES

23.8.7 21-06-18
- Fixed "Allowed memory size...": clone $default_settings_order in accordion component

23.8.6 21-06-12
- Improvement: Child Theme can include custom components

23.8.5 21-06-11
- Improvement: Shadows Settings
- Standarization of code in common settings
- Standarization of settings options throught components

23.8.4 21-06-10
- New var: $components-margin
- Improvement: Border Settings

23.8.3 21-06-09
- Sub-Menu improvements: item color
- Header improvements: fixed header shadow, logo'size corrections
- Header improvements: new vars for fixed header background
- Header optipons improvements: replace logo
- Improvement: modify components'margin inside columnas-simples, items-grid and columnas-internas
- Improvement: columnas-simples allows 1 to 12 columns

23.8.2 21-06-08
- Menu improvements: icon, image, item background styles, hide label
- Header improvements: data-theme colors



**************************************************************************************************
NOTES
**************************************************************************************************
21-06-08
- Menu item not sending $_POST[ $data_key ] to Menu_Item'save method 
  verify this downloading original and test in differnte wp versions

21-06-10
- ->required( '/^[0-9]*$/gm', 'Please enter a number.' ) not working when is setted two times



**************************************************************************************************
TO-DO
**************************************************************************************************
- Megamenus
- add set_suffix to "number" for borders
- chequear responsive: columnas no shrink?
- revisar todas las funciones donde se usa hex to rgb function

- mover a theme specific de tierra viva:
$dark-fixed-header-color: $secondary-color
.header
  &__logo 
    margin-top: 10px
    img
      transition: 500ms all
      margin-bottom: -60px
      height: 150px
  &.fixed &__logo 
    margin-top: 0
    img
      height: 90px
      margin-bottom: 0
@media #{$medium-and-down}
  .header
    &.fixed &__logo img
      height: 50px
@media #{$small-and-down}
  .header
    &__logo img
      height: 80px
      margin-bottom: -40px

var $globos = $('#hacemos-grid-1 .globo');

        if ($globos.length > 0) {
            for (var i = 0; i < $globos.length; i++) {
                var bgc = $($globos[i]).css( "background-color" );
                var $newSpan = $("<span/>").attr("style", "border-color:"+bgc).addClass("indicator");
                $($globos[i]).append($newSpan);
            }
        } 

.column-add-border
  .columnas-3>div:not(:last-child)
    border-right: 1px solid
