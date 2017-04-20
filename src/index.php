<?php
namespace Niteoweb\SpiderBlocker;
/**
 * Plugin Name: Spider Blocker
 * Description: Spider Blocker will block most common bots that consume bandwidth and slow down your server.
 * Version:     1.0.15
 * Runtime:     5.3+
 * Author:      Easy Blog Networks
 * Author URI:  www.easyblognetworks.com
 */

if (!function_exists('apache_get_version')) {
    function apache_get_version()
    {
        if (stristr($_ENV["SERVER_SOFTWARE"], 'Apache')) {
            return $_ENV["SERVER_SOFTWARE"];
        }
        if (stristr($_SERVER["SERVER_SOFTWARE"], 'Apache')) {
            return $_SERVER["SERVER_SOFTWARE"];
        }
        return false;
    }
}

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    ?>
    <div id="error-page">
        <p>This plugin requires PHP 5.3.0 or higher. Please contact your hosting provider about upgrading your
            server software. Your PHP version is <b><?php echo PHP_VERSION; ?></b></p>
    </div>
    <?php
    die();
}


class SpiderBlocker
{

    const OptionName = 'Niteoweb.SpiderBlocker.Bots';
    const nonce = 'Niteoweb.SpiderBlocker.Nonce';
    const CheckHook = 'Niteoweb.SpiderBlocker.CheckHook';
    private $default_bots = array(
        array(
            'name' => 'Ahrefs Bot',
            're' => 'AhrefsBot',
            'desc' => 'https://ahrefs.com/robot/',
            'state' => true,
        ),
        array(
            'name' => 'MJ12 bot',
            're' => 'MJ12bot',
            'desc' => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
            'state' => true,
        ),
        array(
            'name' => 'Roger Bot',
            're' => 'Rogerbot',
            'desc' => 'http://moz.com/help/pro/rogerbot-crawler',
            'state' => true,
        ),
        array(
            'name' => 'Semrush Bot',
            're' => 'SemrushBot',
            'desc' => 'http://www.semrush.com/bot.html',
            'state' => true,
        ),
        array(
            'name' => 'ia_archiver',
            're' => 'ia_archiver',
            'desc' => 'http://archive.org/about/exclude.php',
            'state' => true,
        ),
        array(
            'name' => 'ScoutJet',
            're' => 'ScoutJet',
            'desc' => 'http://scoutjet.com',
            'state' => true,
        ),
        array(
            'name' => 'sistrix',
            're' => 'sistrix',
            'desc' => 'http://crawler.sistrix.net',
            'state' => true,
        ),
        array(
            'name' => 'SearchmetricsBot',
            're' => 'SearchmetricsBot',
            'desc' => 'http://www.searchmetrics.com/en/searchmetrics-bot/',
            'state' => true,
        ),
        array(
            'name' => 'SEOkicks-Robot',
            're' => 'SEOkicks-Robot',
            'desc' => 'http://www.seokicks.de/robot.html',
            'state' => true,
        ),
        array(
            'name' => 'Lipperhey Spider',
            're' => 'Lipperhey Spider',
            'desc' => 'http://www.lipperhey.com/en/website-spider/',
            'state' => true,
        ),
        array(
            'name' => 'Exabot',
            're' => 'Exabot',
            'desc' => 'http://www.exalead.com/search/webmasterguide',
            'state' => true,
        ),
        array(
            'name' => 'NC Bot',
            're' => 'NCBot',
            'desc' => 'https://twitter.com/NetComber/status/334476871691550721',
            'state' => true,
        ),
        array(
            'name' => 'BacklinkCrawler',
            're' => 'BacklinkCrawler',
            'desc' => 'http://www.backlinktest.com/crawler.html',
            'state' => true,
        ),
        array(
            'name' => 'archive.org Bot',
            're' => 'archive.org_bot',
            'desc' => 'http://archive.org/details/archive.org_bot',
            'state' => true,
        ),
        array(
            'name' => 'MeanPath Bot',
            're' => 'meanpathbot',
            'desc' => 'https://meanpath.com/meanpathbot.html',
            'state' => true,
        ),
        array(
            'name' => 'PagesInventory Bot',
            're' => 'PagesInventory',
            'desc' => 'http://www.botsvsbrowsers.com/details/1002332/index.html',
            'state' => true,
        ),
        array(
            'name' => 'Aboundex Bot',
            're' => 'Aboundexbot',
            'desc' => 'http://www.aboundex.com/crawler/',
            'state' => true,
        ),
        array(
            'name' => 'SeoProfiler Bot',
            're' => 'spbot',
            'desc' => 'http://www.seoprofiler.com/bot/',
            'state' => true,
        ),
        array(
            'name' => 'Linkdex Bot',
            're' => 'linkdexbot',
            'desc' => 'http://www.linkdex.com/about/bots/',
            'state' => true,
        ),
        array(
            'name' => 'Gigabot',
            're' => 'Gigabot',
            'desc' => 'http://www.useragentstring.com/pages/Gigabot/',
            'state' => true,
        ),
        array(
            'name' => 'DotBot',
            're' => 'dotbot',
            'desc' => 'http://en.wikipedia.org/wiki/DotBot',
            'state' => true,
        ),
        array(
            'name' => 'Nutch',
            're' => 'Nutch',
            'desc' => 'http://nutch.apache.org/bot.html',
            'state' => true,
        ),
        array(
            'name' => 'BLEX Bot',
            're' => 'BLEXBot',
            'desc' => 'http://webmeup-crawler.com/',
            'state' => true,
        ),
        array(
            'name' => 'Ezooms',
            're' => 'Ezooms',
            'desc' => 'http://graphicline.co.za/blogs/what-is-ezooms-bot',
            'state' => true,
        ),
        array(
            'name' => 'Majestic 12',
            're' => 'Majestic-12',
            'desc' => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
            'state' => true,
        ),
        array(
            'name' => 'Majestic SEO',
            're' => 'Majestic-SEO',
            'desc' => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
            'state' => true,
        ),
        array(
            'name' => 'DSearch',
            're' => 'DSearch',
            'desc' => 'http://www.majestic12.co.uk/projects/dsearch/mj12bot.php',
            'state' => true,
        ),
        array(
            'name' => 'Blekko Bot',
            're' => 'BlekkoBot',
            'desc' => 'http://blekko.com/about/blekkobot',
            'state' => true,
        ),
        array(
            'name' => 'Yandex',
            're' => 'Yandex',
            'desc' => 'http://help.yandex.com/search/?id=1112030',
            'state' => false,
        ),
        array(
            'name' => 'Google Bot',
            're' => 'googlebot',
            'desc' => 'https://support.google.com/webmasters/answer/182072?hl=en',
            'state' => false,
        ),
        array(
            'name' => 'Feedfetcher Google',
            're' => 'Feedfetcher-Google',
            'desc' => 'https://support.google.com/webmasters/answer/178852',
            'state' => false,
        ),
        array(
            'name' => 'Bing Bot',
            're' => 'BingBot',
            'desc' => 'http://en.wikipedia.org/wiki/Bingbot',
            'state' => false,
        ),
        array(
            'name' => 'Nerdy Bot',
            're' => 'NerdyBot',
            'desc' => 'http://nerdybot.com/',
            'state' => true,
        ),
        array(
            'name' => 'James BOT',
            're' => 'JamesBOT',
            'desc' => 'http://cognitiveseo.com/bot.html',
            'state' => true,
        ),
        array(
            'name' => 'Tin Eye',
            're' => 'TinEye',
            'desc' => 'http://www.tineye.com/crawler.html',
            'state' => true,
        ),
        array(
            'state' => true,
            're' => 'Baiduspider',
            'name' => 'Baidu',
            'desc' => 'http://www.baidu.com/search/robots_english.html',
        ),
        array(
            'state' => true,
            're' => 'serpstat',
            'name' => 'Serpstat',
            'desc' => 'https://serpstat.com/',
        ),
        array(
            'state' => true,
            'desc' => 'https://www.spyfu.com/',
            're' => 'spyfu',
            'name' => 'SpyFu',
        ),
    );

