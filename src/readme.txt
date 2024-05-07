=== Plugin Name ===
Contributors: niteoweb
Tags: seo, block, bots, htaccess, apache
Requires at least: 4.0
Tested up to: 6.5.2
Stable tag: 1.3.7

SpiderBlocker will block most common bots that consume bandwidth and slow down your blog.

== Description ==

Spider Blocker blocks most common bots that consume bandwidth and slow down your blog.
It accomplishes this by using .htaccess file to minimize impact on your website. It's hidden from external scanners. 

Spider Blocker is specifically designed for Apache servers with mod_rewrite enabled, allowing you to effortlessly safeguard your website from the most prevalent bots that hamper performance and drain resources.

= Plugin Features =
* Block Unlimited bots from viewing your site
* Easy Export/Import rules (comes with most common list of bots)
* Zero Footprint

== Installation ==

1. Upload 'spider-blocker' directory to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Tools** menu and then **SpiderBlocker* to configure
4. If you use any other plugin to edit .htaccess file, make sure that content of file is valid

== Frequently Asked Questions ==

Will rules stay active after I deactivate the plugin?
No, it’s a good practice for plugins to remove all changes they made to the blog, thus rules will also be deleted from .htaccess.

== Screenshots ==


== Changelog ==

= v1.3.1 =
* Added prlog.ru bot

= v1.3.0 =
* Added support for WP v5.6+

= v1.2.6 =
* Fix issue with class constants

= v1.2.5 =
* Visual fixes and code clean-up
* Added support for LiteSpeed server

= v1.2.0 =
* Code clean-up
* Support for the latest version of WordPress

== Upgrade Notice ==
