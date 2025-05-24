**************************************************************************************************
CHANGELOG
**************************************************************************************************

2.2.2 25-05-24
- Allow 'Icon_And_Text' component classes to be added from another component definition
- Icon And Text feature: square style
- Action Hooks implementation in Template_Engine: 
  "after_component_wrapper_start", "before_component_wrapper_end"
- Remove yoyo from normalized properties in scroll-animations.js
- Improve Common Setting Control to show an indicator when has values
- Improve Common Setting Control to remove empty arrays from animation and actions settings

2.2.1 25-05-23
- UF Fix: Show common_settings_wrapper.settings.main_attributes.id in page_module group
- UF Fix: Use a prompt to paste settings between components if navigator is not available
- Feature: Implement overflow in Core\Builder\Animations\Properties

2.2.0 25-05-22
- Migrate timeline group to groups (!use migrator)
- UF fix: Use components library button just in components repeater

2.1.4 25-05-20
- css line height standarization

2.1.3 25-05-17
- Row settings implementation: 2/5 + 1/5 + 2/5 columns width
- Refactor Common Settings position
- Add toggle_actions setting to Scroll Animations
- Add animation_name to Scroll Animations
- Add rule_name to Page_Container group
- Add scaleX, scaleY to Scroll Animations
- Add toggle_class setting to Scroll Animations
- Image Comp. Feature: external image

2.1.2 25-05-16
- Fix scroll animations scripts to work with to() from() and fromTo()

2.1.1 25-05-12
- Use locate_template() to load Components to check for the class in the child theme
- filter_core_button_styles implementation

2.1.0 25-05-09
- GSAP Animations replace ScrollMagic (!use migrator)
- Special title styles wrapper apply to paragraphs
- Fix submit input styles: remove uppercase and add cursor pointer

2.0.6 25-04-25
- Add line height selector in style formats (tinymce)

2.0.5 25-04-24
- Add backcolor to tinyMCE
- Add font weight selector in style formats (tinymce)

2.0.4 25-04-15
- v23ToggleBox 5.8.36: Add delay option to ensure all elements inside are loaded

2.0.3 25-03-21
- Fix settings affected by internazionalization
- Group related migration with a horizontal bar

2.0.2 25-03-20
-----------------------------------------------------------------------------------------------------
- !IMPORTANT: Leaflet Maps Implementation
- ! Use theme-migrator to transform google maps into leaflet maps
- Added Constant: INITIAL_MAP_POSITION => array('lat' => ..., 'lng' => ..., 'zoom' => 12)
- A google api key is needed to load google maps
-----------------------------------------------------------------------------------------------------

2.0.1 25-03-15
-----------------------------------------------------------------------------------------------------
- !IMPORTANT: V.2.0.1: Columns Layout Field replace Column and Inner Column Component
- ! Use theme-migrator to transform columns into rows
- Removed Hook: before_adding_Columns_components
- Removed Hook: before_adding_Inner_Columns_components
- Added Hook: before_adding_Row_components
- Added Hook: before_adding_Inner_Row_components
-----------------------------------------------------------------------------------------------------

1.5.60 25-02-25
- Redirect if author is in the query string
- Avoid user enumeration from the REST API
- Fix undefined params in Manual Listing

1.5.59 25-02-20
- .full-height implementation in common settings
- Fix: Core\Builder::register_popup_containers() to load only on admin screens
- Core\Builder\Common_Settings removed

1.5.58 25-02-19
- PAGE_HEADER_COMPONENTS implementation
- check_slider_background() implementation in Page Module
- Fix responsive settings affected by internazionalization

1.5.57 25-02-14
- Add .qodo folder to .gitignore
- Reorder Listing component settings
- Register pop up containers in Builder Class
- Footer meta box implementation

1.5.56 25-02-13
- Listing structure (view and js) refactorization
- Listing component: Load more posts button icon

1.5.55 25-02-11
- Slider background implementation in common settings
- Common Settings Translation
- Lowers the metabox priority to 'core' for Yoast SEO's metabox

1.5.54 25-02-10
- Fix 2 in listing -default- argument name affected by internazionalization
- Fix Page header -default- argument name affected by internazionalization
- Translate -Leer Más- string

1.5.53 25-02-03
- Fix autoloader to work in childtheme
- Button Comp: add class from attributes settings in link

