<?php
/**
 * Namespace and Class for our plugin.
 *
 * @package Developry
 * @subpackage Help_Desk_9
 * @author Krasen Slavov
 * @version 1.0.0
 * @since 1.0.0
 */

namespace Developry\HelpDesk9;

defined( 'ABSPATH' ) || exit;

require_once HD9_DIR_PATH . '/classes/Developry.class.php';

if ( ! class_exists( __NAMESPACE__ . '\Help_Desk_9' ) ) 
{
    class Help_Desk_9 extends \Developry\Developry
    {
        /**
         * Constructor.
         */
        public function __construct( $plugin_file_path )
        {
            self::$_plugin_file_path = $plugin_file_path;

            parent::instance( $plugin_file_path );

            self::init();
        }

        public function utilities() 
        {
            register_activation_hook( self::$_plugin_file_path, function() {

                if ( is_admin() && false === get_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once' ) )
                {
                    add_option( 'developry_plugin_loaded', true );

                    add_option( HD9_WPORG_SLUG . '_activate',HD9_NAME );   

                    add_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once', HD9_NAME );
                }

            }, 10, 1 );

            register_deactivation_hook( self::$_plugin_file_path, function() {

                delete_option( 'developry_plugin_loaded' );

                delete_option( HD9_WPORG_SLUG . '_activate' );
                
                delete_option( HD9_WPORG_SLUG . '_welcome_screen_redirect_once' );  
                  
            }, 10, 1 ); 

            add_action( 'plugin_row_meta', function( $links, $file ) {
            
                if ( plugin_basename( self::$_plugin_file_path ) === $file ) 
                {
                    $links['docs']   = '<a href="' . HD9_DOC_PAGE . '" target="_blank" class="text-success">Documentation</a>';
                    $links['donate'] = '<a href="' . HD9_HOME_PAGE . 'donate" target="_blank" class="text-danger"><strong>Donate</strong></a>';
                }
            
                return $links;
            
            } , 10, 2 );

            add_action( 'admin_enqueue_scripts', function() {

                wp_enqueue_style( HD9_WPORG_SLUG . '-developry-admin', HD9_URL . '/assets/css/developry-admin.css', null, HD9_VERSION, 'all' );
                
                wp_enqueue_script( HD9_WPORG_SLUG . '-developry-admin', HD9_URL . '/assets/js/developry-admin.js', array( 'jquery' ), HD9_VERSION, true );
            
            }, 10, 1 );
        
            add_action( 'admin_menu', function() {

                if ( empty( $GLOBALS['admin_page_hooks']['developry-welcome'] ) ) 
                {
                    add_menu_page( 'Thank you for choosing Developry!', 'Developry', 'manage_options', 'developry-welcome', function() {
                        
                        require_once HD9_DIR_PATH . '/templates/developry-welcome-screen.tpl.php';

                    }, 'dashicons-plugins-checked', 1001 );
                }

                if ( empty( $GLOBALS['admin_page_hooks'][HD9_WPORG_SLUG] ) ) 
                {
                    add_submenu_page( 'developry-welcome', HD9_NAME, HD9_NAME, 'manage_options', HD9_WPORG_SLUG, function() {

                        require_once HD9_DIR_PATH . '/templates/plugin-main-admin-page.tpl.php';
                    } );
                }
            } );
        }

        public function customize_flamingo_inbound_messages_columns() 
        {
            add_action( 'manage_flamingo_inbound_posts_columns', function( $columns )  {

                if ( ! array_key_exists( 'post_status', $_GET ) ) 
                {
                    unset( $columns['subject'] );

                    unset( $columns['cb'] ); // remove cb termporary

                    $new_columns['cb']          = '<input type="checkbox" />';

                    $new_columns['new_subject'] = 'Subject';

                    $new_columns['status']      = 'Status';

                    $columns = array_merge( $new_columns, $columns );
                }

                return $columns;

            }, 10, 1 );

            add_action( 'manage_flamingo_inbound_posts_custom_column', function( $column, $post_ID ) { 

                $user   = get_userdata( get_current_user_id() );

                $meta   = get_post_meta( $post_ID, '_flamingo_inbound_reply_data', true );

                if ( 'status' === $column ) 
                {
                    if ( null !== $meta && 1 === $meta['show'] ) 
                    {
                        echo '<span class="status-open">Opened</span>';
                    } 
                    else 
                    {
                        echo '<span class="status-close">Closed</span>';
                    }
                }

                if ( 'new_subject' === $column ) 
                {
                    echo '<style>.flamingo_page_flamingo_inbound .has-row-actions .row-actions:last-of-type { display: none; }</style>';
                    $nonced_url = wp_nonce_url( admin_url('admin.php?page=flamingo_inbound&post=' . $post_ID . '&action=spam'), 'flamingo-spam-inbound-message_' . $post_ID );

                    echo '<strong><a href="admin.php?page=flamingo_inbound&post=' . $post_ID . '&action=edit" class="row-title" aria-label="View post ' . get_the_title( $post_ID ) . '">' . get_the_title( $post_ID ) . '</a></strong><br /><div class="row-actions row-actions-0"><span class="edit"><a href="admin.php?page=flamingo_inbound&post=' . $post_ID . '&action=edit" aria-label="View post ' . get_the_title( $post_ID ) . '">View</a> | </span><span class="spam"><a href="' . $nonced_url . '" aria-label="Move to spam folder">Spam</a> | </span><span class="reply"><a href="#" class="reply-link" aria-label="Reply to ' . get_the_title( $post_ID ) . '" data-postid="' . $post_ID . '" data-userid="' . $user->ID . '" data-title="' . get_the_title( $post_ID ) . '">Reply</a></span>';

                    if ( null !== $meta && 1 === $meta['show'] ) 
                    {
                        echo ' | <span class="status-close"><a href="#" class="close-link" aria-label="Change status of ' . get_the_title( $post_ID ) . '" data-postid="' . $post_ID . '">Close</a></span> ';         
                    } 
                    else 
                    {
                        echo ' | <span class="status-open"><a href="#" class="re-open-link" aria-label="Change status of ' . get_the_title( $post_ID ) . '" data-postid="' . $post_ID . '">Re-open</a></span>';
                    }

                    echo '</div>';
                }

            }, 10, 2 );
        }

        public function create_flamingo_inbound_message_reply() 
        { 
            // ! need to check for Anonymous users
            if ( sizeof( $_POST ) > 0 && $_POST['user_ID']  >= 0 
                && $_POST['post_ID']  && $_POST['message_title'] && $_POST['message_content'] ) 
            {
                $user = get_userdata( get_current_user_id() );

                $message_content = wp_strip_all_tags( $_POST['message_content'] );

                $meta_id     = sanitize_text_field( $_POST['post_ID'] );

                $meta_key    = '_flamingo_inbound_reply_data';

                $meta_value  = array(
                    'user_id'   => sanitize_text_field( $_POST['user_ID'] ), 
                    'post_id'   => sanitize_text_field( $_POST['post_ID'] ), 
                    'title'     => 'Re: ' . sanitize_text_field( $_POST['message_title'] ), 
                    'content'   => $message_content,
                    'timestamp' => time(),
                    'show'      => 1
                );

                // Update initial empty meta with CF9 submission.
                if ( empty ( get_post_meta(  $meta_id, '_flamingo_inbound_reply_data', true ) ) ) 
                { 
                    update_post_meta( $meta_id, $meta_key, $meta_value );
                } 
                else 
                {
                    add_post_meta( $meta_id, $meta_key, $meta_value );
                }

                print 1;

                exit;
            }

            print 0;

            exit;
        }

        public function change_flamingo_inbound_message_reply_status_closed() 
        {
            if ( sizeof( $_POST ) > 0 && $_POST['post_ID']  ) 
            {
                // Get only the 1st meta associated with the key and change the status.
                if ( ! get_post_meta( $_POST['post_ID'] , '_flamingo_inbound_reply_data', true ) ) 
                { 
                    $meta['show'] = 0;

                    update_post_meta( sanitize_text_field( $_POST['post_ID'] ), '_flamingo_inbound_reply_data', $meta );

                    echo 1;
                    exit;
                }
            }

            echo 0;
            exit;
        }

        public function change_flamingo_inbound_message_reply_status_opened() 
        {
            if ( sizeof( $_POST ) > 0 && $_POST['post_ID']  ) 
            {
                if ( ! get_post_meta( $_POST['post_ID'] , '_flamingo_inbound_reply_data', true ) ) 
                {
                    $meta['show'] = 1;

                    update_post_meta( sanitize_text_field( $_POST['post_ID'] ), '_flamingo_inbound_reply_data', $meta );

                    echo 1;
                    exit;
                }
            }

            echo 0;
            exit;
        }

        public function extend_flamingo_inbound_single_message() 
        {
            settings_errors();

            if ( sizeof( $_GET ) > 0 && array_key_exists( 'post', $_GET )
                && array_key_exists( 'page', $_GET ) && array_key_exists( 'action', $_GET )
                && ! empty( $_GET[ 'post' ] ) && 'flamingo_inbound' === $_GET['page'] && 'edit' === $_GET['action'] )
            {
                require_once HD9_DIR_PATH . 'templates/plugin-extend-flamingo-inbound-single-message.tpl.php';
            }
        }

        public function display_reply_table( $atts ) 
        {
            $atts = shortcode_atts( array( 'output' => '', 'format' => '' ), $atts ); // set default atts for shortcode

            if ( ! current_user_can('edit_posts') &&  ! current_user_can('edit_pages') )  
            {
                global $post;

                $user              = get_userdata( get_current_user_id() );

                $user_ID           = ( ! empty( $user ) ) ? $user->ID : 0; // 0 - anonymous, not logged in user

                $query             = new \WP_Query( 
                    array( 
                        'post_type'    => 'flamingo_inbound', 
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(), 
                    ) 
                );

                if ( 'json' === $atts['output'] ) 
                {
                    if ( $query->have_posts() ) {
                        while ( $query->have_posts() ) 
                        { 
                            $query->the_post();

                            $output[$post->ID] =  array(
                                'global' => array(
                                    'post-groupid' => 'post-group-' . $post->ID,
                                    'data-postid'  => $post->ID,
                                    'data-userid'  => $user_ID, 
                                )
                            );

                            $fields = get_post_meta( $post->ID, '_fields', true);

                            foreach ($fields as $key => $value) 
                            {
                                if ( preg_match('/input|title|subject|message|description|content|textarea/', $key) ) 
                                { // some kind of column filter

                                    $output[$post->ID]['request'][$key] = array(
                                        'title'   => ucwords( str_replace('-', ' ', $key) ),
                                        'content' => get_post_meta( $post->ID, '_field_' . $key, true ),
                                    );
                                }
                            }

                            $meta = get_post_meta( $post->ID, '_flamingo_inbound_reply_data' ); // all associated meta
                            
                            array_shift($meta); // need to figure out the notice here, this kind works since the 1st entry is only Array[0]
                            
                            $timestamp = array_column( $meta, 'timestamp' );
                            
                            @array_multisort( $timestamp, SORT_DESC, $meta );
                            
                            foreach ( $meta as $response ) 
                            {
                                if ( 'raw' === $atts['format'] ) 
                                {
                                    $output[$post->ID]['replies'][] = array(
                                        'user_ID'   => $response['user_id'],
                                        'user_name' => ( ! empty( $response['user_id'] ) ) ? get_userdata( $response['user_id'] )->display_name : 'Anonymous',
                                        'title'     => $response['title'],
                                        'message'   => $response['content'],
                                        'date'      => $response['timestamp'],
                                        'show'      => $response['show'],
                                    );
                                } 
                                else 
                                {
                                    $output[$post->ID]['replies'][] = array(
                                        'user_ID'   => $response['user_id'],
                                        'user_name' => ( ! empty( $response['user_id'] ) ) ? get_userdata( $response['user_id'] )->display_name : 'Anonymous',
                                        'title'     => $response['title'],
                                        'message'   => wp_specialchars_decode( $response['content'] ),
                                        'date'      => date( '\o\n jS \of F Y h:i A', $response['timestamp'] ),
                                        'show'      => $response['show'],
                                    );
                                }
                            }
                        }
                    }

                    return json_encode( $output );
                } 
                else 
                {
                    if ( ! current_user_can('edit_posts') &&  ! current_user_can('edit_pages') )
                    {
                        if ( $query->have_posts() ) 
                        {
                            require HD9_DIR_PATH . 'templates/plugin-display-reply-table.tpl.php';
                        }
                    }
                }
            }
        }

        /**
         * Plugin specific private functions can be found below.
         */

        // Load up the plugin scripts, stylesheets and all the action and filter hooks.
        private function init() 
        {
            if ( ! function_exists( 'is_plugin_active' ) ) 
            {
                function is_plugin_active( $plugin ) 
                {
                    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
                }
            }

            if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || ! is_plugin_active( 'flamingo/flamingo.php' ) ) 
            {
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

                deactivate_plugins( plugin_basename( self::$_plugin_file_path ) );

                add_action( 'admin_notices', function() {

                    ?>
                    <div class="notice notice-error">
                        <p>
                            <strong><?php echo HD9_NAME; ?></strong> is depnedent on both <strong>Conctact Form 7</strong> and <strong>Flamingo</strong>.<br /> The plugin will be deactivated automatically if either of these plugins are removed or deactivated.<br /> Please be sure that you have both plugins active in order for the plugin to be loaded.
                        </p>
                    </div>
                    <?php

                }, 10, 1 );
            }
            else
            {
                if ( is_admin() && false === get_option( 'developry_plugin_loaded' ) ) 
                {
                    parent::utilities();
                }
                else 
                {
                    $this->utilities();
                }

                $this->load_help_desk_9_actions();

                $this->customize_flamingo_inbound_messages_columns();
            }
        }

        private function load_help_desk_9_actions()
        {
            add_action( 'admin_enqueue_scripts', function() { 

                wp_enqueue_style( HD9_WPORG_SLUG, HD9_URL . '/assets/css/help-desk-9-admin.css', null, HD9_VERSION, 'all' );

                wp_enqueue_script( HD9_WPORG_SLUG, HD9_URL . '/assets/js/help-desk-9-admin.js', array( 'jquery' ), HD9_VERSION, true );

                wp_localize_script( HD9_WPORG_SLUG, 'wphd9', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

            }, 10, 1 );

            add_action( 'wp_enqueue_scripts', function() { 

                wp_enqueue_style( HD9_WPORG_SLUG, HD9_URL . '/assets/css/help-desk-9-front.css', null, HD9_VERSION, 'all' );

                wp_enqueue_script( HD9_WPORG_SLUG, HD9_URL . '/assets/js/help-desk-9-front.js', array( 'jquery' ), HD9_VERSION, true );

                wp_localize_script( HD9_WPORG_SLUG, 'wphd9', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

            }, 10, 1 );

            add_action( 'wpcf7_after_flamingo', function( $result ) { 

                if ( $result['status'] === 'mail_sent' ) 
                {
                    // Add empty reply meta after CF7 submission.
                    add_post_meta( $result['flamingo_inbound_id'], '_flamingo_inbound_reply_data', null ); 
                }
            }, 10, 1 );

            add_action( 'wp_ajax_hd9_post_reply',        array( $this, 'create_flamingo_inbound_message_reply' ) );
            add_action( 'wp_ajax_nopriv_hd9_post_reply', array( $this, 'create_flamingo_inbound_message_reply' ) );

            add_action( 'wp_ajax_hd9_action_close_status',        array( $this, 'change_flamingo_inbound_message_reply_status_closed' ) );
            add_action( 'wp_ajax_nopriv_hd9_action_close_status', array( $this, 'change_flamingo_inbound_message_reply_status_closed' ) );
        
            add_action( 'wp_ajax_hd9_action_reopen_status',        array( $this, 'change_flamingo_inbound_message_reply_status_opened' ) );
            add_action( 'wp_ajax_nopriv_hd9_action_reopen_status', array( $this, 'change_flamingo_inbound_message_reply_status_opened' ) );

            add_action( 'admin_menu', function() {

                add_submenu_page( 'flamingo', '', '', 'flamingo_edit_inbound_messages', 'flamingo_inbound', array( $this, 'extend_flamingo_inbound_single_message' ) );

            }, 10, 1 );

            add_shortcode( 'help-desk-9', array( $this, 'display_reply_table' ) );
        }
    }
}