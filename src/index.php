<?php

namespace Niteoweb\SpiderBlocker;

/**
 * Plugin Name: Spider Blocker
 * Description: Spider Blocker will block most common bots that consume bandwidth and slow down your server.
 * Version:     1.0.19
 * Runtime:     5.3+
 * Author:      Easy Blog Networks
 * Text Domain: spiderblocker
 * Domain Path: /langs/
 * Author URI:  www.easyblognetworks.com
 */

if ( ! function_exists( 'apache_get_version' ) ) {
	/**
	 * Fetch the Apache version.
	 *
	 * @return string|false
	 */
	function apache_get_version() {
		if ( stristr( $_ENV['SERVER_SOFTWARE'], 'Apache' ) ) {
			return sanitize_text_field( $_ENV['SERVER_SOFTWARE'] );
		}
		if ( stristr( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) ) {
			return sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] );
		}
		return false;
	}
}

/**
 * Checks for PHP version and stop the plugin if the version is < 5.3.0.
 */
if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
	?>
	<div id="error-page">
		<p>
		<?php
		esc_html_e(
			'This plugin requires PHP 5.3.0 or higher. Please contact your hosting provider about upgrading your
			server software. Your PHP version is', 'spiderblocker'
		);
		?>
		<b><?php echo sanitize_text_field( PHP_VERSION ); ?></b></p>
	</div>
	<?php
	die();
}

/**
 * Spiderblocker class where all the action happens.
 *
 * @package WordPress
 * @subpackage spiderblocker
 */
class SpiderBlocker {

	const OPTIONNAME = 'Niteoweb.SpiderBlocker.Bots';
	const NONCE      = 'Niteoweb.SpiderBlocker.Nonce';
	const CHECKHOOK  = 'Niteoweb.SpiderBlocker.CheckHook';

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
		if ( is_admin() ) {
			add_action( 'admin_notices', array( &$this, 'activate_plugin_notice' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_ajax_NSB-get_list', array( &$this, 'load_list' ) );
			add_action( 'wp_ajax_NSB-set_list', array( &$this, 'save_list' ) );
			add_action( 'wp_ajax_NSB-reset_list', array( &$this, 'reset_list' ) );
		}

		// Filter for Robots file.
		add_filter( 'robots_txt', array( &$this, 'robots_file' ), ~PHP_INT_MAX, 2 );
		add_action( 'generate_rewrite_rules', array( &$this, 'generate_rewrite_rules' ) );

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
			'SpiderBlocker', 'SpiderBlocker', 'manage_options', 'ni_spider_block', array( &$this, 'view_handler' )
		);

		add_action( 'load-' . $menu, array( &$this, 'view_handler_load' ) );
	}

	/**
	 * Admin notice which gets fired on plugin activation.
	 *
	 * @codeCoverageIgnore
	 */
	public function activate_plugin_notice() {
		if ( get_option( self::OPTIONNAME ) === false ) {
			update_option( self::OPTIONNAME, $this->default_bots );
			?>
			<div class="notice notice-success">
				<p><?php esc_html_e( 'SpiderBlocker plugin has enabled blocking of some bots, please review settings by visiting', 'spiderblocker' ); ?> <a href="<?php echo esc_url( admin_url( 'tools.php?page=ni_spider_block' ) ); ?>"><?php esc_html_e( 'Setting page', 'spiderblocker' ); ?></a>!</p>
			</div>
			<?php
		}
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
		if ( ! apache_get_version() ) {
			?>
			<div id="error-page">
				<p><?php esc_html_e( 'This plugin requires Apache2 server with mod_rewrite support. Please contact your hosting provider about upgrading your server software. Your Apache version is', 'spiderblocker' ); ?> <b><?php echo esc_html( apache_get_version() ); ?></b></p>
			</div>
			<?php
			die();
		}

		if ( ! SpiderBlocker::is_htaccess_writable() ) {
			$state = SpiderBlocker::chmod_htaccess();
			if ( ! SpiderBlocker::is_htaccess_writable() || ! $state ) {
				?>
				<div id="error-page">
					<p>
					<?php
					printf(
						esc_html__( '%1$s %2$s %3$s', 'spiderblocker' ),
						esc_html__( 'This plugin requires', 'spiderblocker' ),
						'<b>.htaccess</b>',
						esc_html__( 'file that is writable by the server. Please enable write access for file', 'spiderblocker' )
					);
					?>
						<b><?php echo esc_html( ABSPATH . '.htaccess' ); ?></b>.</p>
				</div>
				<?php
				die();
			}
		}
		$this->generate_block_rules();

	}