1.5.52 25-01-31
- Fix Archive_Page::get_archive_post_type() in is_post_type_archive page
- Fix toggle-box.js script: slideDown wasnt adding display block
- Use locate_template() in the autoloader to check for the class in the child theme

1.5.51 25-01-29
- Fix issue related to translation code in cpt admin list
- get_archive_post_type() function implementation in Archive_Page

1.5.50 25-01-23
- Refactorize IS_MULTILANGUAGE constant to avoid error on deactivating Polylang
- Fix listing -default- argument name affected by internazionalization

1.5.49 25-01-10
- Files internazionalization

1.5.48 25-01-09
- OCE Improvements: .sidenav max-width 360px, padding allow any value and better estructure for settings
- Create po - mo files and load text domain
- $theme_path and $theme_uri implementation in Theme_Header_Data

1.5.47 25-01-08
- ADJUST_SCROLL_POSITION constant implementation
- v23ToggleBox 5.8.35: Add _go_to_step() method
- Add styles for: .wpcf7-acceptance:has(.add-wrapper-styles)

1.5.46 25-01-05
- Fix remove_empty_paragraphs() function: dont remove p that hasAttributes to work with cf7

1.5.45 25-01-04
- v23ToggleBox 5.8.34: Add startIndex option to handle the initial active tab index
- --form-ui-color and --table-ui-color css vars implementation

1.5.44 25-01-03
- v23ToggleBox 5.8.33: Add multistep mode

1.5.43 25-01-02
- Fix missing filter index in Listing Component arguments
- remove_empty_paragraphs() js function implementation
- Allow usign default post postcard in cpt archive pages
- Show post type name in archive page header
- Components_Wrapper Component title named to Wrapper
- Components_Wrapper feature: restrict components by posttype
- filter_header_options implementation

1.5.42 25-01-01
- Reorder Button Component Settings
- UF Feature removed: Show filename in repeater group title preview

1.5.41 24-12-30
- Flip Box Comp: allow 'flip-box' component class to be added from another component definition

1.5.41 24-12-27
- --bold-font-weight implementation in Theme Options

1.5.40 24-12-25
- Fix .toggle-box funcionality after ajax refresh on listings
- Fix Listing Component: taxonomy not displaying in data attributte when tax filter is active
- Fix sharer.js to work after ajax refresh

1.5.39 24-12-19
- Horizontal Nav 4 styles implementation in Menu Component
- Implement --accent-colors in .menu-comp
- Fix pdf poster image root in gallery

1.5.38 24-12-18
- Carrusel Comp.: fix controls position

1.5.37 24-12-17
- Add outline to table in editor styles

1.5.36 24-12-12
- CONTENT_BUILDER_SETTINGS constant implementation

1.5.35 24-12-10
- Remove .container from .expander-inner

1.5.34 24-12-05
- Remove text transform uppercase from button
- Force containered page header

1.5.33 24-12-04
- Fix adjust scroll position script to take the header height value direct from the .header

1.5.32 24-11-28
- Move Theme_Migrator UI to a safer place
- Font url implementation in Theme Options - Typography

1.5.31 24-11-21
- lang_switcher shortcode implementation

1.5.30 24-11-18
- Sharer.js implementation

1.5.29 24-11-15
- Accordion CPT removed
- Remove background color from .uf-file-preview for png with white content
- Compile editor sass files
- Fix version nomenclature in common settings control
- Fix: --sticky-header-height starts in 0 to avoid .sticky elements "jumping"
- Fix: remove .header__content padding transition
- Fix: check if page isnt private before filtering the content

1.5.28 24-11-14
- Improve Common Setting Control Margin and Padding values to allow any unit
- Fix .column alignment
- Fix: allow extended background in mobile

1.5.27 24-11-13
- UF layout_control implementation
- data-touch implementation in carrusel and listing components

1.5.26 24-11-04
- Fix: adjutst scroll position in hash in url
- Fix youtube icon in redes sociales shortcode
- Add actions settings to Carrusel Item and use file uf_field type in open-image-popup action

1.5.25 24-10-29
- allow main content to be placed over page header
- Tiktok implementation in rrss

1.5.24 24-10-28
- Partially fix: OCE selector not working inside popup container
  Page header is loading an always hidden OCE selector

1.5.23 24-10-24
- Masonry need to be activated in theme options to be used
- Space around implementation in blocks layout
- mobile-1-1de2-1de2 column width implementation
- Fix layerslider arrows over sidenav
- Items gap implementation in Gallery Component
- v23ToggleBox 5.8.32: Fix togglebox inside togglebox
- Fix css styles for togglebox inside togglebox
- Fix Listing component is showing br separation when pagination is not needed
- fix table selects show behind popup

