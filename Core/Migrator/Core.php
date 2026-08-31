<?php
namespace Core\Migrator;

use Core\Includes\Theme_Version;
use Core\Migrator\Migration\Migrate_0_4_X_to_0_5_0;
use Core\Migrator\Migration\Migrate_1_5_X_to_2_0_1;
use Core\Migrator\Migration\Migrate_Gmaps_to_Leaflet;
use Core\Migrator\Migration\Migrate_ScrollMagic_to_GSAP;
use Core\Migrator\Migration\Migrate_Timeline_Group_To_Groups;
use Core\Migrator\Migration\Migrate_2_2_X_to_2_3_0;
use Core\Migrator\Migration\Migrate_Gallery_Settings;
use Core\Migrator\Migration\Migrate_Video_Settings;
use Core\Migrator\Migration\Migrate_OCE_Settings;
use Core\Migrator\Migration\Migrate_Accordion_Settings;
use Core\Migrator\Migration\Migrate_Inner_Components;
use Core\Migrator\Migration\Migrate_Slider_Comp_To_Shortcode;
use Core\Migrator\Migration\Migrate_Carrusel_Comp_To_Carousel;
use Core\Migrator\Migration\Migrate_Heading_Settings;
use Core\Migrator\Migration\Migrate_2_10_X_to_3_0_0;
use Core\Migrator\Migration\Cleanup_2_10_X_to_3_0_0;
use Core\Migrator\Migration\Migrate_3_2_X_to_3_3_0;
use Core\Migrator\Migration\Migrate_3_3_0_to_3_4_0;
use Core\Migrator\Migration\Migrate_3_4_X_to_3_5_0;
use Core\Migrator\Migration\Migrate_3_5_X_to_3_6_0;
use Core\Migrator\Migration\Migrate_3_6_X_to_3_7_0;
use Core\Migrator\Migration\Migrate_3_7_X_to_3_8_0;
use Core\Migrator\Migration\Migrate_3_8_X_to_3_9_0;
use Core\Migrator\Migration\Migrate_3_9_X_to_3_10_0;
use Core\Migrator\Migration\Migrate_3_10_X_to_3_11_0;
use Core\Migrator\Migration\Migrate_OCE_Restrictions_to_Visibility;
use Core\Migrator\Migration\Migrate_3_11_X_to_3_12_0;
use Core\Migrator\Migration\Migrate_3_13_X_to_3_14_0;
use Core\Migrator\Migration\Migrate_3_14_X_to_3_15_0;

define ('THEME_MIGRATOR_DIR', __DIR__);
define ('THEME_MIGRATOR_PATH', get_template_directory_uri() . '/Core/Migrator');

class Core{

    private static $instance = null;

    private $slug;

    private $version = '1.1.0';

    public $migrator_url;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    // migrations tied to a theme version, mapped to their target ("to") version
    private $versioned_migrations = array(
        Migrate_0_4_X_to_0_5_0::class                 => '0.5.0',
        Migrate_1_5_X_to_2_0_1::class                 => '2.0.1',
        Migrate_Gmaps_to_Leaflet::class                => '2.0.1',
        Migrate_ScrollMagic_to_GSAP::class             => '2.1.0',
        Migrate_Timeline_Group_To_Groups::class        => '2.2.0',
        Migrate_2_2_X_to_2_3_0::class                  => '2.3.0',
        Migrate_Gallery_Settings::class                => '2.4.0',
        Migrate_Video_Settings::class                  => '2.5.0',
        Migrate_OCE_Settings::class                    => '2.6.0',
        Migrate_Accordion_Settings::class              => '2.7.0',
        Migrate_Inner_Components::class                => '2.8.0',
        Migrate_Slider_Comp_To_Shortcode::class        => '2.8.0',
        Migrate_Carrusel_Comp_To_Carousel::class       => '2.8.0',
        Migrate_Heading_Settings::class                => '2.9.0',
        Migrate_2_10_X_to_3_0_0::class                 => '3.0.0',
        Cleanup_2_10_X_to_3_0_0::class                 => '3.0.0',
        Migrate_3_2_X_to_3_3_0::class                  => '3.3.0',
        Migrate_3_3_0_to_3_4_0::class                  => '3.4.0',
        Migrate_3_4_X_to_3_5_0::class                  => '3.5.0',
        Migrate_3_5_X_to_3_6_0::class                  => '3.6.0',
        Migrate_3_6_X_to_3_7_0::class                  => '3.7.0',
        Migrate_3_7_X_to_3_8_0::class                  => '3.8.0',
        Migrate_3_8_X_to_3_9_0::class                  => '3.9.0',
        Migrate_3_9_X_to_3_10_0::class                 => '3.10.0',
        Migrate_3_10_X_to_3_11_0::class                => '3.11.0',
        Migrate_OCE_Restrictions_to_Visibility::class  => '3.11.0',
        Migrate_3_11_X_to_3_12_0::class                => '3.12.0',
        Migrate_3_13_X_to_3_14_0::class                => '3.14.0',
        Migrate_3_14_X_to_3_15_0::class                => '3.15.0',
    );