	/**
	 * Check if the .htaccess file is writable.
	 *
	 * @return bool
	 * @codeCoverageIgnore
	 */
	private static function is_htaccess_writable() {
		$htaccess_file = SpiderBlocker::join_paths( ABSPATH, '.htaccess' );
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
		?>
		<style>
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
		</style>

		<script>
			window.sb_nonce = "<?php echo esc_html( wp_create_nonce( self::NONCE ) ); ?>";
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
						_req.nonce = window.sb_nonce;
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
									msg: 'List of bots was saved and new blocklist applied!'
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
								msg: 'List of bots was reset to defaults!'
							});
						});
					};

					$scope.add = function () {
						$scope.bots.push($scope.bot);
						$rootScope.$emit('notification', {
							state: 'success',
							msg: 'Bot ' + $scope.bot.name + ' was added!'
						});
						$scope.bot = {"state": true};
					};

					$scope.remove = function (at) {
						$rootScope.$emit('notification', {state: 'success', msg: 'Bot was removed!'});
						$scope.bots.splice(find_bot(at), 1);
					};
				});
			})(angular, document, jQuery);
		</script>
		<h1><?php esc_html_e( 'Spider Blocker', 'spiderblocker' ); ?></h1>
		<hr/>
		<div ng-app="spiderBlockApp">
			<div ng-controller="NotificationsCtrl">
				<div class="notice notice-{{ n.state }} fixed" ng-repeat="n in notifications" style="top: {{3.5*($index+1)}}em">
					<p>{{n.msg}}
						<a ng-click="removeNotification(notification)">
							<span class="dashicons dashicons-no-alt"></span>
						</a>
					</p>
				</div>
			</div>

			<div ng-controller="BotListCtrl">
				<h2><?php esc_html_e( 'Add New Bot', 'spiderblocker' ); ?></h2>

				<form name="add_form" ng-submit="add()">
					<table class="form-table">
						<tbody>
						<tr>
							<th scope="row"><label><?php esc_html_e( 'User Agent', 'spiderblocker' ); ?></label></th>
							<td><input bots="bots" ng-model='bot.re' class="regular-text" required/></td>
						</tr>
						<tr>
							<th scope="row"><label><?php esc_html_e( 'Bot Name', 'spiderblocker' ); ?></label></th>
							<td><input type="text" ng-model='bot.name' class="regular-text" required/></td>
						</tr>
						<tr>
						<tr>
							<th scope="row"><label><?php esc_html_e( 'Bot Description URL', 'spiderblocker' ); ?></label></th>
							<td><input type="url" ng-model='bot.desc' class="regular-text" placeholder="http://"/>
							</td>
						</tr>
						</tbody>
					</table>

					<p class="submit"><input ng-disabled="add_form.$invalid" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Add Bot', 'spiderblocker' ); ?>"></p>
				</form>

				<h2><?php esc_html_e( 'List of bots', 'spiderblocker' ); ?></h2>
				<ng-form class="search-box">
					<input size="35" type="search" id="ua-search-input" ng-model="query" placeholder="<?php esc_attr_e( 'Filter...', 'spiderblocker' ); ?>">
				</ng-form>

				<table class="wp-list-table widefat bots">
					<thead>
					<tr>
						<th scope="col" class="manage-column column-description">
							<a href="" ng-click="predicate = 're'; reverse=false"><?php esc_html_e( 'User Agent', 'spiderblocker' ); ?> <span class="dashicons dashicons-sort"></span></a>
						</th>
						<th scope="col" class="manage-column column-name"><?php esc_html_e( 'Name', 'spiderblocker' ); ?></th>
						<th scope="col" class="manage-column column-state">
							<a href="" ng-click="predicate = 'state'; reverse=false"><?php esc_html_e( 'State', 'spiderblocker' ); ?> <span class="dashicons dashicons-sort"></span></a>
						</th>
						<th scope="col" id="action" class="manage-column column-action"><?php esc_html_e( 'Action', 'spiderblocker' ); ?></th>
					</tr>
					</thead>

					<tfoot>
					<tr>
						<th scope="col" class="manage-column column-description"><a href="" ng-click="predicate = 're'; reverse=false"><?php esc_html_e( 'User Agent', 'spiderblocker' ); ?></a></th>
						<th scope="col" class="manage-column column-name"><?php esc_html_e( 'Name', 'spiderblocker' ); ?></th>
						<th scope="col" class="manage-column column-state"><a href="" ng-click="predicate = 'state'; reverse=false"><?php esc_html_e( 'State', 'spiderblocker' ); ?></a>
						</th>
						<th scope="col" id="action" class="manage-column column-action"><?php esc_html_e( 'Action', 'spiderblocker' ); ?></th>
					</tr>
					</tfoot>

					<tbody id="the-list">
					<tr id="spider-blocker" ng-repeat="bot in bots | filter:query | orderBy:predicate:reverse"
						ng-class="{'active': bot.state}">

						<th class="bot-re"> {{ bot.re }}</th>
						<td class="bot-title"><strong>{{ bot.name }}</strong> <a target="_blank" ng-href="{{bot.desc}}">{{ bot.desc }}</a></td>
						<th class="expression" ng-class="{'blocked':bot.state}"> {{ bot.state?"Blocked":"Allowed" }}</th>
						<td class="actions">
							<input ng-hide="bot.state" type="button" ng-click="bot.state=true" class="button button-primary" value="Block">
							<input ng-show="bot.state" type="button" ng-click="bot.state=false" class="button button-secondary" value="Allow">
							<input type="button" ng-click="remove(bot.re)" class="button button-secondary" value="Remove">
						</td>
					</tr>
					</tbody>
				</table>

				<div id="rules-export-import" style="display:none;">
					<textarea style="-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;width: 100%;height: 99%;" json-text ng-model="bots"></textarea>
				</div>

				<p class="submit">
					<input type="button" class="button button-primary" ng-click="save()" value="<?php esc_attr_e( 'Save', 'spiderblocker' ); ?>">
					<input type="button" class="button button-primary" ng-click="reset()" value="<?php esc_attr_e( 'Reset to Defaults', 'spiderblocker' ); ?>">
					<a href="#TB_inline?width=540&height=360&inlineId=rules-export-import" class="thickbox button button-secondary"><?php esc_html_e( 'Import/Export Definitions', 'spiderblocker' ); ?></a>
				</p>
			</div>
		</div>
		<?php
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
		wp_register_script( 'spiderblocker-angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js', array( 'jquery' ), '1.0.18', false );
		wp_enqueue_script( 'spiderblocker-angular' );
		wp_enqueue_media( 'thickbox' );
	}

}

// Initialize Plugin.
if ( defined( 'ABSPATH' ) ) {
	$niteo_spider_blocker = new SpiderBlocker();

	add_action( 'upgrader_process_complete', array( &$niteo_spider_blocker, 'on_plugin_upgrade' ), 10, 2 );

	// Activation Hook.
	register_activation_hook( __FILE__, array( &$niteo_spider_blocker, 'activate_plugin' ) );

	// Deactivation Hook.
	register_deactivation_hook( __FILE__, array( &$niteo_spider_blocker, 'remove_block_rules' ) );
}