1.5.22 24-10-21
- MV23_GLOBALS.woocommerce_is_active implementation
- Add page header content type css class
- Use primary color in mail footer

1.5.21 24-10-17
- Fix: dont apply heading typography to b / strong tags
- Fix modal close in testimonial modal
- Feature: Theme Options Manager

1.5.20 24-10-08
- gap implementation in row setings
- remove line break from css tipography rules\n didnt worked in marine farm project
- add filter_style_formats hook

1.5.19 24-10-07
- Fix menu item white space
- Padding implementation in Offcanvas Elements

1.5.18 24-10-04
- Fix Header styles
- Core\Builder Namespace
- Core\Offcanvas_Elements Namespace
- Core\Migrator Namespace

1.5.17 24-09-26
- Toggle button implementation in WooCommerce product categories widget

1.5.16 24-09-24
- Implement open in popup in woocommerce postcard
- Improve get cart items quantity
- fix woo products width in mobile

1.5.15 24-09-23
- Shop header sidebar implementation

1.5.14 24-09-20
- Archive Pages CPT Refactorization
- Standarize postcard 1 y 2
- --static-header-height and --sticky-header-height implementation

1.5.13 24-09-19
- Feat: filter_tab_styles_for_accordion_component
- Fix: Remove !important from icon and text component
- Fix: Reorder Icon Comp. settings and implement horizontal alignment

1.5.12 24-09-18
- Flip Box Component Implementation

1.5.11 24-09-17
- Fix components spacing
- Get theme version from main theme
- Fix common settings object: border dependencies and placeholder in margin
- Implement aspect ratio in Image Component
- Implement aspect ratio in Video Component

1.5.10 24-09-17
- Remove font weight bolder property for b and strong in normalize
- Fix icon in Icon_And_Text Component
- Load all components in Carrusel Component
- Show placeholder if image is not selected in Image Component
- Fix customizer.js location

1.5.9 24-09-16
- BOOTSTRAP_ICONS Implementation
- Add title template to all components in blocks layout
- Implement full_width option on Image Component
- Move pages_settings to page_template_settings
- fitty.js lib removed

1.5.8 24-09-13
- Register post types on after_setup_theme to have a better compatibility with ultimate fields
- Exclude mv23_library_tax from page template settings

1.5.7 24-09-12
- Fix %shared-horizontal-navs-styles: removed max-width property
- MV23_GLOBALS.menu_breakpoint implementation for js
- Core\Theme_Options namespace

1.5.6 24-09-11
- Add b and strong to headings scope in typography settings
- Fix .horizontal-nav-1 item padding
- Remove button hover arrow effect
- Change --component-space-around-y from 15px to 8px
- New menu location: mobile-header-buttons
- Fix --component-space-around-x in mobile
- Implement visibility setting in all components

1.5.5 24-09-10
- Add support for custom fonts
- Fix video background size
- .content-layout__item 100% in mobile
- --sidebar-width css property
- Implement post title in single page
- Set sticky header transparent by default

1.5.4 24-09-09
- Change Menu Breakpoint from 1296px to 896px
- Fix .main-nav width 
- Nav Shortcode
- Minicart Shortcode

1.5.3 24-09-07
- Implement pages settings for single and archives in Theme Options
- Implement post formats, featured video and tags in portfolio CPT
- Implement portfolio post card
- Move post types registration methods to init action
- Include taxonomies in page settings
- Apply text-underline-offset to links
- Implement Portfolio Sidebar

1.5.2 24-09-06
- Add .container controls in theme options
- Fix date archive pages
- Underline Text implementation in tinymce
- expand_on_click option implementation in Image component
- Portfolio CPT has_archive true

1.5.1 24-09-05
- Fix .pinned-block height
- Fix .content_layout need .layout-grid class
- Load login.css from active theme
- Add position: relative to :has(>.cover-all)
- Remove action woocommerce_get_sidebar
- Fix WooCommerce pagination

1.5.0 24-09-04
-----------------------------------------------------------------------------------------------------
- !IMPORTANT: V.1.5.0
- Folders structure refactorization
- Component Settings Open in popup
- Migrator Module.
-----------------------------------------------------------------------------------------------------
- Blocks Layout Settings implementation in Components Wrapper
- Change .uf-group-prototype width in layout
- Responsive implementation in common settings container
- Add alignment and order per device in Columns Settings

