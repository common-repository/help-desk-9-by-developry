<?php
/*
  Plugin Name:  WP Customer Support 
  Plugin URI:   https://developry.com/developry-help-desk-9
  Description:  Support ticket system built on top of Contact Form 7 and Flamingo. 
  Version:      1.0.1
  Author:       Developry
  Author URI:   https://developry.com/
  License:      GNU General Public License, version 2
  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain:  developry-help-desk-9
*/

defined('ABSPATH') || exit;

define('HD9_WPORG_SLUG',      'developry-help-desk-9');
define('HD9_TEXT_DOMAIN',     'developry-help-desk-9');
define('HD9_NAME',            'WP Customer Support');
define('HD9_HOME_PAGE',       'https://developry.com/');
define('HD9_DOC_PAGE',        HD9_HOME_PAGE . 'developry-help-desk-9');
define('HD9_FILE_PATH',       __FILE__);
define('HD9_DIR_PATH',        plugin_dir_path(__FILE__));
define('HD9_URL',             plugins_url('', __FILE__) . '/');
define('HD9_VERSION',         '1.0.1');
define('HD9_MIN_PHP_VERSION', '7.2');
define('HD9_MIN_WP_VERSION',  '5.1.0');

require_once HD9_DIR_PATH . '/classes/Help_Desk_9.class.php';

use Developry\HelpDesk9 as HelpDesk9;

new HelpDesk9\Help_Desk_9(HD9_FILE_PATH);
