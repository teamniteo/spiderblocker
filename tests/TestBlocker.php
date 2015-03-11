<?php

	class TestExport extends PHPUnit_Framework_TestCase {

		function setUp() {
			\WP_Mock::setUsePatchwork( true );
			\WP_Mock::setUp();
		}

		function tearDown() {
			\WP_Mock::tearDown();
		}

		public function test_init_admin() {
			\WP_Mock::wpFunction( 'is_admin', array(
					'return' => true,
				)
			);

			$plugin = new NiteowebSpiderBlocker;

			\WP_Mock::expectActionAdded( 'admin_menu', array( $plugin, 'adminMenu' ) );
			\WP_Mock::expectActionAdded( 'wp_ajax_NSB-get_list', array( $plugin, 'loadList' ) );
			\WP_Mock::expectActionAdded( 'wp_ajax_NSB-set_list', array( $plugin, 'saveList' ) );
			\WP_Mock::expectActionAdded( 'wp_ajax_NSB-reset_list', array( $plugin, 'resetList' ) );
			\WP_Mock::expectActionAdded( 'generate_rewrite_rules', array( $plugin, 'generateRewriteRules' ) );

			$plugin->__construct();
			\WP_Mock::assertHooksAdded();
		}

		public function test_init_non_admin() {
			\WP_Mock::wpFunction( 'is_admin', array(
					'return' => true,
				)
			);

			$plugin = new NiteowebSpiderBlocker;

			\WP_Mock::expectActionAdded( 'generate_rewrite_rules', array( $plugin, 'generateRewriteRules' ) );

			$plugin->__construct();
			\WP_Mock::assertHooksAdded();
		}

		public function test_admin_menu() {
			\WP_Mock::wpFunction( 'add_management_page', array(
					'return' => true,
					'args'   => array( 'SpiderBlocker', 'SpiderBlocker', 'manage_options', 'ni_spider_block', '*' ),
				)
			);
			$plugin = new NiteowebSpiderBlocker;
			$plugin->adminMenu();

		}

		public function test_generate_rewrite_rules() {
			global $wp_rewrite;
			$wp_rewrite = \Mockery::mock();
			$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
				array(
					"wp-content/plugins/spider_blocker/",
					"index.php%{REQUEST_URI}"
				)
			);
			$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
				array(
					"wp-content/plugins/spider_blocker/index.php",
					"index.php%{REQUEST_URI}"
				)
			);
			$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
				array(
					"wp-content/plugins/spider_blocker/readme.txt",
					"index.php%{REQUEST_URI}"
				)
			);

			$plugin = new NiteowebSpiderBlocker;
			$plugin->generateRewriteRules();

		}

		public function test_rules_generation() {
			global $wp_rewrite;
			$wp_rewrite = \Mockery::mock();
			$wp_rewrite->shouldReceive( 'flush_rules' )->once();

			$plugin = new NiteowebSpiderBlocker;

			\WP_Mock::wpFunction( 'is_admin', array(
					'return' => true,
				)
			);

			\WP_Mock::wpFunction( 'get_home_path', array(
					'return' => '/tmp/',
				)
			);
			\WP_Mock::wpFunction( 'insert_with_markers', array(
					'called' => 1,
					'args'   => array(
						'/tmp/.htaccess',
						'NiteowebSpiderBlocker',
						'*'
					)
				)
			);

			\WP_Mock::wpFunction( 'maybe_unserialize', array(
					'called' => 1,
					'return' => json_decode( json_encode( array(
						array(
							'name'  => 'True Bot',
							're'    => 'TrueBot',
							'desc'  => 'True',
							'state' => true,
						),
						array(
							'name'  => 'False Bot',
							're'    => 'FalseBot',
							'desc'  => 'False',
							'state' => false,
						)
					), false ) ),
				)
			);

			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
				)
			);

			$plugin->generateBlockRules();
			$this->assertEquals( $plugin->getRules(), array(
				'SetEnvIfNoCase User-Agent "TrueBot" block_bot',
				'<Limit GET POST HEAD>',
				'Order Allow,Deny',
				'Allow from all',
				'Deny from env=block_bot',
				'</Limit>',

			) );
		}

		public function test_ajax_get_list() {
			$plugin = new NiteowebSpiderBlocker;


			\WP_Mock::wpFunction( 'check_ajax_referer', array(
					'called' => 1,
					'args'   => array(
						'Niteoweb.SpiderBlocker.Nonce',
						'nonce',
					)
				)
			);

			\WP_Mock::wpFunction( 'wp_send_json_success', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'maybe_unserialize', array(
					'called' => 1,
					'return' => array(
						array(
							'name'  => 'True Bot',
							're'    => 'TrueBot',
							'desc'  => 'True',
							'state' => true,
						),
						array(
							'name'  => 'False Bot',
							're'    => 'FalseBot',
							'desc'  => 'False',
							'state' => false,
						)
					),
				)
			);

			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
				)
			);

			$plugin->loadList();

		}


		public function test_ajax_reset_list() {
			$plugin = new NiteowebSpiderBlocker;


			\WP_Mock::wpFunction( 'check_ajax_referer', array(
					'called' => 1,
					'args'   => array(
						'Niteoweb.SpiderBlocker.Nonce',
						'nonce',
					)
				)
			);

			\WP_Mock::wpFunction( 'wp_send_json_success', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'maybe_unserialize', array(
					'called' => 1,
					'return' => array(
						array(
							'name'  => 'True Bot',
							're'    => 'TrueBot',
							'desc'  => 'True',
							'state' => true,
						),
						array(
							'name'  => 'False Bot',
							're'    => 'FalseBot',
							'desc'  => 'False',
							'state' => false,
						)
					),
				)
			);

			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				)
			);

			\WP_Mock::wpFunction( 'delete_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
				)
			);

			$plugin->resetList();

		}

		public function test_ajax_save_list() {
			$plugin = new NiteowebSpiderBlocker;


			\WP_Mock::wpFunction( 'check_ajax_referer', array(
					'called' => 1,
					'args'   => array(
						'Niteoweb.SpiderBlocker.Nonce',
						'nonce',
					)
				)
			);

			\WP_Mock::wpFunction( 'wp_send_json_success', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'maybe_unserialize', array(
					'called' => 1,
					'return' => array(
						array(
							'name'  => 'True Bot',
							're'    => 'TrueBot',
							'desc'  => 'True',
							'state' => true,
						),
						array(
							'name'  => 'False Bot',
							're'    => 'FalseBot',
							'desc'  => 'False',
							'state' => false,
						)
					),
				)
			);

			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
					'return' => false
				)
			);
			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
					'return' => false
				)
			);
			\WP_Mock::wpFunction( 'sanitize_text_field', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'maybe_serialize', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'add_option', array(
					'called' => 1,
					'args'   => array( "Niteoweb.SpiderBlocker.Bots", "", "", "no" ),
				)
			);

			$_POST['data'] = '[{"name":"True Bot","re":"TrueBot","desc":"True","state":true}]';

			$plugin->saveList();

		}

		public function test_ajax_update_list() {
			$plugin = new NiteowebSpiderBlocker;


			\WP_Mock::wpFunction( 'check_ajax_referer', array(
					'called' => 1,
					'args'   => array(
						'Niteoweb.SpiderBlocker.Nonce',
						'nonce',
					)
				)
			);

			\WP_Mock::wpFunction( 'wp_send_json_success', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'maybe_unserialize', array(
					'called' => 1,
					'return' => array(
						array(
							'name'  => 'True Bot',
							're'    => 'TrueBot',
							'desc'  => 'True',
							'state' => true,
						),
						array(
							'name'  => 'False Bot',
							're'    => 'FalseBot',
							'desc'  => 'False',
							'state' => false,
						)
					),
				)
			);

			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
					'return' => true
				)
			);
			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
					'return' => true
				)
			);
			\WP_Mock::wpFunction( 'update_option', array(
					'called' => 1,
					'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
					'return' => true
				)
			);
			\WP_Mock::wpFunction( 'sanitize_text_field', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'maybe_serialize', array(
					'called' => 1,
				)
			);

			\WP_Mock::wpFunction( 'add_option', array(
					'called' => 1,
					'args'   => array( "Niteoweb.SpiderBlocker.Bots", "", "", "no" ),
				)
			);

			$_POST['data'] = '[{"name":"True Bot","re":"TrueBot","desc":"True","state":true}]';

			$plugin->saveList();

		}

		public function test_ajax_update_list_invalid() {
			$plugin = new NiteowebSpiderBlocker;


			\WP_Mock::wpFunction( 'check_ajax_referer', array(
					'called' => 1,
					'args'   => array(
						'Niteoweb.SpiderBlocker.Nonce',
						'nonce',
					)
				)
			);

			\WP_Mock::wpFunction( 'wp_send_json_error', array(
					'called' => 1,
				)
			);

			$_POST['data'] = '[{INVALID JSON}]';

			$plugin->saveList();

		}

		public function test_skip_rules_generation() {
			$plugin = new NiteowebSpiderBlocker;

			\WP_Mock::wpFunction( 'get_home_path', array(
					'return' => '/not_here/',
				)
			);

			$plugin->generateBlockRules();
		}

		public function test_remove_rules_generation() {
			global $wp_rewrite;
			$wp_rewrite = \Mockery::mock();
			$wp_rewrite->shouldReceive( 'flush_rules' )->once();

			$plugin = new NiteowebSpiderBlocker;

			\WP_Mock::wpFunction( 'is_admin', array(
					'return' => true,
				)
			);

			\WP_Mock::wpFunction( 'get_home_path', array(
					'return' => '/tmp/',
				)
			);
			\WP_Mock::wpFunction( 'insert_with_markers', array(
					'called' => 1,
					'args'   => array(
						'/tmp/.htaccess',
						'NiteowebSpiderBlocker',
						'*'
					)
				)
			);

			\WP_Mock::wpFunction( 'maybe_unserialize', array(
					'called' => 1,
					'return' => json_decode( json_encode( array(
						array(
							'name'  => 'True Bot',
							're'    => 'TrueBot',
							'desc'  => 'True',
							'state' => true,
						),
						array(
							'name'  => 'False Bot',
							're'    => 'FalseBot',
							'desc'  => 'False',
							'state' => false,
						)
					), false ) ),
				)
			);

			\WP_Mock::wpFunction( 'get_option', array(
					'called' => 1,
				)
			);

			$plugin->removeBlockRules();

		}

	}