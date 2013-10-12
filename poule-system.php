<?php

	/*
	  Plugin Name: Poule tournament
	  Plugin URI: http://stefandebruin.eu/plugin-poule
	  Description: Wordpress plugin to set you online poule tournament system
	  Version: 1.1
	  Author: Stefan de Bruin
	  Author URI: http://stefandebruin.eu
	 */

	class Pouletournament {

		/**
		 * constructor
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing
		 */
		public function __construct() {
			define('POULE_PATH', plugin_dir_path(__FILE__));

			$this->includes();

			add_action('admin_menu', array($this, 'create_nav'));
			add_action('admin_head', array($this, 'add_css'));
			add_action('admin_head', array($this, 'add_js'));

			add_action('wp_head', array($this, 'add_js'));

			$plugin_dir = basename(dirname(__FILE__)) . "/languages/";
			load_plugin_textdomain('poule-system', null, $plugin_dir);

			add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'));
		}

		/**
		 * Function that aad the widget
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing
		 */
		public function add_dashboard_widgets() {
			$dashboard = new dashboard();

			wp_add_dashboard_widget('poule-system', __('Poule score', 'poule-system'), array($dashboard, 'home'), array($dashboard, 'config'));
		}

		/**
		 * Function that create the navigation
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing
		 */
		public function create_nav() {
			$plugin_name = "poule";

			$class = new poule_url();

			add_menu_page($plugin_name, $plugin_name, 'manage_options', 'podium', array($class, 'Load'));

			$pages = array('countries', 'matches', 'score');
			foreach ($pages as $page) {
				add_submenu_page('podium', __($page, 'poule-system'), __($page, 'poule-system'), 'manage_options', $page, array($class, 'Load'));
			}

			add_options_page('podium', __('poule settings', 'poule-system'), 'manage_options', 'poulesettings', array($class, 'Load'));
		}

		/**
		 * Function that unclude required files
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing
		 */
		public function includes() {
			require_once(POULE_PATH . 'core/poule-url.php');
			require_once(POULE_PATH . 'core/functions.php');
			require_once(POULE_PATH . 'poule-admin/classes/dashboard.php');
			require_once(POULE_PATH . 'includes/functions.php');
		}

		/**
		 * Create the database tables and activate the plugin
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing
		 */
		public function activate() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$sql_codes = file_get_contents(POULE_PATH . 'core/sql_file_poule-system.sql', FILE_USE_INCLUDE_PATH);

			foreach (explode(";", $sql_codes) as $key => $value) {
				preg_match_all("|`(.*)`|U", $value, $match);
				$table_name = $wpdb->prefix . $match[1][0];
				$sql = str_replace($match[1][0], $table_name, $value);
				dbDelta($sql);
			}

			require_once(POULE_PATH . 'core/poule-install.php');
			poule_install();
		}

		/**
		 * Deactivate the plugin
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		public function deactivate() {
			//delete the tables
		}

		/**
		 * Function that add the css files
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		function add_css() {
			$siteurl = get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__));
			wp_register_style('poule-dtpicker', $siteurl . '/css/jquery.simple-dtpicker.css');
			wp_enqueue_style('poule-dtpicker');
			
			wp_register_style('poule-stylesheet', $siteurl . '/css/stylesheet.css');
			wp_enqueue_style('poule-stylesheet');
		}

		/**
		 * Function that add the css files
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		function add_js() {
			$siteurl = get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__));
			wp_register_script('poule-dtpicker', $siteurl . '/js/jquery.simple-dtpicker.js');
			wp_enqueue_script('poule-dtpicker');

			wp_register_script('poule-js', $siteurl . '/js/poule.js');
			wp_enqueue_script('poule-js');
		}

	}

	$poule = new Pouletournament();

	register_activation_hook(__FILE__, array($poule, 'activate'));
?>