0.4.1 24-07-30
-----------------------------------------------------------------------------------------------------
- !IMPORTANT: THEME CUSTOM FIELDS STRUCTURE REFACTORIZATION
- "Layout Column" component is not generated anymore in content layout
- Progress Bar, Progress Circle and Row components are not implemented anymore
- USE_REUSABLE_SECTIONS_AS_PAGE_MODULE Constant implementated as false
- Theme_Custom_Fields\Component Class implemented, Child themes need to extend it to add componentes
-----------------------------------------------------------------------------------------------------
- fix link color in .text-color-1

0.3.4 24-07-18
- Fix materialize inputs height and padding for a better integration with woocommerce

0.3.3 24-07-17
- Materialize sidenav.js improvement: _handleKeydown() method for ESC key
- add pageID to MV23_GLOBALS
- Use ultimate_fields_page_content() in the_content hook filter
- Implement %current-menu-item-styles global style

0.3.2 24-07-16
- Offcanvas Elements Improvement: close on click setting
  - trigger events tab renamed to trigger
- nodeModules_path removed from gulpfile
- Add Offcanvas Elements to actions settings
- Add Offcanvas Element setting to menu item
- menu_item_data apply to all levels

0.3.1 24-07-16
-------------------------------------------------------------------------------------
- !IMPORTANT: MAIN NAV REFACTORIZATION
- Includes a menu component with adaptive-navbar.js
-------------------------------------------------------------------------------------

0.2.2 24-07-11
- Small css modal refactorizations
- delete .open-minicart classes and js
- fix modal data-status loading css
- post-modal css deleted
- Offcanvas Elements Feature merged
- Add Offcanvas Elements to Theme Options Menu

0.2.1 24-07-02
-------------------------------------------------------------------------------------
- !IMPORTANT: Upgrade Materialize to 1.0.0
- all scripts from scripts.js and ultimate-fields moved to modules
- scripts moved to ignore folder:
  counter.js animated.js progress-bar.js slider-de-contemidos.js video-card.js
-------------------------------------------------------------------------------------

0.1.3 24-06-26
- Components Wrapper Comp. Standarization
- Fix :not(.components) that need component-spacing-separator when they have background, border or shadow
- Fix body background color in editor styles

0.1.2 24-06-22
- Set a better version naming convention in readme.txt
- disable_comments_styles global option implementation and comments_area shortcode

0.1.1 24-06-21
-------------------------------------------------------------------------------------
- !IMPORTANT: HEADER CLASS REFACTORIZATION
  - Floating Header Class removed
  - Renamed to Static Header and Sticky Header
  - Header Class getters return array instead of attributes
  - set_additional_classes method removed
  - .hide-static-header && .hide-sticky-header implementation
  - header customization in page settings revised
-------------------------------------------------------------------------------------
- Use css properties to handle all theme colors

23.8.228 24-06-21
- CPT Class Implementation: set_single_template() method

23.8.227 24-06-18
- Fix Video Comp. external source video is treated as a string

23.8.226 24-06-14
- Menu items custom fields revision:
- Separate setting from megamenu
- Fix: uf -> listenForThemeLocations in menu items
- MENU_ITEM_MEGAMENU_LOCATIONS Implementation
- __locations_multilingual_support()

23.8.225 24-06-13
- Add uf hidden input to menu item in order to edit it in a pop up

23.8.224 24-06-12
- Remove full-width styles from post-modal and expander on listing component
- .wpcf7-full-width class

23.8.223 24-06-07
- Components Wrapper Comp. Implementation

23.8.222 24-06-06
- Accordion Comp. fix btn image_size
- Image Comp. fix popable video action

23.8.221 24-05-18
- Check if user is admin before using 'custom_menu_order' filter hook in theme options

23.8.220 24-05-17
- Listing Comp. on_click_post "no actions" implementation
- Listing Comp. small view fixes: layout and pagination

23.8.219 24-05-16
- Support for CUSTOM_TINYMCE_FONTS

23.8.218 24-04-25
- Fix: Map component zoom data-attribute implementation

23.8.217 24-04-19
- Comp. Listing remove limit and slider from gap controls

23.8.216 24-04-17
- CF7_USE_EMAIL_TEMPLATE fixes:
  - use CF7_EMAIL_BUTTON_COLOR for button color
  - use [_site_title] in footer, remove p margins in data table

