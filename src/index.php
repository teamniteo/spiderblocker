<?php
namespace Niteoweb\SpiderBlocker;
/**
 * Plugin Name: Spider Blocker
 * Description: Spider Blocker will block most common bots that consume bandwidth and slow down your server.
 * Version:     1.0.4
 * Runtime:     5.3
 * Author:      NiteoWeb Ltd.
 * Author URI:  www.niteoweb.com
 */

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

    public $default_bots = 'a:36:{i:0;O:8:"stdClass":4:{s:4:"name";s:10:"Ahrefs Bot";s:2:"re";s:9:"AhrefsBot";s:4:"desc";s:25:"https://ahrefs.com/robot/";s:5:"state";b:1;}i:1;O:8:"stdClass":4:{s:4:"name";s:8:"MJ12 bot";s:2:"re";s:7:"MJ12bot";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:2;O:8:"stdClass":4:{s:4:"name";s:9:"Roger Bot";s:2:"re";s:8:"Rogerbot";s:4:"desc";s:40:"http://moz.com/help/pro/rogerbot-crawler";s:5:"state";b:1;}i:3;O:8:"stdClass":4:{s:4:"name";s:11:"Semrush Bot";s:2:"re";s:10:"SemrushBot";s:4:"desc";s:31:"http://www.semrush.com/bot.html";s:5:"state";b:1;}i:4;O:8:"stdClass":4:{s:4:"name";s:11:"ia_archiver";s:2:"re";s:11:"ia_archiver";s:4:"desc";s:36:"http://archive.org/about/exclude.php";s:5:"state";b:1;}i:5;O:8:"stdClass":4:{s:4:"name";s:8:"ScoutJet";s:2:"re";s:8:"ScoutJet";s:4:"desc";s:19:"http://scoutjet.com";s:5:"state";b:1;}i:6;O:8:"stdClass":4:{s:4:"name";s:7:"sistrix";s:2:"re";s:7:"sistrix";s:4:"desc";s:26:"http://crawler.sistrix.net";s:5:"state";b:1;}i:7;O:8:"stdClass":4:{s:4:"name";s:16:"SearchmetricsBot";s:2:"re";s:16:"SearchmetricsBot";s:4:"desc";s:50:"http://www.searchmetrics.com/en/searchmetrics-bot/";s:5:"state";b:1;}i:8;O:8:"stdClass":4:{s:4:"name";s:14:"SEOkicks-Robot";s:2:"re";s:14:"SEOkicks-Robot";s:4:"desc";s:33:"http://www.seokicks.de/robot.html";s:5:"state";b:1;}i:9;O:8:"stdClass":4:{s:4:"name";s:16:"Lipperhey Spider";s:2:"re";s:16:"Lipperhey Spider";s:4:"desc";s:43:"http://www.lipperhey.com/en/website-spider/";s:5:"state";b:1;}i:10;O:8:"stdClass":4:{s:4:"name";s:6:"Exabot";s:2:"re";s:6:"Exabot";s:4:"desc";s:44:"http://www.exalead.com/search/webmasterguide";s:5:"state";b:1;}i:11;O:8:"stdClass":4:{s:4:"name";s:6:"NC Bot";s:2:"re";s:5:"NCBot";s:4:"desc";s:55:"https://twitter.com/NetComber/status/334476871691550721";s:5:"state";b:1;}i:12;O:8:"stdClass":4:{s:4:"name";s:15:"BacklinkCrawler";s:2:"re";s:15:"BacklinkCrawler";s:4:"desc";s:40:"http://www.backlinktest.com/crawler.html";s:5:"state";b:1;}i:13;O:8:"stdClass":4:{s:4:"name";s:15:"archive.org Bot";s:2:"re";s:15:"archive.org_bot";s:4:"desc";s:42:"http://archive.org/details/archive.org_bot";s:5:"state";b:1;}i:14;O:8:"stdClass":4:{s:4:"name";s:12:"MeanPath Bot";s:2:"re";s:11:"meanpathbot";s:4:"desc";s:37:"https://meanpath.com/meanpathbot.html";s:5:"state";b:1;}i:15;O:8:"stdClass":4:{s:4:"name";s:18:"PagesInventory Bot";s:2:"re";s:14:"PagesInventory";s:4:"desc";s:56:"http://www.botsvsbrowsers.com/details/1002332/index.html";s:5:"state";b:1;}i:16;O:8:"stdClass":4:{s:4:"name";s:12:"Aboundex Bot";s:2:"re";s:11:"Aboundexbot";s:4:"desc";s:32:"http://www.aboundex.com/crawler/";s:5:"state";b:1;}i:17;O:8:"stdClass":4:{s:4:"name";s:15:"SeoProfiler Bot";s:2:"re";s:5:"spbot";s:4:"desc";s:31:"http://www.seoprofiler.com/bot/";s:5:"state";b:1;}i:18;O:8:"stdClass":4:{s:4:"name";s:11:"Linkdex Bot";s:2:"re";s:10:"linkdexbot";s:4:"desc";s:34:"http://www.linkdex.com/about/bots/";s:5:"state";b:1;}i:19;O:8:"stdClass":4:{s:4:"name";s:7:"Gigabot";s:2:"re";s:7:"Gigabot";s:4:"desc";s:45:"http://www.useragentstring.com/pages/Gigabot/";s:5:"state";b:1;}i:20;O:8:"stdClass":4:{s:4:"name";s:6:"DotBot";s:2:"re";s:6:"dotbot";s:4:"desc";s:35:"http://en.wikipedia.org/wiki/DotBot";s:5:"state";b:1;}i:21;O:8:"stdClass":4:{s:4:"name";s:5:"Nutch";s:2:"re";s:5:"Nutch";s:4:"desc";s:32:"http://nutch.apache.org/bot.html";s:5:"state";b:1;}i:22;O:8:"stdClass":4:{s:4:"name";s:8:"BLEX Bot";s:2:"re";s:7:"BLEXBot";s:4:"desc";s:27:"http://webmeup-crawler.com/";s:5:"state";b:1;}i:23;O:8:"stdClass":4:{s:4:"name";s:6:"Ezooms";s:2:"re";s:6:"Ezooms";s:4:"desc";s:49:"http://graphicline.co.za/blogs/what-is-ezooms-bot";s:5:"state";b:1;}i:24;O:8:"stdClass":4:{s:4:"name";s:11:"Majestic 12";s:2:"re";s:11:"Majestic-12";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:25;O:8:"stdClass":4:{s:4:"name";s:12:"Majestic SEO";s:2:"re";s:12:"Majestic-SEO";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:26;O:8:"stdClass":4:{s:4:"name";s:7:"DSearch";s:2:"re";s:7:"DSearch";s:4:"desc";s:56:"http://www.majestic12.co.uk/projects/dsearch/mj12bot.php";s:5:"state";b:1;}i:27;O:8:"stdClass":4:{s:4:"name";s:10:"Blekko Bot";s:2:"re";s:9:"BlekkoBot";s:4:"desc";s:33:"http://blekko.com/about/blekkobot";s:5:"state";b:1;}i:28;O:8:"stdClass":4:{s:4:"name";s:6:"Yandex";s:2:"re";s:6:"Yandex";s:4:"desc";s:41:"http://help.yandex.com/search/?id=1112030";s:5:"state";b:0;}i:29;O:8:"stdClass":4:{s:4:"name";s:10:"Google Bot";s:2:"re";s:9:"googlebot";s:4:"desc";s:57:"https://support.google.com/webmasters/answer/182072?hl=en";s:5:"state";b:0;}i:30;O:8:"stdClass":4:{s:4:"name";s:18:"Feedfetcher Google";s:2:"re";s:18:"Feedfetcher-Google";s:4:"desc";s:51:"https://support.google.com/webmasters/answer/178852";s:5:"state";b:0;}i:31;O:8:"stdClass":4:{s:4:"name";s:8:"Bing Bot";s:2:"re";s:7:"BingBot";s:4:"desc";s:36:"http://en.wikipedia.org/wiki/Bingbot";s:5:"state";b:0;}i:32;O:8:"stdClass":4:{s:4:"name";s:9:"Nerdy Bot";s:2:"re";s:8:"NerdyBot";s:4:"desc";s:20:"http://nerdybot.com/";s:5:"state";b:1;}i:33;O:8:"stdClass":4:{s:4:"name";s:9:"James BOT";s:2:"re";s:8:"JamesBOT";s:4:"desc";s:32:"http://cognitiveseo.com/bot.html";s:5:"state";b:1;}i:34;O:8:"stdClass":4:{s:4:"name";s:7:"Tin Eye";s:2:"re";s:6:"TinEye";s:4:"desc";s:34:"http://www.tineye.com/crawler.html";s:5:"state";b:1;}i:35;O:8:"stdClass":4:{s:5:"state";b:1;s:2:"re";s:11:"Baiduspider";s:4:"name";s:5:"Baidu";s:4:"desc";s:47:"http://www.baidu.com/search/robots_english.html";}}';
    protected $option_name = 'Niteoweb.SpiderBlocker.Bots';
    protected $nonce = 'Niteoweb.SpiderBlocker.Nonce';

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

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    static function isHtaccessWritable()
    {
        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';

        return is_writable($htaccess_file);
    }

    /**
     * @codeCoverageIgnore
     * @param int $mod octet value for chmod
     * @return bool
     */
    static function chmodHtaccess($mod = 0644)
    {
        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';

        return chmod($htaccess_file, $mod);
    }

    function generateRewriteRules()
    {
        global $wp_rewrite;

        // Protect plugin from direct access
        $wp_rewrite->add_external_rule('wp-content/plugins/spider_blocker/index.php', 'index.php%{REQUEST_URI}');
        $wp_rewrite->add_external_rule('wp-content/plugins/spider_blocker/readme.txt', 'index.php%{REQUEST_URI}');
        $wp_rewrite->add_external_rule('wp-content/plugins/spider_blocker/', 'index.php%{REQUEST_URI}');
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
        if (get_option($this->option_name) === false) {
            update_option($this->option_name, $this->default_bots);
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
    function activatePlugin()
    {
        if (!apache_get_version() || !SpiderBlocker::modRewriteEnabled()) {
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

    function generateBlockRules()
    {
        global $wp_rewrite;

        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';

        if ((!file_exists($htaccess_file) && is_writable($home_path)) || is_writable($htaccess_file)) {
            if ($this->modRewriteEnabled()) {
                insert_with_markers($htaccess_file, 'NiteowebSpiderBlocker', $this->getRules());
            }
        }

        $wp_rewrite->flush_rules();
    }

    static function modRewriteEnabled()
    {
        return function_exists('apache_mod_loaded') ? apache_mod_loaded('mod_rewrite', false) : false;
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
        return maybe_unserialize(get_option($this->option_name, $this->default_bots));
    }

    function loadList()
    {
        check_ajax_referer($this->nonce, 'nonce');
        wp_send_json_success($this->getBots());
    }

    function resetList()
    {
        check_ajax_referer($this->nonce, 'nonce');
        delete_option($this->option_name);
        $this->generateBlockRules();
        wp_send_json_success($this->getBots());
    }

    function removeBlockRules()
    {
        global $wp_rewrite;
        delete_option($this->option_name);
        $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
        $htaccess_file = $home_path . '.htaccess';
        $empty = array();
        if ((!file_exists($htaccess_file) && is_writable($home_path)) || is_writable($htaccess_file)) {
            if ($this->modRewriteEnabled()) {
                insert_with_markers($htaccess_file, 'NiteowebSpiderBlocker', $empty);
            }
        }

        $wp_rewrite->flush_rules();
    }

    function saveList()
    {

        check_ajax_referer($this->nonce, 'nonce');
        $data = json_decode(stripcslashes($_POST['data']));

        if (json_last_error()) {
            if (function_exists('json_last_error_msg')) {
                wp_send_json_error(json_last_error_msg());
            } else {
                wp_send_json_error('Failed parsing JSON');
            }

        }
        if (get_option($this->option_name) !== false) {
            update_option($this->option_name, maybe_serialize($data));
        } else {
            add_option($this->option_name, maybe_serialize($data), null, 'no');
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
        wp_enqueue_script( 'spider_blocker_angularjs', plugin_dir_url( __FILE__ ) . 'static/angular.js' );
        wp_enqueue_script( 'spider_blocker_app', plugin_dir_url( __FILE__ ) . 'static/app.js' );
        wp_enqueue_style( 'spider_blocker_app', plugin_dir_url( __FILE__ ) . 'static/app.css' );
        ?>
        <script>
            window.sb_nonce="<?php echo wp_create_nonce($this->nonce); ?>";
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
    register_activation_hook(__FILE__, array(&$NiteowebSpiderBlocker_ins, 'activatePlugin'));
    register_deactivation_hook(__FILE__, array(&$NiteowebSpiderBlocker_ins, 'removeBlockRules'));
}