    private function __construct(){
        $this->slug = 'theme-migrator';

        $installed_version = Theme_Version::getInstance()->get_installed_version();

        foreach ( $this->versioned_migrations as $migration_class => $target_version ) {
            if ( version_compare( $installed_version, $target_version, '<' ) ) {
                $migration_class::getInstance()->migrate();
            }
        }

        add_action( 'admin_menu', array($this, 'add_admin_page') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_scripts') );
        add_action( 'admin_post_mv23_mark_migrations_complete', array( $this, 'handle_mark_migrations_complete') );
        add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget') );
    }

    public function add_admin_page(){
        $slug = self::$instance->get_slug();

        add_submenu_page(
            'theme-options',
            __('Theme Migrator', 'mv23theme'),
            __('Theme Migrator', 'mv23theme'),
            'manage_options',
            $slug,
            array($this, 'display'),
            60
        );

        $this->migrator_url = admin_url('admin.php?page='.$slug);
    }

    public function display(){
        do_action('theme_migrator_display');
        echo '<hr>';
        $this->render_version_status();
    }

    private function render_version_status(){
        $theme_version = Theme_Version::getInstance();
        ?>
        <div class="wrap theme-migrator__version-status">
            <p>
                <?php
                printf(
                    /* translators: 1: installed version, 2: current theme version */
                    esc_html__( 'Installed version: %1$s — Current theme version: %2$s', 'mv23theme' ),
                    esc_html( $theme_version->get_installed_version() ),
                    esc_html( $theme_version->get_current_version() )
                );
                ?>
            </p>
            <?php if ( $theme_version->needs_upgrade() ) : ?>
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="mv23_mark_migrations_complete">
                    <?php wp_nonce_field( 'mv23_mark_migrations_complete' ); ?>
                    <button type="submit" class="button" onclick="return confirm('<?php echo esc_js( __( 'Confirm all applicable migrations above have finished successfully?', 'mv23theme' ) ); ?>');">
                        <?php esc_html_e( 'Mark migrations as complete', 'mv23theme' ); ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }

    public function handle_mark_migrations_complete(){
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not allowed to do this.', 'mv23theme' ) );
        }
        check_admin_referer( 'mv23_mark_migrations_complete' );

        Theme_Version::getInstance()->mark_upgraded();

        wp_safe_redirect( admin_url( 'admin.php?page=' . $this->get_slug() ) );
        exit;
    }

    public function enqueue_migrator_scripts( $hook ) {
        if ( 'admin_page_theme-migrator' != $hook ) return;

        $slug = self::$instance->get_slug();
        wp_enqueue_script($slug.'-scripts', THEME_MIGRATOR_PATH . '/scripts/script.js', array('jquery'), $this->version, true);
        wp_enqueue_style($slug.'-styles', THEME_MIGRATOR_PATH . '/styles/styles.css', array(), $this->version);

        wp_localize_script($slug.'-scripts', 'THEME_MIGRATOR_GLOBALS', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('process_page_data_nonce')
        ));
    }

    public function theme_version_is_less($current_version, $other_version) {
        // transform to array
        $_current = explode('.', $current_version);
        $_other = explode('.', $other_version);
    
        // mutate to integers
        $_current = array_map('intval', $_current);
        $_other = array_map('intval', $_other);
    
        // Compare
        for ($i = 0; $i < count($_current); $i++) {
            if ($_current[$i] < $_other[$i]) {
                return true;
            } elseif ($_current[$i] > $_other[$i]) {
                return false;
            }
        }
    
        // is the same version
        return false;
    }

    public function get_slug(){
        return $this->slug;
    }

    public function add_dashboard_widget(){
        if ( ! current_user_can( 'manage_options' ) || ! Theme_Version::getInstance()->needs_upgrade() ) {
            return;
        }

        wp_add_dashboard_widget(
            'mv23_theme_migrations_widget',
            __( 'Theme Migrations Available', 'mv23theme' ),
            array( $this, 'render_dashboard_widget' )
        );
    }

    public function render_dashboard_widget(){
        $theme_version = Theme_Version::getInstance();
        ?>
        <p>
            <?php
            printf(
                /* translators: 1: installed version, 2: current theme version */
                esc_html__( 'The theme code is at version %2$s but the site is migrated up to %1$s. Some migrations may be pending.', 'mv23theme' ),
                esc_html( $theme_version->get_installed_version() ),
                esc_html( $theme_version->get_current_version() )
            );
            ?>
        </p>
        <p>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->get_slug() ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'Go to Theme Migrator', 'mv23theme' ); ?>
            </a>
        </p>
        <?php
    }
}