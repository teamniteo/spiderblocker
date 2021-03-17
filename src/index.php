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
	const PLUGIN_NAME = 'Spider Blocker';

	/**
	 * @var string
	 */
	const PLUGIN_BASE = 'spiderblocker/index.php';

	/**
	 * @var string
	 */
	const PLUGIN_VERSION = '@##VERSION##@';

	/**
	 * @var string
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * @var string
	 */
	const MINIMUM_WP_VERSION = '4.2.0';

	/**
	 * @var string
	 */
	const OPTIONNAME = 'Niteoweb.SpiderBlocker.Bots';

	/**
	 * @var string
	 */
	const NONCE = 'Niteoweb.SpiderBlocker.Nonce';

	/**
	 * @var string
	 */
	const CHECKHOOK = 'Niteoweb.SpiderBlocker.CheckHook';

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
			'name'  => 'Baidu',
			're'    => 'Baiduspider',
			'desc'  => 'http://www.baidu.com/search/robots_english.html',
			'state' => true,
		),
		array(
			'name'  => 'Serpstat',
			're'    => 'serpstat',
			'desc'  => 'https://serpstat.com/',
			'state' => true,
		),
		array(
			'name'  => 'SpyFu',
			're'    => 'spyfu',
			'desc'  => 'https://www.spyfu.com/',
			'state' => true,
		),
		array(
			'name'  => 'Prlog',
			're'    => 'Prlog',
			'desc'  => 'https://prlog.ru/',
			'state' => true,
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
		if ( ! $this->get_server_software( 'Apache' ) && ! $this->get_server_software( 'LiteSpeed' ) ) {
			$this->deactivate_plugin();
			$this->add_admin_notice(
				'no_apache',
				'error',
				sprintf(
					esc_html__( '%s requires Apache2 or LiteSpeed server with mod_rewrite support. Please contact your hosting provider about upgrading your server software.', 'spiderblocker' ),
					$this->get_plugin_name()
				)
			);
		}

		// Write permission for .htaccess
		if ( ! $this->is_htaccess_writable() || ! $this->chmod_htaccess() ) {
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
	 */
	public function plugin_url() {
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

		if ( ! isset( $_POST['data'] ) ) {
			wp_send_json_error( esc_html__( 'Unable to process the request as no data has been received.', 'spiderblocker' ) );
			return;
		}

		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( esc_html__( 'Unable to process the request as no data has been received.', 'spiderblocker' ) );
			return;
		}

		$data = json_decode( stripcslashes( $_POST['data'] ), true );

		if ( json_last_error() ) {
			wp_send_json_error( esc_html__( 'Failed parsing JSON data.', 'spiderblocker' ) );
			return;
		}

		update_option( self::OPTIONNAME, maybe_serialize( $data ), 'no' );

		// Add rule to .htaccess file
		$this->generate_block_rules();

		// Update robots_txt file
		add_filter( 'robots_txt', array( &$this, 'robots_file' ), ~PHP_INT_MAX, 2 );

		// Send success response
		wp_send_json_success( $this->get_bots() );
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
		// Load admin assets
		$this->admin_css();
		$this->admin_js();

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
		wp_enqueue_script( 'spiderblocker-admin', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js', array( 'jquery' ), self::PLUGIN_VERSION, false );

		$localize = array(
			'nonce'           => wp_create_nonce( self::NONCE ),
			'save_text'       => esc_html__( 'List of bots was saved and new blocklist applied!', 'spiderblocker' ),
			'save_reset_text' => esc_html__( 'List of bots was reset to defaults!', 'spiderblocker' ),
			'bot_text'        => esc_html__( 'Bot', 'spiderblocker' ),
			'added_text'      => esc_html__( 'was added!', 'spiderblocker' ),
			'removed_text'    => esc_html__( 'Bot was removed!', 'spiderblocker' ),
		);

		// Pass data to JS
		wp_localize_script( 'spiderblocker-admin', 'sb_i18n', $localize );

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
	}

	/**
	 * CSS for settings panel.
	 *
	 * @codeCoverageIgnore
	 */
	public function admin_css() {
		?>
			<style type="text/css">
				.spiderblocker-root {
					margin-right: 20px;
				}
				.sb-table-top {
					display: flex;
					align-items: center;
				}
				.search-box {
					margin-left: auto;
				}
				.form-table th {
					padding: 10px 10px 10px 0;
				}
				.form-table td {
					padding: 10px;
				}
				.notice.fixed {
					position: fixed;
					right: 1em;
					top: 3.5em;
				}
				tr.active {
					background-color: rgba(54, 204, 255, 0.05);
				}
				.active th.bot-re {
					border-left: 4px solid #2ea2cc;
				}

				@media (max-width: 782px) {
					.spiderblocker-root {
						margin-right: 12px;
					}
					.form-table td {
						padding: 10px 0;
					}
				}
			</style>
		<?php
	}

	/**
	 * JS for settings panel.
	 *
	 * @codeCoverageIgnore
	 */
	public function admin_js() {
		?>
		<script type="text/javascript">
			-(function () {
				var spiderBlockApp = angular.module('spiderBlockApp', []);
			
				spiderBlockApp.directive('jsonText', function () {
					return {
						restrict: 'A',
						require: 'ngModel',
						link: function (scope, element, attr, ngModel) {
							function into(input) {
								return angular.fromJson(input);
							}
			
							function out(data) {
								return angular.toJson(data, true);
							}
			
							ngModel.$parsers.push(into);
							ngModel.$formatters.push(out);
						}
					};
				});
				spiderBlockApp.controller('NotificationsCtrl', function ($scope, $rootScope, $timeout) {
					$scope.notifications = [];
			
					$rootScope.$on('notification', function (event, data) {
						$scope.notifications.push(data);
						$timeout(function () {
							$scope.removeNotification(data);
						}, 3000);
					});
			
					$scope.removeNotification = function (notification) {
						var index;
						if ($scope.notifications !== undefined) {
							index = $scope.notifications.indexOf(notification);
							$scope.notifications.splice(index, 1);
						}
					}
				});
				spiderBlockApp.controller('BotListCtrl', function ($scope, $http, $rootScope) {
					var wp_ajax = function (_req) {
						_req.nonce = sb_i18n.nonce;
			
						return $http({
							method: 'POST',
							url: ajaxurl,
							data: jQuery.param(_req),
							headers: {'Content-Type': 'application/x-www-form-urlencoded'}
						})
					};
			
					var find_bot = function (re) {
						for (var i = $scope.bots.length - 1; i >= 0; i--) {
							if ($scope.bots[i]['re'] == re) {
								return i;
							}
						}
						return null;
					};

					$scope.bot = {"state": true};
			
					wp_ajax({
						action: 'NSB-get_list'
					}).success(function (res) {
						$scope.bots = res.data;
					});
			
					$scope.save = function () {
						wp_ajax({
							action: 'NSB-set_list',
							data: angular.toJson($scope.bots)
						}).success(function (res) {
							if (res.success) {
								$scope.bots = res.data;
								$rootScope.$emit('notification', {
									state: 'success',
									msg: sb_i18n.save_text
								});
							} else {
								$rootScope.$emit('notification', {state: 'errror', msg: res.data});
							}
						});
					};
			
					$scope.reset = function () {
						wp_ajax({
							action: 'NSB-reset_list'
						}).success(function (res) {
							$scope.bots = res.data;
							$rootScope.$emit('notification', {
								state: 'success',
								msg: sb_i18n.save_reset_text
							});
						});
					};
			
					$scope.add = function () {
						$scope.bots.push($scope.bot);
						$rootScope.$emit('notification', {
							state: 'success',
							msg: sb_i18n.bot_text + ' ' + $scope.bot.name + ' ' + sb_i18n.added_text
						});
						$scope.bot = {"state": true};
					};
			
					$scope.remove = function (at) {
						$rootScope.$emit('notification', {state: 'success', msg: sb_i18n.removed_text});
						$scope.bots.splice(find_bot(at), 1);
					};
				});
			})(angular, document, jQuery);
		</script>
		<?php
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
	 * Fetch the Apache version.
	 *
	 * @return string|false
	 */
	public function get_server_software( $server ) {
		// Check Apache
		if ( stristr( $_ENV['SERVER_SOFTWARE'], $server ) ) {
			return sanitize_text_field( $_ENV['SERVER_SOFTWARE'] );
		}

		if ( stristr( $_SERVER['SERVER_SOFTWARE'], $server ) ) {
			return sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] );
		}

		return false;
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
	 * Change file permission of the .htaccess file.
	 *
	 * @param int $mod octet value for chmod.
	 * @return bool
	 * @codeCoverageIgnore
	 */
	protected function chmod_htaccess( $mod = 0644 ) {
		$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
		$htaccess_file = $home_path . '.htaccess';

		return chmod( $htaccess_file, $mod );
	}

	/**
	 * Check if the .htaccess file is writable.
	 *
	 * @return bool
	 * @codeCoverageIgnore
	 */
	protected function is_htaccess_writable() {
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

}

// Initlaize if called from within WordPress
if ( defined( 'ABSPATH' ) ) {
	$spiderblocker = new SpiderBlocker();

	// Register hooks
	add_action( 'upgrader_process_complete', array( $spiderblocker, 'on_plugin_upgrade' ), 10, 2 );

	// Runs on plugin activation & de-activation
	register_activation_hook( __FILE__, array( $spiderblocker, 'activate_plugin' ) );
	register_deactivation_hook( __FILE__, array( $spiderblocker, 'remove_block_rules' ) );
}
