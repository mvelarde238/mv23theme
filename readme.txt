**************************************************************************************************
CHANGELOG
**************************************************************************************************
23.8.132 23-05-23
- Carrusel items max height
- Listing Comp: scrolltop setting implementation
- Pinned block setTimeout 1s

23.8.131 23-05-19
- Add .clearfix to wysiwyg format selector

23.8.130 23-05-05
- Improvement: Constant UF_TAXONOMIES

23.8.129 23-04-25
- Validate some vars on initial theme activation

23.8.128 23-03-09
- Transform vimeo urls in iframes

23.8.127 23-03-08
- Mail added to rrss
- (libs\uf\core\js\overlay.js) remove img tag from pop up title

23.8.126 23-03-07
- Add id to main content
- Add id to archive pages
- Listing Component: just show published posts

23.8.125 23-03-06
- Listing Component: offset setting implementation
- Include edit-components.php after Content_Layout initialization

23.8.124 23-03-04
- Use LISTING_%_GAP CONTANTS in Listing Component
- undefined key %_widths_4 correction

23.8.123 23-03-03
- fix scroll-animations: tween element could be the same as trigger element
- scroll-animations improvement: tween element can be inner or outer
- Image / Video Component: allow youtube video
- scroll-animations: new css animated properties: boxShadow, borderRadius, filter

23.8.122 23-02-21
- New Row settings: widths in mobile and tablet for 4 columns
- Search Result basic style

23.8.121 23-02-17
- Use Columnas Simples Comp. in Columnas Internas, just if needed

23.8.120 23-02-15
- New Content Slider Comp. feature: scroll to top
- Use MV23_GLOBALS.headerHeight in pinned blocks

23.8.120 23-02-08
- New Map Componente features: unit height and info window 

23.8.119 23-02-07
- Set loop to false in Slider de contenidos
- Blockquote css refactorization

23.8.118 23-02-06
- Load Columnas Simples in Columnas Internas
- print html instead of string in title

23.8.117 23-02-02
- fix scroll-animations to work with -correct- items

23.8.116 23-02-01
- Include Separardor component in page header content builder

23.8.115 23-01-24
- Listing component: order parameter

23.8.114 22-11-29
- apply width:25% to columns repeater

23.8.113 22-11-28
- general custom fields optimization
- CARD constant
- secciones reusables post type just work with modules
- more-components.php deleted, use edit-components.php
- PROGRESS_BAR and PROGRESS_CIRCLE constants
- fix scroll-animations -> trigger-element array key

23.8.112 22-11-25
- Include post template from child-theme if dosnt exist in parent-theme
- bug: var was missing
- bug: "array key dosnt exist"
- remove console.logs

23.8.111 22-11-23
- Turn off scroll animations in mobile

23.8.110 22-11-18
- Multiple Scroll Animation
- hide line breaks removed from global settings and moved to text editor

23.8.109 22-11-17
- Scroll Animation Settings
- Inner components should have layout1 as default layout
- added: css properties for animations
- megamenu not apply overflow hidden to body

23.8.108 22-10-28
- Modules default layout: layout2
- sass: blockquote class correction 

23.8.107 22-10-18
- video background standarization

23.8.106 22-10-17
- Image / Video Component: default video aspect ratio and autoplay

23.8.105 22-10-12
- Card Component: autoplay video
- vars validation
- Page Module: video background

23.8.104 22-09-01
- v23ToggleBox.js version 6.8.23

23.8.104 30-08-06
- Correction: $page_header->print_slider()

23.8.103 22-08-06
- v23ToggleBox.js version '5.8.27'

23.8.103 22-08-06
- Accordion Component Item: layout content

23.8.102 22-08-06
- Content Layout Class
- vars validation

23.8.101 22-08-05
- correction: Icono & Text show main color 

23.8.100 22-08-04
- Page Title video background
- Columnas Simples item flex-grow
- PAGE_HEADER_CONTENT_BUILDER
- custom_page_header_options
- Page Header print methods
- Listing Compo. $list_template 'carrusel' corrections

23.8.99 22-08-03
- WhatsApp settings in rrss options
- MOBILE_MENU_LOGO Constant
- pinned block script moved to window load

23.8.98 22-07-13
- Accordion Comp. accent color
- template sidebars --> pinned

23.8.97 22-07-12
- post_listing_header() pluggable function

23.8.96 22-07-11
- Content Blocks Editor use componentes reusables
- DISABLE_PAGE_HEADER_IN in woocommerce support
- pluggable functions in woocommerce support

23.8.95 22-07-07
- MODAL_OUT_DURATION Constant

23.8.94 22-07-06
- Carrusel and Listing integration: (+gutter +autoplay)

23.8.93 22-07-05
- custom_page_headers()

23.8.92 22-06-08
- color-picker-enhancement.js load form parent theme
- correction: loading.gif path

23.8.91 22-06-01
- wp_get_active_network_plugins() not working
- Only show woo shortcode when needed

23.8.90 22-05-31
- WooCommerce Support

23.8.88 22-05-27
- COLOR_PICKER_PALETTES CONSTANT use theme main colors
-----------------------------------------------------------------------
- !IMPORTANT: SRC FOLDER STRUCTURE REFACTORIZATION 
-----------------------------------------------------------------------

23.8.87 22-05-25
- listing component: check if first array item is empty
- .pdf-responsive class 
- editor-de-texto.sass

23.8.86 22-05-24
- Implementation of new grid in archive pages and listing component
- pinned block correction
- posts filter implementation in listing component
-----------------------------------------------------------------------
- Important changes in several archives (css clean up, unused files and functions )
-----------------------------------------------------------------------