23.8.215 24-04-04
- Listing Component fix: include pagination wrapper always even if is empty to be filled with ajax

23.8.214 24-04-02
- Show attachment caption in gallery lightbox
- Listing Component fixes: 
  - take in count data-terms on submit
  - move loading css animation from before to after
  - add max-width to post filter
  - check date params to include in main query
  - show load more pagination on submit
- Fix .button class inside columnas simples comp.
- Fix save_as_theme_footer action was showing in all cpts
- Reduce fancybox toolbar middle buttons in mobile
- Use component layout in Content_Layout group

23.8.213 24-03-22
- Button Componente class renamed to .button-comp to avoid css collison with .button
- JQuery Flex Datalist implementation in Text uf field

23.8.212 24-03-21
- Listing Component fix: taxonomies-terms not working in pagination

23.8.211 24-03-17
- Button Comp: .btn--white implementation
- rrss .style4 implementation
- Fix color in main nav current item when is a button

23.8.210 24-03-16
- Url attribute implementation in open_minicart shortcode. Default: cart page

23.8.209 24-03-15
- Fixes: Testimonios Comp. Field show author in title & Items Grid Comp. is not a .component
- Make .cover-all class global
- remove-support-for-pdf-to-embed-convertion class change to disable-link-to-embed-conversion

23.8.208 24-03-08
- WooCommerce Support for: minicart_sidebar and open_minicart shortcode
---------------------------------------------------------------------------------------
!IMPORTANT
- Move .btn pseudo-element from after to before for better integration with WooCommerce
----------------------------------------------------------------------------------------
- Add get_items_cart_qty javascript function
- Fix carrusels controls position in all devices

23.8.207 24-03-07
- Add support for shop_sidebar when woocommerce is active
-------------------------------------------------------------------------------------
- !IMPORTANT: REFACTORIZE DISABLE_PAGE_HEADER_IN
  - Renamed to PAGE_HEADER_IN
  - By default page header just load in pages and archive pages
-------------------------------------------------------------------------------------
- (core/js/container/group.js) Show filename in repeater group title preview 


23.8.207 24-03-06
- Header css fixes: standarize .container width and remove secondary-nav styles
- .modal implementation: data-close-on-click=1
- Add Separador Component to CONTENT_BUILDER_COMPONENTS

23.8.206 24-03-05
- Fix post card tags position
- Fix theme-color-scheme in header and nav

23.8.205 24-03-04
- Gallery Comp. Fix: Add --aspect-ratio css var to carrusel gallery
- admin-styles.css implementation: .one-lined-file-preview class for UF File Field
- add .remove-support-for-pdf-to-embed-convertion css class
- add fancybox options: infinite-false and fullscreen button
- Load overriden variables sass file before variables file

23.8.204 24-03-02
-------------------------------------------------------------------------------------------------------
- !IMPORTANT: Posts Listing Comp. works with '.trigger-post-action' class instead of '.expander-open'
-------------------------------------------------------------------------------------------------------
- Posts Listing Comp. Implementation: show post in popup action
- Page Settings Implementation: page_color_scheme
- scroll-animations: new animated property: background position

23.8.203 24-02-28
- Carussel updates: 
  - tiny slider version 2.9.4 
  - gallery mode implementation
  - touch false in mobile devices
- CSS Fixes: remove expander before and after and remove position relative from post-card
- CSS Implementation: columnas auto + fluid

23.8.202 24-02-27
- Scroll Animation implementations: set_pin and trigger carrusel next/prev
- Carrusel Comp: axis implementation

23.8.201 24-02-23
- Comp. Action Settings refactorization to select image/video popup in field

23.8.200 24-02-22
- Gallery Comp. css Fixes
- Remove Colorbox support from listing expander
- Add .scroll class to editor styles
- Fix javascript related to carrusel in listing and items in expander
- CSS fix: import typography in overriden variables

23.8.199 24-02-21
- Gallery Comp. Implementation: open gallery with a link

23.8.198 24-02-14
- Gallery Comp. Refactorization

23.8.197 24-02-09
- Footer CPT Implementation
- Use CPT Class to register Post Types

23.8.196 24-02-08
------------------------------------------------------------------------------------
- !IMPORTANT: Merge features from childtheme && theme css standarization
------------------------------------------------------------------------------------
- Image/Video Comp. improvements
  - Used embed UF Field type instead of text:type to save external urls
  - Youtube source changed to External source
  - Fix fields dependencies
  - Remove support for $video_background_fields
