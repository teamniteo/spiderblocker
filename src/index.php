<?php

namespace Niteoweb\SpiderBlocker;

/**
 * Plugin Name: Spider Blocker
 * Description: Spider Blocker will block most common bots that consume bandwidth and slow down your server.
 * Version:     @##VERSION##@
 * Runtime:     5.6+
 * Author:      Easy Blog Networks
 * Text Domain: spiderblocker
 * Domain Path: i18n
 * Author URI:  www.easyblognetworks.com
 */

// Exit if ABSPATH is not defined
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Spiderblocker class where all the action happens.
 *
 * @package WordPress
 * @subpackage spiderblocker
 */
class SpiderBlocker {

	/**
	 * @var string
	 */
	public const PLUGIN_NAME = 'Spider Blocker';

	/**
	 * @var string
	 */
	public const PLUGIN_BASE = 'spiderblocker/index.php';

	/**
	 * @var string
	 */
	public const PLUGIN_VERSION = '@##VERSION##@';

	/**
	 * @var string
	 */
	public const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * @var string
	 */
	public const MINIMUM_WP_VERSION = '4.2.0';

	/**
	 * @var string
	 */
	public const OPTIONNAME = 'Niteoweb.SpiderBlocker.Bots';

	/**
	 * @var string
	 */
	public const NONCE = 'Niteoweb.SpiderBlocker.Nonce';

	/**
	 * @var string
	 */
	public const CHECKHOOK = 'Niteoweb.SpiderBlocker.CheckHook';

	/**
	 * @var array
	 */
	public $notices = array();

