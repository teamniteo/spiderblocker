<?php

use Niteoweb\SpiderBlocker\SpiderBlocker;

class TestBlocker extends \PHPUnit\Framework\TestCase {

	function setUp() {
		\WP_Mock::setUsePatchwork( true );
		\WP_Mock::setUp();
	}

	function tearDown() {
		$this->addToAssertionCount(
			\Mockery::getContainer()->mockery_getExpectationCount()
		);
		\WP_Mock::tearDown();
	}

	public function test_init_admin() {
		\WP_Mock::wpFunction(
			'is_admin', array(
				'return' => true,
			)
		);

		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);

		$plugin = new SpiderBlocker();

		\WP_Mock::expectActionAdded( 'admin_menu', array( $plugin, 'admin_menu' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_NSB-get_list', array( $plugin, 'load_list' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_NSB-set_list', array( $plugin, 'save_list' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_NSB-reset_list', array( $plugin, 'reset_list' ) );
		\WP_Mock::expectFilterAdded( 'robots_txt', array( $plugin, 'robots_file' ), ~PHP_INT_MAX, 2 );
		\WP_Mock::expectActionAdded( 'generate_rewrite_rules', array( $plugin, 'generate_rewrite_rules' ) );

		$plugin->__construct();
		\WP_Mock::assertHooksAdded();
	}

	public function test_init_non_admin() {
		\WP_Mock::wpFunction(
			'is_admin', array(
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);

		$plugin = new SpiderBlocker();

		\WP_Mock::expectActionAdded( 'generate_rewrite_rules', array( $plugin, 'generate_rewrite_rules' ) );

		$plugin->__construct();
		\WP_Mock::assertHooksAdded();
	}

	public function test_admin_menu() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'add_management_page', array(
				'return' => true,
				'args'   => array( 'SpiderBlocker', 'SpiderBlocker', 'manage_options', 'ni_spider_block', '*' ),
			)
		);

        $plugin = new SpiderBlocker();
        $plugin->admin_menu();
	}

	public function test_generate_rewrite_rules() {
		global $wp_rewrite;

		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'wp_make_link_relative', array(
				'return' => '/wp-content/plugins/spider_blocker/',
			)
		);

		\WP_Mock::wpFunction(
			'plugin_dir_url', array(
				'return' => 'http://localhost/wp-content/plugins/spider_blocker/',
			)
		);

		$wp_rewrite = \Mockery::mock();
		$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
			array(
				'wp-content/plugins/spider_blocker/',
				'spiderblocker.php%{REQUEST_URI}',
			)
		);
		$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
			array(
				'wp-content/plugins/spider_blocker/spiderblocker.php',
				'spiderblocker.php%{REQUEST_URI}',
			)
		);
		$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
			array(
				'wp-content/plugins/spider_blocker/readme.txt',
				'spiderblocker.php%{REQUEST_URI}',
			)
		);

		$plugin = new SpiderBlocker();
		$plugin->generate_rewrite_rules( $wp_rewrite );
	}

	public function test_rules_generation() {
		global $wp_rewrite;

		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$wp_rewrite = \Mockery::mock();
		$wp_rewrite->shouldReceive( 'flush_rules' )->once();

		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'is_admin', array(
				'return' => true,
			)
		);

		\WP_Mock::wpFunction(
			'get_home_path', array(
				'return' => '/tmp/',
			)
		);
		\WP_Mock::wpFunction(
			'insert_with_markers', array(
				'called' => 1,
				'args'   => array(
					'/tmp/.htaccess',
					'NiteowebSpiderBlocker',
					'*',
				),
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
				'called' => 1,
				'return' => json_decode(
					json_encode(
						array(
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
							),
						), false
					)
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
				'return' => array( 'a', 'b', 'c' ),
			)
		);

		$plugin->generate_block_rules();
		$this->assertEquals(
			$plugin->get_rules(), array(
				'SetEnvIfNoCase User-Agent "TrueBot" block_bot',
				'<Limit GET POST HEAD>',
				'Order Allow,Deny',
				'Allow from all',
				'Deny from env=block_bot',
				'</Limit>',

			)
		);
	}

	public function test_ajax_get_list() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer', array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
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
					),
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
			)
		);

		$plugin->load_list();
	}


	public function test_ajax_reset_list() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer', array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
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
					),
				),
			)
		);

		\WP_Mock::wpFunction(
			'delete_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
			)
		);

		\WP_Mock::expectFilterAdded( 'robots_txt', array( $plugin, 'robots_file' ), ~PHP_INT_MAX, 2 );

		$plugin->reset_list();
	}

	public function test_ajax_save_list() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer', array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
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
					),
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
				'return' => false,
			)
		);
		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				'return' => false,
			)
		);
		\WP_Mock::wpFunction(
			'sanitize_text_field', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_serialize', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'add_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '', '', 'no' ),
			)
		);

		\WP_Mock::expectFilterAdded( 'robots_txt', array( $plugin, 'robots_file' ), ~PHP_INT_MAX, 2 );

		$_POST['data'] = '[{"name":"True Bot","re":"TrueBot","desc":"True","state":true}]';

		$plugin->save_list();
	}

	public function test_ajax_update_list() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer', array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
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
					),
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'update_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'sanitize_text_field', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_serialize', array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'add_option', array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '', '', 'no' ),
			)
		);

		$_POST['data'] = '[{"name":"True Bot","re":"TrueBot","desc":"True","state":true}]';

		$plugin->save_list();
	}

	public function test_ajax_update_list_invalid() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer', array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_error', array(
				'called' => 1,
			)
		);

		$_POST['data'] = '[{INVALID JSON}]';

		$plugin->save_list();
	}

	public function test_skip_rules_generation() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'get_home_path', array(
				'return' => '/not_here/',
			)
		);

		$plugin->generate_block_rules();
	}

	public function test_remove_rules_generation() {
		global $wp_rewrite;

		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);
		$wp_rewrite = \Mockery::mock();
		$wp_rewrite->shouldReceive( 'flush_rules' )->once();

		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'is_admin', array(
				'return' => true,
			)
		);

		\WP_Mock::wpFunction(
			'get_home_path', array(
				'return' => '/tmp/',
			)
		);
		\WP_Mock::wpFunction(
			'insert_with_markers', array(
				'called' => 1,
				'args'   => array(
					'/tmp/.htaccess',
					'NiteowebSpiderBlocker',
					'*',
				),
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
				'called' => 1,
				'return' => json_decode(
					json_encode(
						array(
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
							),
						), false
					)
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
			)
		);

		$plugin->remove_block_rules();
	}

	public function test_robots_filter() {
		\WP_Mock::wpFunction(
			'wp_next_scheduled', array(
				'return' => true,
			)
		);

		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'maybe_unserialize', array(
				'called' => 1,
				'return' => json_decode(
					json_encode(
						array(
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
							),
						), false
					), true
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option', array(
				'called' => 1,
				'return' => array( 'one', 'two', 'three' ),
			)
		);

		$this->assertEquals(
			$plugin->robots_file( '', true ), "User-agent: TrueBot\nDisallow: /\n\n"
		);
	}

    public function test_view_handler_load() {
        \WP_Mock::wpFunction(
            'wp_next_scheduled', array(
                'return' => true,
            )
        );

        $plugin = new SpiderBlocker();

        \WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( $plugin, 'view_handler_scripts' ) );
        $plugin->view_handler_load();
    }

}