- Standarize carrusel controls position

23.8.195 24-02-07
- (libs/ultimate-fields/core/js/field/repeater.js) Fix: group opens twice on copy/paste action

23.8.194 24-02-02
- Carrusel Comp. auto height parameter implementation

23.8.193 24-01-26
- Fix: Validate if pll_ function exists before use it
- Fix: .carrusel__item__link styles definition

23.8.192 24-01-25
- Implementation of add_group inside Layout Column
------------------------------------------------------------------------------------
- !IMPORTANT: $GLOBALS['componentes'] implementation
  - edit-components.php file needs to add new components to $GLOBALS['componentes']
  - edit-components-before-content-layout.php file removed
------------------------------------------------------------------------------------
- Implementation of context menu (delete, copy, paste) in layout group
- Accordion Comp. fields reorganization

23.8.191 24-01-24
- Implementation of Layout Column Component in Content_Layout Class
- Balance components's margin top/bottom inside .columnas-simples component

23.8.190 24-01-22
- Add modal close to mobile menu

23.8.189 24-01-19
- Button Comp. Implementation: add icon and icon position setting
- Carrusel Comp. Implementation: add settings fields

23.8.188 24-01-17
- Set better name and description for header_widgets_1

23.8.187 24-01-11
- Add Google Maps Services selector in theme options

23.8.186 24-01-10
- Carrusel js fix: slideBy parameter same as items per device
- Carrusel css fix: control images root
- Add register_custom_fields hook 
  * useful to use Content_Layout Class in other custom field container
- Map component zoom data-attribute implementation

23.8.185 23-12-21
- Use slug in lang switcher shortcode

23.8.184 23-11-29
- Remove sintaxis de tipo de retorno (: void, : int, : string, etc.) in UF Lib

23.8.183 23-11-28
- CF7 not valid message color in text-color-2
- Page Header background color refactorization to allow empty value

23.8.182 23-11-24
- add actions settings to column component

23.8.181 23-11-23
- Gallery css fixes:
  admin: fix select2 show behind modal
  front: fix wp media folder show tiny img in full width
  front: justify-content center
- Remove UF warnings when WP_DEBUG is true
------------------------------------------------------------------------------
- !IMPORTANT FIX: DISABLE_PAGE_HEADER_IN in post result in FATAL ERROR
  when post is in UF_POSTTYPES or CONTENT_BUILDER_POSTTYPES
  - post is removed from UF_POSTTYPES
  - editor is not removed in post
  - if post need to be added to UF_POSTTYPES or CONTENT_BUILDER_POSTTYPES
    remove post from DISABLE_PAGE_HEADER_IN
------------------------------------------------------------------------------

23.8.180 23-11-22
- Use CONTENT_BUILDER_COMPONENTS in accordion component
- Add Listing, Button, HTML Components to CONTENT_BUILDER_COMPONENTS

23.8.179 23-11-21
- Correction in oembed function: ignore links inside href attribute

23.8.178 23-11-09
- Listing Comp. on click post actions implementation
  Refactorization of posts-listing--portfolio1

23.8.177 23-11-07
- Add title_template to scroll animation group

23.8.176 23-10-26
- .columnas-simples__item css change to break in mobile: width:100%;
- add scroll to box option to toggle_box_settings

23.8.175 23-10-19
- include ultimate_fields_page_content() function in admin to work with ajax

23.8.174 23-10-11
- $sidebar-width sass variable
- CONTENT_BUILDER_COMPONENTS constant implementation
- $scroll-width sass variable

23.8.173 23-09-28
- Show layout class in .page-module

23.8.172 23-09-27
- Listing Comp. orderby menu_order implementation

23.8.171 23-09-26
- Separador Comp. unit implementation
- v23ToggleBox.js data-scrollto && data-scrollTop implementation

23.8.170 23-09-25
- Listing Comp. Implementation: WooCommerce special product tags: on_sale, best selling, featured

23.8.168 23-09-22
- Use scroll indicators script just if is enabled

23.8.167 23-09-21
- lib UF -> (core/classes/Field/Select.php) Add add_query_params function to use with terms query

23.8.167 23-09-15
- Listing Comp. fix: validate if some term is selected

23.8.166 23-09-14
- Single Megamenu
- Change Gallery Comp. default values for image and ligthbox size

