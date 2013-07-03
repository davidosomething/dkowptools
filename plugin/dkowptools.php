<?php
/**
 * Plugin Name: DKO WP Tools
 * Plugin URI:  http://github.com/davidosomething/
 * Description: Show browser resolution in admin bar
 * Author:      davidosomething
 * Version:     0.1.0
 * Author URI:  http://www.davidosomething.com/
 */

if (!class_exists('DKOWPAdmin')) require_once 'lib/admin.php';

// singleton!
class DKOWPTools extends DKOWPAdmin
{
  // plugin meta
  protected $version      = '0.1.0';
  protected $plugin_file  = __FILE__;
  protected $slug         = 'DKOWPTools';

  // Singleton instance
  public static $instance = null;

  // Options
  protected $default_options = array(
    'version'       => '0.0.0',
    'use_root_node' => FALSE,
  );

  private $root_node = null;

  // Admin vars
  protected $screen_id; // check user on correct screen with this
  protected $main_page; // submit forms here
  protected $admin_messages = array();
  // menu
  protected $menu_title     = 'WP Tools';
  protected $menu_access    = 'manage_options';
  protected $menu_icon      = 'http://s.gravatar.com/avatar/dcf949116994998753bd171a74f20fe9?s=16';
  protected $menu_position;

////////////////////////////////////////////////////////////////////////////////
// Init ////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

  /**
   * __construct
   *
   * @return void
   */
  public function __construct() {
    global $wpdb;
    self::$instance = $this;

    // initialize vars
    $this->screen_id = 'toplevel_page_' . $this->slug;
    $this->main_page = admin_url('admin.php?page=' . $this->slug);
    $this->menu_position = (string)('500.' .substr(base_convert(md5($this->slug), 16, 10) , -5));

    $this->_setup_options();

    // Make sure db is created/up-to-date
    register_activation_hook(__FILE__, array($this, 'ensure_version'));
    add_action('plugins_loaded', array($this, 'ensure_version'));

    // Add admin page and help
    // add_action('admin_menu', array($this, 'add_root_menu'));
    // add_action('admin_notices', array($this, 'admin_notices'));

    add_action('admin_bar_menu', array($this, 'admin_bar_resolution'), 21);
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
  }


////////////////////////////////////////////////////////////////////////////////
// Boilerplate /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

  /**
   * get_instance
   * Return the static instance of DKOVotables
   *
   * @return object
   */
  public static function get_instance() {
    if (is_null(self::$instance)) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * _setup_options
   * Get and merge stored options with defaults.
   *
   * @return void
   */
  private function _setup_options() {
    $options = get_option('dkovotables_options');
    if (empty($options)) {
      $options = array();
    }
    $this->options = wp_parse_args($options, $this->default_options);
  }

  /**
   * ensure_version
   * Compare the activated version to this file. Update database if not same.
   *
   * @return void
   */
  public function ensure_version() {
    $installed_version = $this->options['version'];
    if ($installed_version !== $this->version) {
      $this->_update_database();
    }
  }

  private function _update_database() {
  }

////////////////////////////////////////////////////////////////////////////////
// Backend /////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
  public function is_dkowpconfig_menu_enabled($wp_admin_bar) {
    // already checked?
    if ($this->root_node) {
      return TRUE;
    }

    // check for dkowpconfig
    $root_node = $wp_admin_bar->get_node('dkowpconfig_display_environment');
    if ($root_node) {
      $this->root_node = 'dkowpconfig_display_environment';
      return TRUE;
    }

    return FALSE;
  }

  public function add_root_node($wp_admin_bar) {
    $args = array(
      'id'    => 'dkowptools',
      'title' => '<b style="font-weight:700;">DKOWPTools</b>',
      'href'  => admin_url()
    );
    $wp_admin_bar->add_node($args);
    $this->root_node = 'dkowptools';
    return $this->root_node;
  }

  public function admin_bar_resolution($wp_admin_bar) {
    if (!is_admin_bar_showing()) return;

    $args = array(
      'id'     => 'dkowptools_resolution',
      'title'  => 'Res: <span class="ab-item dkowptools_resolution">js disabled?</span>'
    );

    if ($this->options['use_root_node']) {
      $this->root_node || $this->is_dkowpconfig_menu_enabled($wp_admin_bar) || $this->add_root_node($wp_admin_bar);
      $args['parent'] = $this->root_node;
    }

    $wp_admin_bar->add_node($args);
  }

  public function enqueue_scripts() {
    wp_enqueue_script('dkowptools', plugins_url('/assets/js/script.js', __FILE__));
  }
}
$dkowptools = DKOWPTools::get_instance();