23.8.85 22-05-23
- Icon and text component -> font-size always (line height problem correction)
- Map height corrections

23.8.84 22-05-10
- CF7_USE_EMAIL_TEMPLATE implementation: error corrections

23.8.84 22-05-10
- IMAGE_THUMB_SIZE constant implementation
- CF7_USE_EMAIL_TEMPLATE constant implementation
-----------------------------------------------------------------------
- !IMPORTANT child themes need to include:
require_once( get_template_directory() . '/inc/functions/utils.php' );
-----------------------------------------------------------------------

23.8.83 22-05-09
- Using / for division outside of calc() is deprecated
- MOBILE_MENU_ [POSITION, WIDTH] constants implementation

23.8.82 22-05-06
- Page Header in 404 page

23.8.81 22-04-29
- Page Settings Metabox apply to PAGE_SETTINGS_POSTTYPES

23.8.80 22-04-28
- Theme My Login Multi Language Support

23.8.79 22-04-27
- (Floating Header Class) consider replace_logo meta 

23.8.78 22-04-20
- (core\classes\Options_Page.php) add_metaboxes hook third param 

23.8.77 22-04-14
- MV23_GLOBALS lang works with pll_current_language()
- Correction in oembed function: pattern dosnt start with whitespace \s

23.8.77 22-03-29
- Correction in oembed function (not convert links to iframe) 

23.8.76 22-03-28
- Accordion changes
- Accordion Comp. sanitize_title() for slug
- Oembed function refactorization to replace url with video in accordion

23.8.75 22-03-18
- facebook share data-hashtag correction

23.8.74 22-03-17
- video modal refactorization: background-color, load empty, empty on close, .zoom-video logic
- Image Component refactorization: select type: video / image

23.8.73 22-03-16
- Theme Optiones Custom Page and reorder theme utilities (CPTS)
- Show posts count in theme options CPT's

23.8.72 22-03-15
- \inc\partials\minipost.php -> thumbnail size: medium

23.8.71 22-03-14
- Icon and text component -> font-size JUST with icon (apparently line height problem in images)

23.8.70 22-03-11
- .sub-menu hover just in header menu

23.8.69 22-01-26
- Map css Height to Min-Height

23.8.68 22-01-18
- WhatsApp - Telegram -> Theme Options

23.8.67 22-01-05
- Content Blocks Container
- Columnas Simples Responsive
- hide editor in product post type
- Theme Options show editor in product

23.8.66 21-11-23
- Accordion Component mobile_template

23.8.65 21-11-22
- Componente Reusable -> get_template_part() instead of get_template_directory()
- lib UF -> (js/container/group.js) localStorage instead os sessionStorage

23.8.64 21-11-19
- Icon_And_Text_Widget corrections: alpha setting and left-icon class
- Megamenu js correction: hide if hover on any item

23.8.63 21-11-17
- video-card.js correction in .zoom-video function 

23.8.62 21-11-13
- Accordion Component -> update js and open first tab implementation

23.8.61 21-11-09
- ultimate_fields_page_content() load in admin for ajax use

23.8.60 21-11-04
- Card Component content_type setting

23.8.59 21-11-02
- Page Header allow empty content when "contenido" is selected
- if LOGOS_QTY == 1 show main_logo in mobile menu
- Components Settings reduced to one tab
- Accordion Icon default to none

23.8.58 21-11-01
- Correction: DISABLE_PAGE_HEADER_IN wasnt working in single.php
- Page Module Visibility

23.8.57 21-10-31
- Image Component

23.8.56 21-10-30
- Modal: empty-on-close Callback

23.8.55 21-10-29
- replace all apply_filters('the_content', $content) by do_shortcode(wpautop($content))
- oembed function to convert youtube links in oembed
- sidebar right/left template

23.8.54 21-10-28
- Corrections in archive page to work with woo
- page class corection to get the correct id in header archive pages
- [posts] query object correction
- archive page exclude_from_search (listings) true
- no-image class in page-header if dosnt have background-img

23.8.53 21-10-27
- Map Component height
- Youtube show play icon
- HEADER_HEIGHT constant for anchors when header is fixed

23.8.52 21-10-22
- Header Class accepts $additional_classes
- floating header .js change in order to get the correct logo img 

23.8.51 21-10-21
- Archive Page Corrections in order to work for "post" posttype
- Page Header Class Implementation
- Accordion: string replace en for titles: (),

23.8.50 21-10-20
- Page Header container change title -> Page Title
- Page Settings Implementation
- Page Class Implementation
- Flickr added to rrss
- wp_body_open() hook implementation
- Header Refactorized
- minor changes: FLOATING_HEADER_BREAKPOINT, carrusel alignment, x-align-on class correction, ...

23.8.49 21-10-14
- HEADER_THEME default CONSTANT
- header.php -> 404 page inherits theme form blog page
- Accordion component tab_style switched to image_select

23.8.48 21-10-07
- Removed icon meta tag from header.php

23.8.47 21-09-23
- Columnas dont open in popup
- title_template configuration for icon and text & Text Editor
- Icon and Text Component -> options re-arranged
- Megamenu delay
- Component doesnt open when added

23.8.46 21-09-22
- GIT Implementation
- Template Part Component CONSTANT
- Icon and text component -> font-size with both icon and image 
- icon shortcode load again for fallback compatibility
- Layout settings load in default settings

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
- add set_suffix to "number" for borders
- chequear responsive: columnas no shrink?
- revisar todas las funciones donde se usa hex to rgb function