23.8.165 23-09-07
- Transform document links js-function works in all .single pages
- Listing Component Fixes: Carousel template works with filter and pagination

23.8.164 23-09-06
----------------------------------------------------------------------------
- !IMPORTANT: Listing Component ands posts_filter refactorization
This version works with several taxonomies and terms
----------------------------------------------------------------------------

23.8.163 23-09-05
- Footer Modules Implementation: Portuguese language

23.8.162 23-09-04
- Card Comp. implementations: action and video global settings

23.8.161 23-08-30
- Carussel Comp. Fix: imgs align items center

23.8.160 23-08-25
- Add $container-width css var to use in .extended-bg columns

23.8.159 23-08-23
- Gallery Comp. dosn´t depend on wp media folder plugin
- Image/Video Comp. improvements to detect youtube video id
- Transform document links (.docx, .pptx, .xlsxs) into embed iframe in single page
- utils: get_video_details_from_url( $url ) function implementation

23.8.158 23-08-19
- move video background options to common settings

23.8.157 23-08-07
- Columnas Comp. update video_settings options
- Fixed Header alpha bgc option implementation
- Page Settings alpha bgc option implementation

23.8.156 23-08-05
- Posts Listing Comp. 'portfolio1' Fixes: 
  works with '.expander-open' class
  inner colorbox implementation

23.8.155 23-08-03
- Posts Listing Comp: 'portfolio1' list template implementation
- Listing Comp. fixes
  removed post_terms custom field -> added to LISTING_TAXONOMIES
  filter in listing view: if no term is selected use first taxonomy returned in get_object_taxonomies( $posttype )

23.8.154 23-08-02
- Content Slider carrusel rewind true
- Allow all file types in Uf Gallery Field 

23.8.153 23-08-01
- DEFAULT_COLOR_SCHEME and DEFAULT_TEXT_COLOR implementation

23.8.152 23-07-26
- tns-controls color
- Video Poster Implementation
- New column's width: 1/8 6/8 1/8
- change columnas-simples breakpoint
- use rrss style3 in mobile
- Icon and text component -> horizontal align implementation

23.8.151 23-07-25
- Add / Remove active class on toggle section

23.8.150 23-07-14
- Carrusel Comp. remove align center

23.8.149 23-07-12
- Add action setting that allows show and hide a section
- text-color-2 > h1,h2,...,a color:inherit
- Button Comp: add size setting
- Carrusel Comp standarization
  shared css styles
  allow content layout

23.8.148 23-07-11
- Fix: Post Listing Carrusel data-control-position

23.8.147 23-07-04
- Post Listing Carrusel: set default control position to center

23.8.146 23-06-30
- Fixed v23ToggleBoxModule: preventDefault avoid links execution

23.8.145 23-06-22
- Image/Video Comp. fix video settings defaults

23.8.144 23-06-19
-----------------------------------------------------------------------
- !IMPORTANT: Image/Video Component refactorization
-----------------------------------------------------------------------

23.8.143 23-06-17
- Gallery Comp. fix bugs:
 gallery items order
 orderby options commented -> not working

23.8.142 23-06-16
- Fixed unexpected ',' in gallery comp.

23.8.141 23-06-15
- Listing Component: orderby setting implementation

23.8.140 23-06-09
- Just use the_content filter if is_singular() || is_page()

23.8.139 23-06-07
- Carrusel & Content Slider Comp: navigation buttons standarization

23.8.139 23-06-07
- fix z-index for select2 in popup
- Gallery Component: use wp media folders

23.8.138 23-06-06
- Button Comp: add attributes option

23.8.137 23-06-04
- Content Layout -> look for components view in child theme

23.8.136 23-06-03
- Listing Comp: refactorize posts filter options to have default values
- Listing Comp: clear pagination loading message on error

23.8.135 23-05-27
- V23_ToggleBox version 5.8.30
  _handle_hash_in_url() implementation
  _attach_hashchange_events implementation
  anchors push state and emit an event
- Accordion comp. allow to change item id 
- HTML Component
- Mobile Menú Button: color standarization
- .posts-filter .field-wrapper mobile 100%

23.8.134 23-05-26
- Test button shortcode
- New Button Component
- Add Button Comp. to accordion content layout

23.8.133 23-05-24
- scroll-animations: new animated property: add/remove class

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
- Column Component accepts 1 in quantity and video background
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