	/**
	 * Bots which can be blocked by the spiderblocker plugin
	 *
	 * @var array $default_bots Array of bots
	 */
	private $default_bots = array(
		array(
			'name'  => 'Ahrefs Bot',
			're'    => 'AhrefsBot',
			'desc'  => 'https://ahrefs.com/robot/',
			'state' => true,
		),
		array(
			'name'  => 'MJ12 bot',
			're'    => 'MJ12bot',
			'desc'  => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
			'state' => true,
		),
		array(
			'name'  => 'Roger Bot',
			're'    => 'Rogerbot',
			'desc'  => 'http://moz.com/help/pro/rogerbot-crawler',
			'state' => true,
		),
		array(
			'name'  => 'Semrush Bot',
			're'    => 'SemrushBot',
			'desc'  => 'http://www.semrush.com/bot.html',
			'state' => true,
		),
		array(
			'name'  => 'ia_archiver',
			're'    => 'ia_archiver',
			'desc'  => 'http://archive.org/about/exclude.php',
			'state' => true,
		),
		array(
			'name'  => 'ScoutJet',
			're'    => 'ScoutJet',
			'desc'  => 'http://scoutjet.com',
			'state' => true,
		),
		array(
			'name'  => 'sistrix',
			're'    => 'sistrix',
			'desc'  => 'http://crawler.sistrix.net',
			'state' => true,
		),
		array(
			'name'  => 'SearchmetricsBot',
			're'    => 'SearchmetricsBot',
			'desc'  => 'http://www.searchmetrics.com/en/searchmetrics-bot/',
			'state' => true,
		),
		array(
			'name'  => 'SEOkicks-Robot',
			're'    => 'SEOkicks-Robot',
			'desc'  => 'http://www.seokicks.de/robot.html',
			'state' => true,
		),
		array(
			'name'  => 'Lipperhey Spider',
			're'    => 'Lipperhey Spider',
			'desc'  => 'http://www.lipperhey.com/en/website-spider/',
			'state' => true,
		),
		array(
			'name'  => 'Exabot',
			're'    => 'Exabot',
			'desc'  => 'http://www.exalead.com/search/webmasterguide',
			'state' => true,
		),
		array(
			'name'  => 'NC Bot',
			're'    => 'NCBot',
			'desc'  => 'https://twitter.com/NetComber/status/334476871691550721',
			'state' => true,
		),
		array(
			'name'  => 'BacklinkCrawler',
			're'    => 'BacklinkCrawler',
			'desc'  => 'http://www.backlinktest.com/crawler.html',
			'state' => true,
		),
		array(
			'name'  => 'archive.org Bot',
			're'    => 'archive.org_bot',
			'desc'  => 'http://archive.org/details/archive.org_bot',
			'state' => true,
		),
		array(
			'name'  => 'MeanPath Bot',
			're'    => 'meanpathbot',
			'desc'  => 'https://meanpath.com/meanpathbot.html',
			'state' => true,
		),
		array(
			'name'  => 'PagesInventory Bot',
			're'    => 'PagesInventory',
			'desc'  => 'http://www.botsvsbrowsers.com/details/1002332/index.html',
			'state' => true,
		),
		array(
			'name'  => 'Aboundex Bot',
			're'    => 'Aboundexbot',
			'desc'  => 'http://www.aboundex.com/crawler/',
			'state' => true,
		),
		array(
			'name'  => 'SeoProfiler Bot',
			're'    => 'spbot',
			'desc'  => 'http://www.seoprofiler.com/bot/',
			'state' => true,
		),
		array(
			'name'  => 'Linkdex Bot',
			're'    => 'linkdexbot',
			'desc'  => 'http://www.linkdex.com/about/bots/',
			'state' => true,
		),
		array(
			'name'  => 'Gigabot',
			're'    => 'Gigabot',
			'desc'  => 'http://www.useragentstring.com/pages/Gigabot/',
			'state' => true,
		),
		array(
			'name'  => 'DotBot',
			're'    => 'dotbot',
			'desc'  => 'http://en.wikipedia.org/wiki/DotBot',
			'state' => true,
		),
		array(
			'name'  => 'Nutch',
			're'    => 'Nutch',
			'desc'  => 'http://nutch.apache.org/bot.html',
			'state' => true,
		),
		array(
			'name'  => 'BLEX Bot',
			're'    => 'BLEXBot',
			'desc'  => 'http://webmeup-crawler.com/',
			'state' => true,
		),
		array(
			'name'  => 'Ezooms',
			're'    => 'Ezooms',
			'desc'  => 'http://graphicline.co.za/blogs/what-is-ezooms-bot',
			'state' => true,
		),
		array(
			'name'  => 'Majestic 12',
			're'    => 'Majestic-12',
			'desc'  => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
			'state' => true,
		),
		array(
			'name'  => 'Majestic SEO',
			're'    => 'Majestic-SEO',
			'desc'  => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
			'state' => true,
		),
		array(
			'name'  => 'DSearch',
			're'    => 'DSearch',
			'desc'  => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
			'state' => true,
		),
		array(
			'name'  => 'MJ12',
			're'    => 'MJ12',
			'desc'  => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
			'state' => true,
		),
		array(
			'name'  => 'Blekko Bot',
			're'    => 'BlekkoBot',
			'desc'  => 'http://blekko.com/about/blekkobot',
			'state' => true,
		),
		array(
			'name'  => 'Yandex',
			're'    => 'Yandex',
			'desc'  => 'http://help.yandex.com/search/?id=1112030',
			'state' => false,
		),
		array(
			'name'  => 'Google Bot',
			're'    => 'googlebot',
			'desc'  => 'https://support.google.com/webmasters/answer/182072?hl=en',
			'state' => false,
		),
		array(
			'name'  => 'Feedfetcher Google',
			're'    => 'Feedfetcher-Google',
			'desc'  => 'https://support.google.com/webmasters/answer/178852',
			'state' => false,
		),
		array(
			'name'  => 'Bing Bot',
			're'    => 'BingBot',
			'desc'  => 'http://en.wikipedia.org/wiki/Bingbot',
			'state' => false,
		),
		array(
			'name'  => 'Nerdy Bot',
			're'    => 'NerdyBot',
			'desc'  => 'http://nerdybot.com/',
			'state' => true,
		),
		array(
			'name'  => 'James BOT',
			're'    => 'JamesBOT',
			'desc'  => 'http://cognitiveseo.com/bot.html',
			'state' => true,
		),
		array(
			'name'  => 'Tin Eye',
			're'    => 'TinEye',
			'desc'  => 'http://www.tineye.com/crawler.html',
			'state' => true,
		),
		array(
			'state' => true,
			're'    => 'Baiduspider',
			'name'  => 'Baidu',
			'desc'  => 'http://www.baidu.com/search/robots_english.html',
		),
		array(
			'state' => true,
			're'    => 'serpstat',
			'name'  => 'Serpstat',
			'desc'  => 'https://serpstat.com/',
		),
		array(
			'state' => true,
			'desc'  => 'https://www.spyfu.com/',
			're'    => 'spyfu',
			'name'  => 'SpyFu',
		),
	);

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_filter( 'robots_txt', array( &$this, 'robots_file' ), ~PHP_INT_MAX, 2 );

