<?php
/**
 * Base class and namespace used in all of our plugins.
 *
 * @package Developry
 * @subpackage 
 * @author Krasen Slavov
 * @version 1.0.0
 * @since 1.0.0
 */

namespace Developry;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( __NAMESPACE__ . '\Developry' ) ) 
{
    class Developry 
    {
        // @var $_instance
        public static $_instance;

        // @var $_plugin_file_path
        public static $_plugin_file_path;

        // @var $_developry_plugins
        public static $_developry_plugins = array( 
            'developry-google-fonts.php',
            'help-desk-9-by-developry.php',
            'featured-image-plus.php',
            'superscripted.php'
        );

        /**
         * Constructor.
         */
        public function __construct( $plugin_file_path )
        {
            if ( version_compare( PHP_VERSION, HD9_MIN_PHP_VERSION, '<' )
                && version_compare( $GLOBALS['wp_version'], HD9_MIN_WP_VERSION, '<' ) ) 
            {
                add_action( 'admin_notices', array( $this, 'show_minimum_support_notice' ) );
            } 
            else  
            {
                self::$_plugin_file_path = $plugin_file_path;
            }
        }

        /**
         * Create and instance of our plugin.
         */
        public static function instance( $plugin_file_path ) 
        {
            if ( is_null( self::$_instance ) ) 
            {
                self::$_instance = new self( $plugin_file_path );
            }

            return self::$_instance;
        }

        /**
         * Check for WordPress and PHP minimum support versions.
         */
        public function show_minimum_support_notice() 
        {
            $class   = 'notice notice-error is-dismissable';

            $message = '<h2>WordPress Plugin Minimum Requirements<hr /></h2>';
            $message .= '<p><strong>' . HD9_NAME .'</strong> requires PHP version ' . HD9_MIN_PHP_VERSION . ' and WordPress ' . HD9_MIN_WP_VERSION . ' or above.<br /><br /> You are currently running <strong>PHP ' . PHP_VERSION . '</strong> and <strong>WordPress ' . $GLOBALS['wp_version'] . '</strong>. Please upgrade to the minimum supported versions for the plugin to be loaded.</p>';
            $message .= '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">&larr; Go Back</a>';

            return '<div class="' . $class . '">' . $message . '</div>';
        }

        /**
         * Call activate and deactivate action on plugin activation/deactivation.
         */
        public function activate() 
        { 
            if ( is_admin() && false === get_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once' ) )
            {
                add_option( 'developry_plugin_loaded', true );

                add_option( HD9_WPORG_SLUG . '_activate',HD9_NAME );   

                add_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once', HD9_NAME );
            }
        }

        public function deactivate() 
        {
            delete_option( 'developry_plugin_loaded' );

            delete_option( HD9_WPORG_SLUG . '_activate' );
            
            delete_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once' );            
        }

        /**
         * Help and utility global actions called on plugin activation/deactivation
         */
        public function utilities()
        {
            register_activation_hook( self::$_plugin_file_path, array( $this, 'activate' ), 10, 1 );

            register_deactivation_hook( self::$_plugin_file_path, array( $this, 'deactivate' ), 10, 1 ); 

            add_action( 'activated_plugin', function() {

                if ( is_admin() && false !== get_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once' ) )
                {
                    if ( in_array( basename( self::$_plugin_file_path ), self::$_developry_plugins ) )
                    {
                        exit( wp_safe_redirect( admin_url( '/admin.php?page=developry-welcome' ) ) );
                    }
                }

            }, 10, 1 );

            add_action( 'plugin_row_meta', function( $links, $file ) {
            
                if ( plugin_basename( self::$_plugin_file_path ) === $file ) 
                {
                    $links['docs']   = '<a href="' . HD9_DOC_PAGE . '" target="_blank" class="text-success">Documentation</a>';
                    $links['donate'] = '<a href="' . HD9_HOME_PAGE . 'donate" target="_blank" class="text-danger"><strong>Donate</strong></a>';
                }
            
                return $links;
            
            }, 10, 2 );

            add_action( 'admin_enqueue_scripts', function() {

                wp_enqueue_style( HD9_WPORG_SLUG . '-developry-admin', HD9_URL . '/assets/css/developry-admin.css', null, HD9_VERSION, 'all' );
                
                wp_enqueue_script( HD9_WPORG_SLUG . '-developry-admin', HD9_URL . '/assets/js/developry-admin.js', array( 'jquery' ), HD9_VERSION, true );
            
            }, 10, 1 );

            add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ), 10, 1 );
        }

        /**
         * Add plugin WP admin Developry menu.
         */
        function add_admin_menu_page()
        {
            if ( empty( $GLOBALS['admin_page_hooks']['developry-welcome'] ) ) 
            {
                add_menu_page( 'Thank you for choosing Developry!', 'Developry', 'manage_options', 'developry-welcome', array( $this, 'show_developry_welcome_screen' ), 'dashicons-plugins-checked', 1001 );
            }

            if ( empty( $GLOBALS['admin_page_hooks'][HD9_WPORG_SLUG] ) ) 
            {
                add_submenu_page( 'developry-welcome', HD9_NAME, HD9_NAME, 'manage_options', HD9_WPORG_SLUG, array( $this, 'show_plugin_main_page' ) );
            }
        }

        /**
         * Show up the following content for Developry main screen admin link.
         */
        function show_developry_welcome_screen() 
        {
            require_once HD9_DIR_PATH . '/templates/developry-welcome-screen.tpl.php';
        }

        /**
         * Show up the following content for Plugin main screen admin link.
         */
        function show_plugin_main_page() 
        {
            require_once HD9_DIR_PATH . '/templates/plugin-main-admin-page.tpl.php';
        }
    }
}
