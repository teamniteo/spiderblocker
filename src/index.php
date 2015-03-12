<?php
	namespace Niteoweb\SpiderBlocker;
	/**
	 * Plugin Name: Spider Blocker
	 * Description: Spider Blocker will block most common bots that consume bandwidth and slow down your server.
	 * Version:     1.0.2
	 * Author:      NiteoWeb Ltd.
	 * Author URI:  www.niteoweb.com
	 */

	if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
		?>
		<div id="error-page">
			<p>This plugin requires PHP 5.3.0 or higher. Please contact your hosting provider about upgrading your
				server software. Your PHP version is <b><?php echo PHP_VERSION; ?></b></p>
		</div>
		<?php
		die();
	}


	class Updater {
		public $current_version;
		public $plugin_slug;
		public $slug;
		private $update_url = 'https://api.github.com/repos/niteoweb/spiderblocker/releases/latest';

		function __construct() {
			$plugin_info = get_plugin_data( __FILE__, false );
			// Set the class public variables
			$this->current_version = $plugin_info["Version"];
			$this->plugin_slug     = plugin_basename( __FILE__ );

			list ( $t1, $t2 ) = explode( '/', $this->plugin_slug );
			$this->slug = str_replace( '.php', '', $t2 );

			// define the alternative API for updating checking
			add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'checkUpdate' ) );
		}

		/**
		 * @codeCoverageIgnore
		 */
		public static function activate() {
			new Updater();
		}

		public function checkUpdate( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			// Get the remote version
			$remote_version = $this->getRemoteInformation();
			if ( $remote_version ) {
				$version = str_replace( 'v', '', $remote_version->tag_name );
				// If a newer version is available, add the update
				if ( version_compare( $this->current_version, $version, '<' ) ) {
					$obj                                       = new \stdClass();
					$obj->slug                                 = $this->slug;
					$obj->new_version                          = $version;
					$obj->url                                  = $this->update_url;
					$obj->package                              = $remote_version->assets[0]->browser_download_url;
					$transient->response[ $this->plugin_slug ] = $obj;
				}
			}

			return $transient;
		}

		public function getRemoteInformation() {
			$request = wp_remote_get( $this->update_url );
			if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
				return json_decode( $request['body'], false );
			}

			return false;
		}

	}

	class SpiderBlocker {

		public $default_bots = 'a:35:{i:0;O:8:"stdClass":4:{s:4:"name";s:10:"Ahrefs Bot";s:2:"re";s:9:"AhrefsBot";s:4:"desc";s:25:"https://ahrefs.com/robot/";s:5:"state";b:1;}i:1;O:8:"stdClass":4:{s:4:"name";s:8:"MJ12 bot";s:2:"re";s:7:"MJ12bot";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:2;O:8:"stdClass":4:{s:4:"name";s:9:"Roger Bot";s:2:"re";s:8:"Rogerbot";s:4:"desc";s:40:"http://moz.com/help/pro/rogerbot-crawler";s:5:"state";b:1;}i:3;O:8:"stdClass":4:{s:4:"name";s:11:"Semrush Bot";s:2:"re";s:10:"SemrushBot";s:4:"desc";s:31:"http://www.semrush.com/bot.html";s:5:"state";b:1;}i:4;O:8:"stdClass":4:{s:4:"name";s:11:"ia_archiver";s:2:"re";s:11:"ia_archiver";s:4:"desc";s:36:"http://archive.org/about/exclude.php";s:5:"state";b:1;}i:5;O:8:"stdClass":4:{s:4:"name";s:8:"ScoutJet";s:2:"re";s:8:"ScoutJet";s:4:"desc";s:19:"http://scoutjet.com";s:5:"state";b:1;}i:6;O:8:"stdClass":4:{s:4:"name";s:7:"sistrix";s:2:"re";s:7:"sistrix";s:4:"desc";s:26:"http://crawler.sistrix.net";s:5:"state";b:1;}i:7;O:8:"stdClass":4:{s:4:"name";s:16:"SearchmetricsBot";s:2:"re";s:16:"SearchmetricsBot";s:4:"desc";s:50:"http://www.searchmetrics.com/en/searchmetrics-bot/";s:5:"state";b:1;}i:8;O:8:"stdClass":4:{s:4:"name";s:14:"SEOkicks-Robot";s:2:"re";s:14:"SEOkicks-Robot";s:4:"desc";s:33:"http://www.seokicks.de/robot.html";s:5:"state";b:1;}i:9;O:8:"stdClass":4:{s:4:"name";s:16:"Lipperhey Spider";s:2:"re";s:16:"Lipperhey Spider";s:4:"desc";s:43:"http://www.lipperhey.com/en/website-spider/";s:5:"state";b:1;}i:10;O:8:"stdClass":4:{s:4:"name";s:6:"Exabot";s:2:"re";s:6:"Exabot";s:4:"desc";s:44:"http://www.exalead.com/search/webmasterguide";s:5:"state";b:1;}i:11;O:8:"stdClass":4:{s:4:"name";s:6:"NC Bot";s:2:"re";s:5:"NCBot";s:4:"desc";s:55:"https://twitter.com/NetComber/status/334476871691550721";s:5:"state";b:1;}i:12;O:8:"stdClass":4:{s:4:"name";s:15:"BacklinkCrawler";s:2:"re";s:15:"BacklinkCrawler";s:4:"desc";s:40:"http://www.backlinktest.com/crawler.html";s:5:"state";b:1;}i:13;O:8:"stdClass":4:{s:4:"name";s:15:"archive.org Bot";s:2:"re";s:15:"archive.org_bot";s:4:"desc";s:42:"http://archive.org/details/archive.org_bot";s:5:"state";b:1;}i:14;O:8:"stdClass":4:{s:4:"name";s:12:"MeanPath Bot";s:2:"re";s:11:"meanpathbot";s:4:"desc";s:37:"https://meanpath.com/meanpathbot.html";s:5:"state";b:1;}i:15;O:8:"stdClass":4:{s:4:"name";s:18:"PagesInventory Bot";s:2:"re";s:14:"PagesInventory";s:4:"desc";s:56:"http://www.botsvsbrowsers.com/details/1002332/index.html";s:5:"state";b:1;}i:16;O:8:"stdClass":4:{s:4:"name";s:12:"Aboundex Bot";s:2:"re";s:11:"Aboundexbot";s:4:"desc";s:32:"http://www.aboundex.com/crawler/";s:5:"state";b:1;}i:17;O:8:"stdClass":4:{s:4:"name";s:15:"SeoProfiler Bot";s:2:"re";s:5:"spbot";s:4:"desc";s:31:"http://www.seoprofiler.com/bot/";s:5:"state";b:1;}i:18;O:8:"stdClass":4:{s:4:"name";s:11:"Linkdex Bot";s:2:"re";s:10:"linkdexbot";s:4:"desc";s:34:"http://www.linkdex.com/about/bots/";s:5:"state";b:1;}i:19;O:8:"stdClass":4:{s:4:"name";s:7:"Gigabot";s:2:"re";s:7:"Gigabot";s:4:"desc";s:45:"http://www.useragentstring.com/pages/Gigabot/";s:5:"state";b:1;}i:20;O:8:"stdClass":4:{s:4:"name";s:6:"DotBot";s:2:"re";s:6:"dotbot";s:4:"desc";s:35:"http://en.wikipedia.org/wiki/DotBot";s:5:"state";b:1;}i:21;O:8:"stdClass":4:{s:4:"name";s:5:"Nutch";s:2:"re";s:5:"Nutch";s:4:"desc";s:32:"http://nutch.apache.org/bot.html";s:5:"state";b:1;}i:22;O:8:"stdClass":4:{s:4:"name";s:8:"BLEX Bot";s:2:"re";s:7:"BLEXBot";s:4:"desc";s:27:"http://webmeup-crawler.com/";s:5:"state";b:1;}i:23;O:8:"stdClass":4:{s:4:"name";s:6:"Ezooms";s:2:"re";s:6:"Ezooms";s:4:"desc";s:49:"http://graphicline.co.za/blogs/what-is-ezooms-bot";s:5:"state";b:1;}i:24;O:8:"stdClass":4:{s:4:"name";s:11:"Majestic 12";s:2:"re";s:11:"Majestic-12";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:25;O:8:"stdClass":4:{s:4:"name";s:12:"Majestic SEO";s:2:"re";s:12:"Majestic-SEO";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:26;O:8:"stdClass":4:{s:4:"name";s:7:"DSearch";s:2:"re";s:7:"DSearch";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:27;O:8:"stdClass":4:{s:4:"name";s:10:"Blekko Bot";s:2:"re";s:9:"BlekkoBot";s:4:"desc";s:33:"http://blekko.com/about/blekkobot";s:5:"state";b:1;}i:28;O:8:"stdClass":4:{s:4:"name";s:6:"Yandex";s:2:"re";s:6:"Yandex";s:4:"desc";s:41:"http://help.yandex.com/search/?id=1112030";s:5:"state";b:0;}i:29;O:8:"stdClass":4:{s:4:"name";s:10:"Google Bot";s:2:"re";s:9:"googlebot";s:4:"desc";s:57:"https://support.google.com/webmasters/answer/182072?hl=en";s:5:"state";b:0;}i:30;O:8:"stdClass":4:{s:4:"name";s:18:"Feedfetcher Google";s:2:"re";s:18:"Feedfetcher-Google";s:4:"desc";s:51:"https://support.google.com/webmasters/answer/178852";s:5:"state";b:0;}i:31;O:8:"stdClass":4:{s:4:"name";s:8:"Bing Bot";s:2:"re";s:7:"BingBot";s:4:"desc";s:36:"http://en.wikipedia.org/wiki/Bingbot";s:5:"state";b:0;}i:32;O:8:"stdClass":4:{s:4:"name";s:9:"Nerdy Bot";s:2:"re";s:8:"NerdyBot";s:4:"desc";s:20:"http://nerdybot.com/";s:5:"state";b:1;}i:33;O:8:"stdClass":4:{s:4:"name";s:9:"James BOT";s:2:"re";s:8:"JamesBOT";s:4:"desc";s:32:"http://cognitiveseo.com/bot.html";s:5:"state";b:1;}i:34;O:8:"stdClass":4:{s:4:"name";s:7:"Tin Eye";s:2:"re";s:6:"TinEye";s:4:"desc";s:34:"http://www.tineye.com/crawler.html";s:5:"state";b:1;}}';
		protected $option_name = 'Niteoweb.SpiderBlocker.Bots';
		protected $nonce = 'Niteoweb.SpiderBlocker.Nonce';

		function __construct() {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( &$this, 'activatePluginNotice' ) );
				add_action( 'admin_menu', array( &$this, 'adminMenu' ) );
				add_action( 'wp_ajax_NSB-get_list', array( &$this, 'loadList' ) );
				add_action( 'wp_ajax_NSB-set_list', array( &$this, 'saveList' ) );
				add_action( 'wp_ajax_NSB-reset_list', array( &$this, 'resetList' ) );
			}
			add_action( 'generate_rewrite_rules', array( &$this, "generateRewriteRules" ) );
		}

		/**
		 * @codeCoverageIgnore
		 */
		static function isHtaccessWritable() {
			$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
			$htaccess_file = $home_path . '.htaccess';

			return is_writable( $htaccess_file );
		}

		function generateRewriteRules() {
			global $wp_rewrite;

			// Protect plugin from direct access
			$wp_rewrite->add_external_rule( 'wp-content/plugins/spider_blocker/index.php', 'index.php%{REQUEST_URI}' );
			$wp_rewrite->add_external_rule( 'wp-content/plugins/spider_blocker/readme.txt', 'index.php%{REQUEST_URI}' );
			$wp_rewrite->add_external_rule( 'wp-content/plugins/spider_blocker/', 'index.php%{REQUEST_URI}' );
		}

		function adminMenu() {
			add_management_page(
				'SpiderBlocker', 'SpiderBlocker', 'manage_options', 'ni_spider_block', array(
					&$this,
					'viewHandler'
				)
			);
		}

		/**
		 * @codeCoverageIgnore
		 */
		function activatePluginNotice() {
			if ( get_option( $this->option_name ) === false ) {
				update_option( $this->option_name, $this->default_bots );
				?>
				<div class="notice notice-success">
					<p>SpiderBlocker plugin has enabled blocking of some bots, please review settings by visiting <a
							href="<?php echo admin_url( 'tools.php?page=ni_spider_block' ); ?>">Setting page</a>!</p>
				</div>
			<?php
			}
		}

		/**
		 * @codeCoverageIgnore
		 */
		function activatePlugin() {
			$this->generateBlockRules();
		}

		function generateBlockRules() {
			global $wp_rewrite;

			$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
			$htaccess_file = $home_path . '.htaccess';

			if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) ) {
				if ( $this->modRewriteEnabled() ) {
					insert_with_markers( $htaccess_file, 'NiteowebSpiderBlocker', $this->getRules() );
				}
			}

			$wp_rewrite->flush_rules();
		}

		static function modRewriteEnabled() {
			return function_exists( 'apache_mod_loaded' ) ? apache_mod_loaded( 'mod_rewrite', false ) : false;
		}

		/**
		 * Generate block rules based on enabled bots
		 *
		 * @return array
		 */
		function getRules() {
			$list = array();
			foreach ( $this->getBots() as $bot ) {
				if ( $bot->state ) {
					$list[] = 'SetEnvIfNoCase User-Agent "' . $bot->re . '" block_bot';
				}

			}
			$list[] = "<Limit GET POST HEAD>";
			$list[] = "Order Allow,Deny";
			$list[] = "Allow from all";
			$list[] = "Deny from env=block_bot";
			$list[] = "</Limit>";

			return $list;
		}

		private function getBots() {
			return maybe_unserialize( get_option( $this->option_name, $this->default_bots ) );
		}

		function loadList() {
			check_ajax_referer( $this->nonce, 'nonce' );
			wp_send_json_success( $this->getBots() );
		}

		function resetList() {
			check_ajax_referer( $this->nonce, 'nonce' );
			delete_option( $this->option_name );
			$this->generateBlockRules();
			wp_send_json_success( $this->getBots() );
		}

		function removeBlockRules() {
			global $wp_rewrite;
			delete_option( $this->option_name );
			$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
			$htaccess_file = $home_path . '.htaccess';
			$empty         = array();
			if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) ) {
				if ( $this->modRewriteEnabled() ) {
					insert_with_markers( $htaccess_file, 'NiteowebSpiderBlocker', $empty );
				}
			}

			$wp_rewrite->flush_rules();
		}

		function saveList() {

			check_ajax_referer( $this->nonce, 'nonce' );
			$data = json_decode( stripcslashes( $_POST['data'] ) );

			if ( json_last_error() ) {
				if ( function_exists( 'json_last_error_msg' ) ) {
					wp_send_json_error( json_last_error_msg() );
				} else {
					wp_send_json_error( 'Failed parsing JSON' );
				}

			}
			if ( get_option( $this->option_name ) !== false ) {
				update_option( $this->option_name, maybe_serialize( $data ) );
			} else {
				add_option( $this->option_name, maybe_serialize( $data ), null, 'no' );
			}

			$this->generateBlockRules();
			wp_send_json_success( $this->getBots() );

		}

		/**
		 * @codeCoverageIgnore
		 */
		function viewHandler() {
			add_thickbox();
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

			<h1>Spider Blocker</h1>
			<hr/>
			<div ng-app="spiderBlockApp">
				<div ng-controller="NotificationsCtrl">
					<div class="notice notice-{{ n.state }} fixed" ng-repeat="n in notifications"
					     style="top: {{3.5*($index+1)}}em">
						<p>{{n.msg}}
							<a ng-click="removeNotification(notification)">
								<span class="dashicons dashicons-no-alt"></span>
							</a>
						</p>
					</div>
				</div>


				<div ng-controller="BotListCtrl">
					<h2>Add New Bot</h2>

					<form name="add_form" ng-submit="add()">
						<table class="form-table">
							<tbody>
							<tr>
								<th scope="row"><label>User Agent</label></th>
								<td><input bots="bots" ng-model='bot.re' class="regular-text"
								           required/></td>
							</tr>
							<tr>
								<th scope="row"><label>Bot Name</label></th>
								<td><input type="text" ng-model='bot.name' class="regular-text" required/></td>
							</tr>
							<tr>
							<tr>
								<th scope="row"><label>Bot Description URL</label></th>
								<td><input type="url" ng-model='bot.desc' class="regular-text" placeholder="http://"/>
								</td>
							</tr>
							</tbody>
						</table>
						<p class="submit"><input ng-disabled="add_form.$invalid" type="submit"
						                         class="button button-primary" value="Add Bot"></p>
					</form>
					<h2>List of bots</h2>
					<ng-form class="search-box">
						<input size="35" type="search" id="ua-search-input" ng-model="query" placeholder="Filter...">
					</ng-form>
					<table class="wp-list-table widefat bots">
						<thead>
						<tr>

							<th scope="col" class="manage-column column-description">
								<a href=""
								   ng-click="predicate = 're'; reverse=false">User
									Agent <span class="dashicons dashicons-sort"></span></a></th>

							<th scope="col" class="manage-column column-name">Name</th>
							<th scope="col" class="manage-column column-state">
								<a href=""
								   ng-click="predicate = 'state'; reverse=false">State <span
										class="dashicons dashicons-sort"></span></a>
							</th>
							<th scope="col" id="action" class="manage-column column-action">Action</th>
						</tr>
						</thead>

						<tfoot>
						<tr>

							<th scope="col" class="manage-column column-description"><a href=""
							                                                            ng-click="predicate = 're'; reverse=false">User
									Agent</a></th>

							<th scope="col" class="manage-column column-name">Name</th>
							<th scope="col" class="manage-column column-state"><a href=""
							                                                      ng-click="predicate = 'state'; reverse=false">State</a>
							</th>
							<th scope="col" id="action" class="manage-column column-action">Action</th>
						</tr>
						</tfoot>

						<tbody id="the-list">
						<tr id="spider-blocker" ng-repeat="bot in bots | filter:query | orderBy:predicate:reverse"
						    ng-class="{'active': bot.state}">

							<th class="bot-re"> {{ bot.re }}</th>
							<td class="bot-title"><strong>{{ bot.name }}</strong> <a target="_blank"
							                                                         ng-href="{{bot.desc}}">{{
									bot.desc }}</a></td>
							<th class="expression" ng-class="{'blocked':bot.state}"> {{ bot.state?"Blocked":"Allowed"
								}}
							</th>
							<td class="actions">
								<input ng-hide="bot.state" type="button" ng-click="bot.state=true"
								       class="button button-primary" value="Block">
								<input ng-show="bot.state" type="button" ng-click="bot.state=false"
								       class="button button-secondary" value="Allow">
								<input type="button" ng-click="remove(bot.re)" class="button button-secondary"
								       value="Remove">
							</td>
						</tr>
						</tbody>
					</table>
					<div id="rules-export-import" style="display:none;">
        <textarea
	        style="-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;width: 100%;height: 99%;"
	        json-text ng-model="bots"></textarea>
					</div>
					<p class="submit">
						<input type="button" class="button button-primary" ng-click="save()" value="Save">
						<input type="button" class="button button-primary" ng-click="reset()" value="Reset to Defaults">
						<a href="#TB_inline?width=540&height=360&inlineId=rules-export-import"
						   class="thickbox button button-secondary">Import/Export Definitions</a>
					</p>

				</div>

				<script type="text/javascript"
				        src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.13/angular.min.js"></script>
				<script type="text/javascript"
				        src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.13/angular-aria.min.js"></script>
				<script type="text/javascript">
					-(function () {
						var spiderBlockApp = angular.module('spiderBlockApp', ['ngAria']);

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
								_req.nonce = '<?php echo wp_create_nonce($this->nonce); ?>';
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
			</div>
		<?php
		}
	}

	// Inside WordPress
	if ( defined( 'ABSPATH' ) ) {
		if ( ! apache_get_version() || ! SpiderBlocker::modRewriteEnabled() ) {
			?>
			<div id="error-page">
				<p>This plugin requires Apache2 server with mod_rewrite support. Please contact your hosting provider
					about
					upgrading your server software. Your Apache version is <b><?php echo apache_get_version(); ?></b>
				</p>
			</div>
			<?php
			die();
		}

		if ( ! SpiderBlocker::isHtaccessWritable() ) {
			?>
			<div id="error-page">
				<p>This plugin requires <b>.htaccess</b> file that is writable by the server. Please enable write access
					for file <b><?php echo ABSPATH . '.htaccess'; ?></b>.</p>
			</div>
			<?php
			die();
		}

		$NiteowebSpiderBlocker_ins = new SpiderBlocker;
		register_activation_hook( __FILE__, array( &$NiteowebSpiderBlocker_ins, 'activatePlugin' ) );
		register_deactivation_hook( __FILE__, array( &$NiteowebSpiderBlocker_ins, 'removeBlockRules' ) );
		add_action( 'admin_init', array( 'Niteoweb\SpiderBlocker\Updater', 'activate' ) );

	}