    function __construct()
    {
        if (is_admin()) {
            add_action('admin_notices', array(&$this, 'activatePluginNotice'));
            add_action('admin_menu', array(&$this, 'adminMenu'));
            add_action('wp_ajax_NSB-get_list', array(&$this, 'loadList'));
            add_action('wp_ajax_NSB-set_list', array(&$this, 'saveList'));
            add_action('wp_ajax_NSB-reset_list', array(&$this, 'resetList'));
        }
        add_action('generate_rewrite_rules', array(&$this, "generateRewriteRules"));

    }

    function generateRewriteRules($wp_rewrite)
    {
        // Protect plugin from direct access
        $wp_rewrite->add_external_rule($this->pluginURL() . 'index.php', 'index.php%{REQUEST_URI}');
        $wp_rewrite->add_external_rule($this->pluginURL() . 'readme.txt', 'index.php%{REQUEST_URI}');
        $wp_rewrite->add_external_rule($this->pluginURL(), 'index.php%{REQUEST_URI}');
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    private function pluginURL()
    {
        $url = wp_make_link_relative(plugin_dir_url(__FILE__));
        $url = ltrim($url, "/");

        return $url;
    }

    function adminMenu()
    {
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
    function activatePluginNotice()
    {
        if (get_option(self::OptionName) === false) {
            update_option(self::OptionName, $this->default_bots);
            ?>
            <div class="notice notice-success">
                <p>SpiderBlocker plugin has enabled blocking of some bots, please review settings by visiting <a
                            href="<?php echo admin_url('tools.php?page=ni_spider_block'); ?>">Setting page</a>!</p>
            </div>
            <?php
        }
    }

    /**
     * @codeCoverageIgnore
     */
    function onPluginUpgrade()
    {
        $this->generateBlockRules();
    }

    /**
     * @codeCoverageIgnore
     */
    function activatePlugin()
    {
        if (!apache_get_version()) {
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

        if (!SpiderBlocker::isHtaccessWritable()) {
            $state = SpiderBlocker::chmodHtaccess();
            if (!SpiderBlocker::isHtaccessWritable() || !$state) {
                ?>
                <div id="error-page">
                    <p>This plugin requires <b>.htaccess</b> file that is writable by the server. Please enable write
                        access
                        for file <b><?php echo ABSPATH . '.htaccess'; ?></b>.</p>
                </div>
                <?php
                die();
            }
        }
        $this->generateBlockRules();

    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    static function isHtaccessWritable()
    {
        $htaccess_file = SpiderBlocker::joinPaths(ABSPATH, '.htaccess');
        return is_writable($htaccess_file);
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    static function joinPaths()
    {
        $paths = array();

        foreach (func_get_args() as $arg) {
            if ($arg !== '') {
                $paths[] = $arg;
            }
        }

        return preg_replace('#/+#', '/', join('/', $paths));
    }

    /**
     * @codeCoverageIgnore
     *
     * @param int $mod octet value for chmod
     *
     * @return bool
     */
    static function chmodHtaccess($mod = 0644)
    {
        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';

        return chmod($htaccess_file, $mod);
    }

    function generateBlockRules()
    {
        global $wp_rewrite;

        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';

        if ((!file_exists($htaccess_file) && is_writable($home_path)) || is_writable($htaccess_file)) {
            insert_with_markers($htaccess_file, 'NiteowebSpiderBlocker', $this->getRules());
        }

        $wp_rewrite->flush_rules();
    }

    /**
     * Generate block rules based on enabled bots
     *
     * @return array
     */
    function getRules()
    {
        $list = array();
        foreach ($this->getBots() as $bot) {
            if(is_array($bot)){
                $bot = (object) $bot;
            }
            if ($bot->state) {
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

    private function getBots()
    {
        $data = get_option(self::OptionName);
        if($data && is_array($data) && count($data) > 0){
            return maybe_unserialize($data);
        }
        return $this->default_bots;
    }


    function loadList()
    {
        check_ajax_referer(self::nonce, 'nonce');
        wp_send_json_success($this->getBots());
    }

    function resetList()
    {
        check_ajax_referer(self::nonce, 'nonce');
        delete_option(self::OptionName);
        $this->generateBlockRules();
        wp_send_json_success($this->getBots());
    }

    function removeBlockRules()
    {
        global $wp_rewrite;
        delete_option(self::OptionName);
        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';
        $empty = array();
        if ((!file_exists($htaccess_file) && is_writable($home_path)) || is_writable($htaccess_file)) {
            insert_with_markers($htaccess_file, 'NiteowebSpiderBlocker', $empty);
        }

        $wp_rewrite->flush_rules();
    }

    function saveList()
    {

        check_ajax_referer(self::nonce, 'nonce');
        $data = json_decode(stripcslashes($_POST['data']));

        if (json_last_error()) {
            if (function_exists('json_last_error_msg')) {
                wp_send_json_error(json_last_error_msg());
            } else {
                wp_send_json_error('Failed parsing JSON');
            }

        }
        if (get_option(self::OptionName) !== false) {
            update_option(self::OptionName, maybe_serialize($data));
        } else {
            add_option(self::OptionName, maybe_serialize($data), null, 'no');
        }

        $this->generateBlockRules();
        wp_send_json_success($this->getBots());

    }

    /**
     * @codeCoverageIgnore
     */
    function viewHandler()
    {

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
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"
                type="text/javascript"></script>
        <script>
            window.sb_nonce = "<?php echo wp_create_nonce(self::nonce); ?>";
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
        </div>
        <?php
    }


}

// Inside WordPress
if (defined('ABSPATH')) {
    $NiteowebSpiderBlocker_ins = new SpiderBlocker;
    add_action( "upgrader_process_complete", array(&$NiteowebSpiderBlocker_ins, 'onPluginUpgrade'), 10, 2);
    register_activation_hook(__FILE__, array(&$NiteowebSpiderBlocker_ins, 'activatePlugin'));
    register_deactivation_hook(__FILE__, array(&$NiteowebSpiderBlocker_ins, 'removeBlockRules'));
}