		add_action( 'admin_init', array( $this, 'check_environment' ) );
		add_action( 'admin_init', array( $this, 'check_server' ) );
		add_action( 'admin_init', array( $this, 'add_plugin_notices' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_NSB-get_list', array( $this, 'load_list' ) );
		add_action( 'wp_ajax_NSB-set_list', array( $this, 'save_list' ) );
		add_action( 'wp_ajax_NSB-reset_list', array( $this, 'reset_list' ) );
		add_action( 'generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ) );
	}

	/**
	 * Checks the environment on loading WordPress, just in case the environment changes after activation.
	 */
	public function check_environment() {
		if ( $this->is_environment_compatible() ) {
			return;
		}

		$this->deactivate_plugin();
		$this->add_admin_notice( 'bad_environment', 'error', $this->get_plugin_name() . ' has been deactivated. ' . $this->get_environment_message() );
	}

	/**
	 * Checks for server and .htaccess write permissions.
	 */
	public function check_server() {
		// Check Apache version
		if ( ! $this->get_server_software() ) {
			$this->deactivate_plugin();
			$this->add_admin_notice(
				'no_apache',
				'error',
				sprintf(
					esc_html__( '%s requires Apache2 server with mod_rewrite support. Please contact your hosting provider about upgrading your server software.', 'spiderblocker' ),
					$this->get_plugin_name()
				)
			);
		}

		// Write permission for .htaccess
		$state = self::chmod_htaccess();

		if ( ! self::is_htaccess_writable() || ! $state ) {
			$this->deactivate_plugin();
			$this->add_admin_notice(
				'no_htaccess',
				'error',
				sprintf(
					esc_html__( '%s requires .htaccess file that is writable by the server. Please enable write access for the file.', 'spiderblocker' ),
					$this->get_plugin_name()
				)
			);
		}
	}

	/**
	 * Adds notices for out-of-date WordPress and/or WooCommerce versions.
	 */
	public function add_plugin_notices() {
		// Check for WP version
		if ( ! $this->is_wp_compatible() ) {
			$this->add_admin_notice(
				'update_wordpress',
				'error',
				sprintf(
					esc_html__( '%1$s requires WordPress version %2$s or higher. Please %3$supdate WordPress &raquo;%4$s', 'spiderblocker' ),
					$this->get_plugin_name(),
					$this->get_wp_version(),
					'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">',
					'</a>'
				)
			);
		}

		// Check for plugin activation
		if ( $this->activate_plugin_notice() ) {
			$this->add_admin_notice(
				'plugin_activated',
				'success',
				sprintf(
					esc_html__( '%1$s plugin has enabled blocking of some bots, please review settings by visiting %2$sSettings page%3$s!', 'spiderblocker' ),
					$this->get_plugin_name(),
					'<a href="' . esc_url( admin_url( 'tools.php?page=ni_spider_block' ) ) . '">',
					'</a>'
				)
			);
		}
	}

	/**
	 * Admin notice which gets fired on plugin activation.
	 *
	 * @codeCoverageIgnore
	 */
	public function activate_plugin_notice() {
		if ( get_option( self::OPTIONNAME, false ) ) {
			return false;
		}

		// Add option to DB and return true
		update_option( self::OPTIONNAME, $this->default_bots );

		return true;
	}

	/**
	 * Displays any admin notices added with add_admin_notice()
	 */
	public function admin_notices() {
		foreach ( (array) $this->notices as $notice_key => $notice ) {
			echo '<div class="notice notice-' . esc_attr( $notice['class'] ) . '">';
			echo '<p>' . wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) ) . '</p>';
			echo '</div>';
		}
	}

	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @param string $slug the slug for the notice
	 * @param string $class the css class for the notice
	 * @param string $message the notice message
	 */
	private function add_admin_notice( $slug, $class, $message ) {
		$this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message,
		);
	}

	/**
	 * Protect plugin from direct access.
	 *
	 * @param type $wp_rewrite Class for managing the rewrite rules.
	 */
	public function generate_rewrite_rules( $wp_rewrite ) {
		$wp_rewrite->add_external_rule( $this->plugin_url() . 'index.php', 'index.php%{REQUEST_URI}' );
		$wp_rewrite->add_external_rule( $this->plugin_url() . 'readme.txt', 'index.php%{REQUEST_URI}' );
		$wp_rewrite->add_external_rule( $this->plugin_url(), 'index.php%{REQUEST_URI}' );
	}

	/**
	 * Fetch Plugin URL.
	 *
	 * @return string
	 * @codeCoverageIgnore
	 */
	private function plugin_url() {
		$url = wp_make_link_relative( plugin_dir_url( __FILE__ ) );
		$url = ltrim( $url, '/' );

		return $url;
	}

	/**
	 * Add submenu page to the Tools main menu.
	 */
	public function admin_menu() {
		$menu = add_management_page(
			'SpiderBlocker',
			'SpiderBlocker',
			'manage_options',
			'ni_spider_block',
			array( &$this, 'view_handler' )
		);

		add_action( 'load-' . $menu, array( &$this, 'view_handler_load' ) );
	}

	/**
	 * Generate block rules when the download process for a plugin
	 * install or update finishes.
	 *
	 * @codeCoverageIgnore
	 */
	public function on_plugin_upgrade() {
		$this->generate_block_rules();
	}

	/**
	 * This gets fired on plugin activation.
	 *
	 * @codeCoverageIgnore
	 */
	public function activate_plugin() {
		$this->generate_block_rules();
	}

	/**
	 * Check if the .htaccess file is writable.
	 *
	 * @return bool
	 * @codeCoverageIgnore
	 */
	private static function is_htaccess_writable() {
		$htaccess_file = self::join_paths( ABSPATH, '.htaccess' );
		return is_writable( $htaccess_file );
	}

	/**
	 * Function to join the supplied arguments together.
	 *
	 * @return string
	 * @codeCoverageIgnore
	 */
	private static function join_paths() {
		$paths = array();

		foreach ( func_get_args() as $arg ) {
			if ( '' !== $arg ) {
				$paths[] = $arg;
			}
		}

		return preg_replace( '#/+#', '/', join( '/', $paths ) );
	}

	/**
	 * Change file permission of the .htaccess file.
	 *
	 * @param int $mod octet value for chmod.
	 * @return bool
	 * @codeCoverageIgnore
	 */
	private static function chmod_htaccess( $mod = 0644 ) {
		$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
		$htaccess_file = $home_path . '.htaccess';

		return chmod( $htaccess_file, $mod );
	}

	/**
	 * Add our rules for bots in the .htaccess file.
	 */
	public function generate_block_rules() {
		global $wp_rewrite;

		$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
		$htaccess_file = $home_path . '.htaccess';

		if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) ) {
			insert_with_markers( $htaccess_file, 'NiteowebSpiderBlocker', $this->get_rules() );
		}

		$wp_rewrite->flush_rules();
	}

	/**
	 * Generate block rules based on enabled bots.
	 *
	 * @return array
	 */
	public function get_rules() {
		$list = array();
		foreach ( $this->get_bots() as $bot ) {
			if ( is_array( $bot ) ) {
				$bot = (object) $bot;
			}
			if ( $bot->state ) {
				$list[] = 'SetEnvIfNoCase User-Agent "' . $bot->re . '" block_bot';
			}
		}
		$list[] = '<Limit GET POST HEAD>';
		$list[] = 'Order Allow,Deny';
		$list[] = 'Allow from all';
		$list[] = 'Deny from env=block_bot';
		$list[] = '</Limit>';

		return $list;
	}

	/**
	 * Get the list of bots from the database or return default
	 * list if nothing found in the database.
	 *
	 * @return array
	 */
	public function get_bots() {
		$data = get_option( self::OPTIONNAME );
		if ( $data ) {
			return maybe_unserialize( $data );
		}
		return $this->default_bots;
	}

	/**
	 * Gets called via AJAX to return the the list of bots.
	 */
	public function load_list() {
		check_ajax_referer( self::NONCE, 'nonce' );
		wp_send_json_success( $this->get_bots() );
	}

	/**
	 * Gets called via AJAX to return the the list of default bots.
	 */
	public function reset_list() {
		check_ajax_referer( self::NONCE, 'nonce' );
		delete_option( self::OPTIONNAME );
		$this->generate_block_rules();
		add_filter( 'robots_txt', array( &$this, 'robots_file' ), ~PHP_INT_MAX, 2 );
		wp_send_json_success( $this->get_bots() );
	}

	/**
	 * Remove our bot rules from the .htaccess file.
	 */
	public function remove_block_rules() {
		global $wp_rewrite;

		delete_option( self::OPTIONNAME );
		$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
		$htaccess_file = $home_path . '.htaccess';
		$empty         = array();
		if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) ) {
			insert_with_markers( $htaccess_file, 'NiteowebSpiderBlocker', $empty );
		}

		$wp_rewrite->flush_rules();
	}

	/**
	 * Gets called via AJAX to update the bots list in the database.
	 */
	public function save_list() {
		check_ajax_referer( self::NONCE, 'nonce' );
		if ( isset( $_POST['data'] ) && '' !== $_POST['data'] ) {
			$data = json_decode( stripcslashes( $_POST['data'] ), true );

			if ( json_last_error() ) {
				if ( function_exists( 'json_last_error_msg' ) ) {
					wp_send_json_error( json_last_error_msg() );
				} else {
					wp_send_json_error( esc_html__( 'Failed parsing JSON', 'spiderblocker' ) );
				}
			}

			if ( get_option( self::OPTIONNAME ) !== false ) {
				update_option( self::OPTIONNAME, maybe_serialize( $data ) );
			} else {
				add_option( self::OPTIONNAME, maybe_serialize( $data ), '', 'no' );
			}

			$this->generate_block_rules();
			add_filter( 'robots_txt', array( &$this, 'robots_file' ), ~PHP_INT_MAX, 2 );
			wp_send_json_success( $this->get_bots() );
		} else {
			wp_send_json_error( esc_html__( 'Unable to process the request as no data has been received.', 'spiderblocker' ) );
		}
	}

	/**
	 * This function is supplied to robots_txt filter to add bots block
	 * rules to the robots.txt file.
	 *
	 * @param string $output Output of the robots.txt file before our additions.
	 * @param bool   $public Blog's public status fetched from the database.
	 */
	public function robots_file( $output, $public ) {
		foreach ( $this->get_bots() as $bot ) {
			if ( is_array( $bot ) ) {
				$bot = (object) $bot;
			}

			if ( ! empty( $bot->state ) ) {
				$output .= sprintf( "User-agent: %s\n", $bot->re );
				$output .= "Disallow: /\n";
				$output .= "\n";
			}
		}

		return $output;
	}

	/**
	 * Plugin HTML is rendered via this function.
	 *
	 * @codeCoverageIgnore
	 */
	public function view_handler() {
		include __DIR__ . '/inc/templates/settings.php';
	}

	/**
	 * Adds action for admin scripts.
	 */
	public function view_handler_load() {
		add_action( 'admin_enqueue_scripts', array( &$this, 'view_handler_scripts' ) );
	}

	/**
	 * Registers & Enqueues the admin scripts for the view_handler() function.
	 */
	public function view_handler_scripts() {
		wp_enqueue_script( 'spiderblocker-angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js', array( 'jquery' ), self::PLUGIN_VERSION, false );
		wp_enqueue_script( 'spiderblocker-js', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array( 'spiderblocker-angular' ), self::PLUGIN_VERSION, false );

		wp_enqueue_style( 'spiderblocker-css', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), self::PLUGIN_VERSION );

		$localize = array(
			'nonce'           => wp_create_nonce( self::NONCE ),
			'save_text'       => esc_html__( 'List of bots was saved and new blocklist applied!', 'spiderblocker' ),
			'save_reset_text' => esc_html__( 'List of bots was reset to defaults!', 'spiderblocker' ),
			'bot_text'        => esc_html__( 'Bot', 'spiderblocker' ),
			'added_text'      => esc_html__( 'was added!', 'spiderblocker' ),
			'removed_text'    => esc_html__( 'Bot was removed!', 'spiderblocker' ),
		);

		// Pass data to JS
		wp_localize_script( 'spiderblocker-js', 'sb_i18n', $localize );

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
	}

	/**
	 * Determines if the server environment is compatible with this plugin.
	 *
	 * @return bool
	 */
	public function is_environment_compatible() {
		return version_compare( PHP_VERSION, $this->get_php_version(), '>=' );
	}

	/**
	 * Gets the message for display when the environment is incompatible with this plugin.
	 *
	 * @return string
	 */
	public function get_environment_message() {
		return sprintf(
			esc_html__( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'eu-vat-b2b-taxes' ),
			$this->get_php_version(),
			PHP_VERSION
		);
	}

	/**
	 * Determines if the WordPress compatible.
	 *
	 * @return bool
	 */
	public function is_wp_compatible() {
		if ( ! $this->get_wp_version() ) {
			return true;
		}

		return version_compare( get_bloginfo( 'version' ), $this->get_wp_version(), '>=' );
	}

	/**
	 * Returns PLUGIN_NAME.
	 */
	public function get_plugin_name() {
		return self::PLUGIN_NAME;
	}

	/**
	 * Returns PLUGIN_BASE.
	 */
	public function get_plugin_base() {
		return self::PLUGIN_BASE;
	}

	/**
	 * Returns MINIMUM_PHP_VERSION.
	 */
	public function get_php_version() {
		return self::MINIMUM_PHP_VERSION;
	}

	/**
	 * Returns MINIMUM_WP_VERSION.
	 */
	public function get_wp_version() {
		return self::MINIMUM_WP_VERSION;
	}

	/**
	 * Deactivates the plugin.
	 */
	protected function deactivate_plugin() {
		deactivate_plugins( $this->get_plugin_base() );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * Fetch the Apache version.
	 *
	 * @return string|false
	 */
	protected function get_server_software() {
		if ( stristr( $_ENV['SERVER_SOFTWARE'], 'Apache' ) ) {
			return sanitize_text_field( $_ENV['SERVER_SOFTWARE'] );
		}

		if ( stristr( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) ) {
			return sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] );
		}

		return false;
	}

}

// Initialize plugin and register hooks
$spiderblocker = new SpiderBlocker();

add_action( 'upgrader_process_complete', array( $spiderblocker, 'on_plugin_upgrade' ), 10, 2 );

// Runs on plugin activation & de-activation
register_activation_hook( __FILE__, array( $spiderblocker, 'activate_plugin' ) );
register_deactivation_hook( __FILE__, array( $spiderblocker, 'remove_block_rules' ) );
