<?php

	/**
	 *
	 * Plugin Name: Spider Blocker
	 * Description: Spider Blocker will block most common bots that consume bandwidth and slow down your server.
	 * Version:     1.0.0
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

	class NiteowebSpiderBlocker {

		protected $option_name = 'Niteoweb.SpiderBlocker.Bots';
		protected $nonce = 'Niteoweb.SpiderBlocker.Nonce';
		protected $default_bots = 'a:35:{i:0;O:8:"stdClass":4:{s:4:"name";s:10:"Ahrefs Bot";s:2:"re";s:9:"AhrefsBot";s:4:"desc";s:25:"https://ahrefs.com/robot/";s:5:"state";b:1;}i:1;O:8:"stdClass":4:{s:4:"name";s:8:"MJ12 bot";s:2:"re";s:7:"MJ12bot";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:2;O:8:"stdClass":4:{s:4:"name";s:9:"Roger Bot";s:2:"re";s:8:"Rogerbot";s:4:"desc";s:40:"http://moz.com/help/pro/rogerbot-crawler";s:5:"state";b:1;}i:3;O:8:"stdClass":4:{s:4:"name";s:11:"Semrush Bot";s:2:"re";s:10:"SemrushBot";s:4:"desc";s:31:"http://www.semrush.com/bot.html";s:5:"state";b:1;}i:4;O:8:"stdClass":4:{s:4:"name";s:11:"ia_archiver";s:2:"re";s:11:"ia_archiver";s:4:"desc";s:36:"http://archive.org/about/exclude.php";s:5:"state";b:1;}i:5;O:8:"stdClass":4:{s:4:"name";s:8:"ScoutJet";s:2:"re";s:8:"ScoutJet";s:4:"desc";s:19:"http://scoutjet.com";s:5:"state";b:1;}i:6;O:8:"stdClass":4:{s:4:"name";s:7:"sistrix";s:2:"re";s:7:"sistrix";s:4:"desc";s:26:"http://crawler.sistrix.net";s:5:"state";b:1;}i:7;O:8:"stdClass":4:{s:4:"name";s:16:"SearchmetricsBot";s:2:"re";s:16:"SearchmetricsBot";s:4:"desc";s:50:"http://www.searchmetrics.com/en/searchmetrics-bot/";s:5:"state";b:1;}i:8;O:8:"stdClass":4:{s:4:"name";s:14:"SEOkicks-Robot";s:2:"re";s:14:"SEOkicks-Robot";s:4:"desc";s:33:"http://www.seokicks.de/robot.html";s:5:"state";b:1;}i:9;O:8:"stdClass":4:{s:4:"name";s:16:"Lipperhey Spider";s:2:"re";s:16:"Lipperhey Spider";s:4:"desc";s:43:"http://www.lipperhey.com/en/website-spider/";s:5:"state";b:1;}i:10;O:8:"stdClass":4:{s:4:"name";s:6:"Exabot";s:2:"re";s:6:"Exabot";s:4:"desc";s:44:"http://www.exalead.com/search/webmasterguide";s:5:"state";b:1;}i:11;O:8:"stdClass":4:{s:4:"name";s:6:"NC Bot";s:2:"re";s:5:"NCBot";s:4:"desc";s:55:"https://twitter.com/NetComber/status/334476871691550721";s:5:"state";b:1;}i:12;O:8:"stdClass":4:{s:4:"name";s:15:"BacklinkCrawler";s:2:"re";s:15:"BacklinkCrawler";s:4:"desc";s:40:"http://www.backlinktest.com/crawler.html";s:5:"state";b:1;}i:13;O:8:"stdClass":4:{s:4:"name";s:15:"archive.org Bot";s:2:"re";s:15:"archive.org_bot";s:4:"desc";s:42:"http://archive.org/details/archive.org_bot";s:5:"state";b:1;}i:14;O:8:"stdClass":4:{s:4:"name";s:12:"MeanPath Bot";s:2:"re";s:11:"meanpathbot";s:4:"desc";s:37:"https://meanpath.com/meanpathbot.html";s:5:"state";b:1;}i:15;O:8:"stdClass":4:{s:4:"name";s:18:"PagesInventory Bot";s:2:"re";s:14:"PagesInventory";s:4:"desc";s:56:"http://www.botsvsbrowsers.com/details/1002332/index.html";s:5:"state";b:1;}i:16;O:8:"stdClass":4:{s:4:"name";s:12:"Aboundex Bot";s:2:"re";s:11:"Aboundexbot";s:4:"desc";s:32:"http://www.aboundex.com/crawler/";s:5:"state";b:1;}i:17;O:8:"stdClass":4:{s:4:"name";s:15:"SeoProfiler Bot";s:2:"re";s:5:"spbot";s:4:"desc";s:31:"http://www.seoprofiler.com/bot/";s:5:"state";b:1;}i:18;O:8:"stdClass":4:{s:4:"name";s:11:"Linkdex Bot";s:2:"re";s:10:"linkdexbot";s:4:"desc";s:34:"http://www.linkdex.com/about/bots/";s:5:"state";b:1;}i:19;O:8:"stdClass":4:{s:4:"name";s:7:"Gigabot";s:2:"re";s:7:"Gigabot";s:4:"desc";s:45:"http://www.useragentstring.com/pages/Gigabot/";s:5:"state";b:1;}i:20;O:8:"stdClass":4:{s:4:"name";s:6:"DotBot";s:2:"re";s:6:"dotbot";s:4:"desc";s:35:"http://en.wikipedia.org/wiki/DotBot";s:5:"state";b:1;}i:21;O:8:"stdClass":4:{s:4:"name";s:5:"Nutch";s:2:"re";s:5:"Nutch";s:4:"desc";s:32:"http://nutch.apache.org/bot.html";s:5:"state";b:1;}i:22;O:8:"stdClass":4:{s:4:"name";s:8:"BLEX Bot";s:2:"re";s:7:"BLEXBot";s:4:"desc";s:27:"http://webmeup-crawler.com/";s:5:"state";b:1;}i:23;O:8:"stdClass":4:{s:4:"name";s:6:"Ezooms";s:2:"re";s:6:"Ezooms";s:4:"desc";s:49:"http://graphicline.co.za/blogs/what-is-ezooms-bot";s:5:"state";b:1;}i:24;O:8:"stdClass":4:{s:4:"name";s:11:"Majestic 12";s:2:"re";s:11:"Majestic-12";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:25;O:8:"stdClass":4:{s:4:"name";s:12:"Majestic SEO";s:2:"re";s:12:"Majestic-SEO";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:26;O:8:"stdClass":4:{s:4:"name";s:7:"DSearch";s:2:"re";s:7:"DSearch";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:27;O:8:"stdClass":4:{s:4:"name";s:10:"Blekko Bot";s:2:"re";s:9:"BlekkoBot";s:4:"desc";s:33:"http://blekko.com/about/blekkobot";s:5:"state";b:1;}i:28;O:8:"stdClass":4:{s:4:"name";s:6:"Yandex";s:2:"re";s:6:"Yandex";s:4:"desc";s:41:"http://help.yandex.com/search/?id=1112030";s:5:"state";b:0;}i:29;O:8:"stdClass":4:{s:4:"name";s:10:"Google Bot";s:2:"re";s:9:"googlebot";s:4:"desc";s:57:"https://support.google.com/webmasters/answer/182072?hl=en";s:5:"state";b:0;}i:30;O:8:"stdClass":4:{s:4:"name";s:18:"Feedfetcher Google";s:2:"re";s:18:"Feedfetcher-Google";s:4:"desc";s:51:"https://support.google.com/webmasters/answer/178852";s:5:"state";b:0;}i:31;O:8:"stdClass":4:{s:4:"name";s:8:"Bing Bot";s:2:"re";s:7:"BingBot";s:4:"desc";s:36:"http://en.wikipedia.org/wiki/Bingbot";s:5:"state";b:0;}i:32;O:8:"stdClass":4:{s:4:"name";s:9:"Nerdy Bot";s:2:"re";s:8:"NerdyBot";s:4:"desc";s:20:"http://nerdybot.com/";s:5:"state";b:1;}i:33;O:8:"stdClass":4:{s:4:"name";s:9:"James BOT";s:2:"re";s:8:"JamesBOT";s:4:"desc";s:32:"http://cognitiveseo.com/bot.html";s:5:"state";b:1;}i:34;O:8:"stdClass":4:{s:4:"name";s:7:"Tin Eye";s:2:"re";s:6:"TinEye";s:4:"desc";s:34:"http://www.tineye.com/crawler.html";s:5:"state";b:1;}}';

		function __construct() {
			if ( is_admin() ) {
				add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
				add_action( 'wp_ajax_NSB-get_list', array( &$this, 'load_list' ) );
				add_action( 'wp_ajax_NSB-set_list', array( &$this, 'save_list' ) );
				add_action( 'wp_ajax_NSB-reset_list', array( &$this, 'reset_list' ) );
			}

			add_action( 'generate_rewrite_rules', array( &$this, "generate_rewrite_rules" ) );
			add_action( 'generate_rewrite_rules', array( &$this, "generate_block_rules" ) );

		}

		function generate_block_rules( $content ) {

			$home_path     = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
			$htaccess_file = $home_path . '.htaccess';

			if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) ) {
				if ( $this->mod_rewrite_enabled() ) {
					return insert_with_markers( $htaccess_file, 'NiteowebSpiderBlocker', $this->get_rules() );
				}
			}

			return $content;
		}

		static function mod_rewrite_enabled() {
			return function_exists( 'apache_mod_loaded' ) ? apache_mod_loaded( 'mod_rewrite', false ) : false;
		}

		private function get_rules() {
			$list = array();
			foreach ( $this->get_bots() as $bot ) {
				$list[] = 'SetEnvIfNoCase User-Agent "' . $bot->re . '" block_bot';
			}
			$list[] = "<Limit GET POST HEAD>";
			$list[] = "Order Allow,Deny";
			$list[] = "Allow from all";
			$list[] = "Deny from env=block_bot";
			$list[] = "</Limit>";

			return $list;
		}

		private function get_bots() {
			return maybe_unserialize( get_option( $this->option_name, $this->default_bots ) );
		}

		function generate_rewrite_rules() {
			global $wp_rewrite;

			// Protect plugin from direct access
			$wp_rewrite->add_external_rule( 'wp-content/plugins/spider_blocker/index.php', 'index.php%{REQUEST_URI}' );
			$wp_rewrite->add_external_rule( 'wp-content/plugins/spider_blocker/', 'index.php%{REQUEST_URI}' );
		}

		function admin_menu() {
			add_management_page( 'SpiderBlocker', 'SpiderBlocker', 'manage_options', 'ni_spider_block', array(
				$this,
				'view_handler'
			) );
		}

		function load_list() {
			check_ajax_referer( $this->nonce, 'nonce' );
			wp_send_json_success( json_encode( $this->get_bots() ) );
		}

		function reset_list() {
			check_ajax_referer( $this->nonce, 'nonce' );
			delete_option( $this->option_name );
			wp_send_json_success( json_encode( $this->get_bots() ) );
		}

		function save_list() {
			check_ajax_referer( $this->nonce, 'nonce' );
			$data = json_decode( sanitize_text_field( $_POST['data'] ) );
			if ( get_option( $this->option_name ) !== false ) {
				update_option( $this->option_name, maybe_serialize( $data ) );
			} else {
				add_option( $this->option_name, maybe_serialize( $data ), null, 'no' );
			}

			$this->generate_block_rules( null );
			wp_send_json_success( json_encode( $this->get_bots() ) );

		}

		function view_handler() {
			?>
			<style>
				tr.active {
					background-color: rgba(54, 204, 255, 0.05);
				}

				.active th.bot-re {
					border-left: 4px solid #2ea2cc;
				}

				.blocked {
					column-rule: rgba(242, 0, 19, 0.75);;
				}
			</style>
			<h1>Spider Blocker</h1>
			<hr/>
			<div ng-app="spiderBlockApp">
				<div ng-controller="BotListCtrl">
					<h2>Add New Bot</h2>

					<form name="add_form" ng-submit="add()">
						<table class="form-table">
							<tbody>
							<tr>
								<th scope="row"><label>User Agent</label></th>
								<td><input bots="bots" ensure-unique="re" ng-model='bot.re' class="regular-text"
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
							</td>
						</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="button" class="button button-primary" ng-click="save()" value="Save">
						<input type="button" class="button button-primary" ng-click="reset()" value="Reset to Defaults">
						<input type="button" class="button button-secondary" value="Import Definitions">
						<input type="button" class="button button-secondary" value="Export Definitions">
					</p>

				</div>

				<script type="text/javascript"
				        src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.13/angular.min.js"></script>
				<script type="text/javascript"
				        src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.13/angular-aria.min.js"></script>
				<script type="text/javascript">
					-(function () {
						var spiderBlockApp = angular.module('spiderBlockApp', ['ngAria']);

						spiderBlockApp.directive('ensureUnique', [function () {
							return {
								require: 'ngModel',
								scope: {
									bots: '='
								},
								link: function (scope, ele, attrs, c) {
									scope.$watch(attrs.ngModel, function () {
										var safe = true;
										var bots = scope.bots;
										console.log(bots);
										if (scope.bots) {
											for (var i = bots.length - 1; i >= 0; i--) {
												safe &= bots[i]['re'] != attrs.ngModel;
											}
											if (safe) {
												c.$setValidity('unique', true);
											} else {
												c.$setValidity('unique', false);
											}
										} else {
											c.$setValidity('unique', true);
										}
									});
								}
							}
						}]);

						spiderBlockApp.controller('BotListCtrl', function ($scope, $http) {
							var wp_ajax = function (_req) {
								_req.nonce = '<?php echo wp_create_nonce( $this->nonce ); ?>';
								return $http({
									method: 'POST',
									url: ajaxurl,
									data: jQuery.param(_req),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								})
							};

							$scope.bot = {"state": true};

							wp_ajax({
								action: 'NSB-get_list'
							}).success(function (res) {
								$scope.bots = JSON.parse(res.data);
							});

							$scope.save = function () {
								wp_ajax({
									action: 'NSB-set_list',
									data: angular.toJson($scope.bots)
								}).success(function (res) {
									$scope.bots = JSON.parse(res.data);
								});
							};

							$scope.reset = function () {
								wp_ajax({
									action: 'NSB-reset_list'
								}).success(function (res) {
									$scope.bots = JSON.parse(res.data);
								});
							};

							$scope.add = function () {
								$scope.bots.push($scope.bot);
								$scope.bot = {"state": true};
							};
						});
					})(angular, document, jQuery);
				</script>
			</div>
		<?php
		}
	}

	if ( ! apache_get_version() || ! NiteowebSpiderBlocker::mod_rewrite_enabled() ) {
		?>
		<div id="error-page">
			<p>This plugin requires Apache2 server with mod_rewrite support. Please contact your hosting provider about
				upgrading your server software. Your Apache version is <b><?php echo apache_get_version(); ?></b></p>
		</div>
		<?php
		die();
	}
	new NiteowebSpiderBlocker;


