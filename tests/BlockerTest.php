<?php

use Niteoweb\SpiderBlocker\SpiderBlocker;
use PHPUnit\Framework\TestCase;

class BlockerTest extends TestCase {

	function setUp() {
		\WP_Mock::setUp();
	}

	function tearDown() {
		$this->addToAssertionCount(
			\Mockery::getContainer()->mockery_getExpectationCount()
		);

		\WP_Mock::tearDown();
		\Mockery::close();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 */
	public function testConstructor() {
		$plugin = new SpiderBlocker();

		\WP_Mock::expectFilterAdded( 'robots_txt', array( $plugin, 'robots_file' ), ~PHP_INT_MAX, 2 );

		\WP_Mock::expectActionAdded( 'admin_init', array( $plugin, 'check_environment' ) );
		\WP_Mock::expectActionAdded( 'admin_init', array( $plugin, 'check_server' ) );
		\WP_Mock::expectActionAdded( 'admin_init', array( $plugin, 'add_plugin_notices' ) );
		\WP_Mock::expectActionAdded( 'admin_notices', array( $plugin, 'admin_notices' ), 15 );
		\WP_Mock::expectActionAdded( 'admin_menu', array( $plugin, 'admin_menu' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_NSB-get_list', array( $plugin, 'load_list' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_NSB-set_list', array( $plugin, 'save_list' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_NSB-reset_list', array( $plugin, 'reset_list' ) );
		\WP_Mock::expectActionAdded( 'generate_rewrite_rules', array( $plugin, 'generate_rewrite_rules' ) );

		$plugin->__construct();
		\WP_Mock::assertHooksAdded();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::check_environment
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::is_environment_compatible
	 */
	public function testCheckEnvironmentEmpty() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldReceive( 'is_environment_compatible' )->andReturn( true );

		$this->assertEmpty( $mock->check_environment() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::check_environment
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::is_environment_compatible
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::deactivate_plugin
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::add_admin_notice
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_environment_message
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_php_version
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_plugin_name
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_plugin_base
	 */
	public function testCheckEnvironment() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldReceive( 'is_environment_compatible' )->andReturn( false );

		$_GET['activate'] = 'yes';

		\WP_Mock::userFunction(
			'is_plugin_active',
			array(
				'return' => true,
			)
		);
		\WP_Mock::userFunction(
			'deactivate_plugins',
			array(
				'return' => true,
			)
		);

		$mock->check_environment();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::check_server
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::deactivate_plugin
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::add_admin_notice
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_plugin_base
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_plugin_name
	 */
	public function testCheckServer() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldAllowMockingProtectedMethods();
		$mock->shouldReceive( 'get_server_software', 'is_htaccess_writable' )->andReturn( false );

		$mock->check_server();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::add_plugin_notices
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::is_wp_compatible
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::add_admin_notice
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_plugin_name
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_wp_version
	 */
	public function testAddPluginNotices() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldReceive( 'activate_plugin_notice' )->andReturn( true );

		\WP_Mock::userFunction(
			'get_bloginfo',
			array(
				'return' => '4.0',
			)
		);
		\WP_Mock::userFunction(
			'admin_url',
			array(
				'return' => true,
			)
		);
		\WP_Mock::userFunction(
			'esc_url',
			array(
				'return' => '#',
			)
		);

		$mock->add_plugin_notices();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::activate_plugin_notice
	 */
	public function testActivatePluginNoticeFalse() {
		$plugin = new SpiderBlocker();

		\WP_Mock::userFunction(
			'get_option',
			array(
				'return' => true,
			)
		);

		$this->assertFalse( $plugin->activate_plugin_notice() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::activate_plugin_notice
	 */
	public function testActivatePluginNoticeTrue() {
		$plugin = new SpiderBlocker();

		\WP_Mock::userFunction(
			'get_option',
			array(
				'return' => false,
			)
		);
		\WP_Mock::userFunction(
			'update_option',
			array(
				'return' => true,
			)
		);

		$this->assertTrue( $plugin->activate_plugin_notice() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::admin_notices
	 */
	public function testAdminNotices() {
		$plugin          = new SpiderBlocker();
		$plugin->notices = array(
			'notice1' => array(
				'class'   => 'class1',
				'message' => 'message1',
			),
		);

		\WP_Mock::userFunction(
			'wp_kses',
			array(
				'return' => 'message1',
			)
		);

		$this->expectOutputString( '<div class="notice notice-class1"><p>message1</p></div>' );
		$plugin->admin_notices();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::is_wp_compatible
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_wp_version
	 */
	public function testIsWpCompatible() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldReceive( 'get_wp_version' )->andReturn( false );
		$this->assertTrue( $mock->is_wp_compatible() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::is_environment_compatible
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_php_version
	 */
	public function testEnvironmentCompatibleTrue() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldReceive( 'get_php_version' )->andReturn( '1.0' );
		$this->assertTrue( $mock->is_environment_compatible() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::is_environment_compatible
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_php_version
	 */
	public function testEnvironmentCompatibleFalse() {
		$mock = \Mockery::mock( '\Niteoweb\SpiderBlocker\SpiderBlocker' )->makePartial();
		$mock->shouldReceive( 'get_php_version' )->andReturn( '100.0' );
		$this->assertFalse( $mock->is_environment_compatible() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::admin_menu
	 */
	public function testAdminMenu() {
		\WP_Mock::wpFunction(
			'add_management_page',
			array(
				'return' => true,
				'args'   => array( 'SpiderBlocker', 'SpiderBlocker', 'manage_options', 'ni_spider_block', '*' ),
			)
		);

		$plugin = new SpiderBlocker();
		$plugin->admin_menu();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_rewrite_rules
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::plugin_url
	 */
	public function testGenerateRewriteRules() {
		global $wp_rewrite;

		\WP_Mock::wpFunction(
			'wp_make_link_relative',
			array(
				'return' => '/wp-content/plugins/spider_blocker/',
			)
		);

		\WP_Mock::wpFunction(
			'plugin_dir_url',
			array(
				'return' => 'http://localhost/wp-content/plugins/spider_blocker/',
			)
		);

		$wp_rewrite = \Mockery::mock();
		$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
			array(
				'wp-content/plugins/spider_blocker/',
				'index.php%{REQUEST_URI}',
			)
		);
		$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
			array(
				'wp-content/plugins/spider_blocker/index.php',
				'index.php%{REQUEST_URI}',
			)
		);
		$wp_rewrite->shouldReceive( 'add_external_rule' )->withArgs(
			array(
				'wp-content/plugins/spider_blocker/readme.txt',
				'index.php%{REQUEST_URI}',
			)
		);

		$plugin = new SpiderBlocker();
		$plugin->generate_rewrite_rules( $wp_rewrite );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::plugin_url
	 */
	public function testPluginUrl() {
		$plugin = new SpiderBlocker();

		\WP_Mock::userFunction(
			'plugin_dir_url',
			array(
				'return' => 'https://plugin-url.com/',
			)
		);
		\WP_Mock::userFunction(
			'wp_make_link_relative',
			array(
				'return' => 'https://plugin-url.com/',
			)
		);

		$this->assertEquals(
			'https://plugin-url.com/',
			$plugin->plugin_url()
		);
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_block_rules
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_rules
	 */
	public function testRulesGeneration() {
		global $wp_rewrite;

		$wp_rewrite = \Mockery::mock();
		$wp_rewrite->shouldReceive( 'flush_rules' )->once();

		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'is_admin',
			array(
				'return' => true,
			)
		);

		\WP_Mock::wpFunction(
			'get_home_path',
			array(
				'return' => '/tmp/',
			)
		);
		\WP_Mock::wpFunction(
			'insert_with_markers',
			array(
				'called' => 1,
				'args'   => array(
					'/tmp/.htaccess',
					'NiteowebSpiderBlocker',
					'*',
				),
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
						),
						false
					),
					true
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option',
			array(
				'called' => 1,
				'return' => array( 'a', 'b', 'c' ),
			)
		);

		$plugin->generate_block_rules();
		$this->assertEquals(
			$plugin->get_rules(),
			array(
				'SetEnvIfNoCase User-Agent "TrueBot" block_bot',
				'<Limit GET POST HEAD>',
				'Order Allow,Deny',
				'Allow from all',
				'Deny from env=block_bot',
				'</Limit>',

			)
		);
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::load_list
	 */
	public function testAjaxGetList() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer',
			array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
			'get_option',
			array(
				'called' => 1,
			)
		);

		$plugin->load_list();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_block_rules
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::reset_list
	 */
	public function testAjaxResetList() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer',
			array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
			'delete_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
			)
		);

		\WP_Mock::expectFilterAdded( 'robots_txt', array( $plugin, 'robots_file' ), ~PHP_INT_MAX, 2 );

		$plugin->reset_list();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::save_list
	 */
	public function testAjaxSaveListNoData() {
		$plugin = new SpiderBlocker();

		\WP_Mock::userFunction(
			'wp_send_json_error',
			array(
				'return' => true,
			)
		);

		$this->assertEmpty( $plugin->save_list() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::save_list
	 */
	public function testAjaxSaveListEmptyData() {
		$plugin = new SpiderBlocker();

		$_POST['data'] = '';

		\WP_Mock::userFunction(
			'wp_send_json_error',
			array(
				'return' => true,
			)
		);

		$this->assertEmpty( $plugin->save_list() );
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_block_rules
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::save_list
	 */
	public function testAjaxSaveList() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer',
			array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
			'get_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
				'return' => false,
			)
		);
		\WP_Mock::wpFunction(
			'get_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				'return' => false,
			)
		);
		\WP_Mock::wpFunction(
			'sanitize_text_field',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_serialize',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'add_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '', '', 'no' ),
			)
		);

		\WP_Mock::expectFilterAdded( 'robots_txt', array( $plugin, 'robots_file' ), ~PHP_INT_MAX, 2 );

		$_POST['data'] = '[{"name":"True Bot","re":"TrueBot","desc":"True","state":true}]';

		$plugin->save_list();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_block_rules
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::save_list
	 */
	public function testAjaxUpdateList() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer',
			array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_success',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
			'get_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots' ),
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'get_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'update_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '*' ),
				'return' => true,
			)
		);
		\WP_Mock::wpFunction(
			'sanitize_text_field',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'maybe_serialize',
			array(
				'called' => 1,
			)
		);

		\WP_Mock::wpFunction(
			'update_option',
			array(
				'called' => 1,
				'args'   => array( 'Niteoweb.SpiderBlocker.Bots', '', 'no' ),
			)
		);

		$_POST['data'] = '[{"name":"True Bot","re":"TrueBot","desc":"True","state":true}]';

		$plugin->save_list();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_block_rules
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::save_list
	 */
	public function testAjaxUpdateListInvalid() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'check_ajax_referer',
			array(
				'called' => 1,
				'args'   => array(
					'Niteoweb.SpiderBlocker.Nonce',
					'nonce',
				),
			)
		);

		\WP_Mock::wpFunction(
			'wp_send_json_error',
			array(
				'called' => 1,
			)
		);

		$_POST['data'] = '[{INVALID JSON}]';

		$plugin->save_list();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::generate_block_rules
	 */
	public function testSkipRulesGeneration() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'get_home_path',
			array(
				'return' => '/not_here/',
			)
		);

		$plugin->generate_block_rules();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::remove_block_rules
	 */
	public function testRemoveRulesGeneration() {
		global $wp_rewrite;

		$wp_rewrite = \Mockery::mock();
		$wp_rewrite->shouldReceive( 'flush_rules' )->once();

		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'is_admin',
			array(
				'return' => true,
			)
		);

		\WP_Mock::wpFunction(
			'get_home_path',
			array(
				'return' => '/tmp/',
			)
		);
		\WP_Mock::wpFunction(
			'insert_with_markers',
			array(
				'called' => 1,
				'args'   => array(
					'/tmp/.htaccess',
					'NiteowebSpiderBlocker',
					'*',
				),
			)
		);

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
						),
						false
					)
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option',
			array(
				'called' => 1,
			)
		);

		$plugin->remove_block_rules();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_bots
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::robots_file
	 */
	public function testRobotsFilter() {
		$plugin = new SpiderBlocker();

		\WP_Mock::wpFunction(
			'maybe_unserialize',
			array(
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
						),
						false
					),
					true
				),
			)
		);

		\WP_Mock::wpFunction(
			'get_option',
			array(
				'called' => 1,
				'return' => array( 'one', 'two', 'three' ),
			)
		);

		$this->assertEquals(
			$plugin->robots_file( '', true ),
			"User-agent: TrueBot\nDisallow: /\n\n"
		);
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::view_handler_load
	 */
	public function testViewHandlerLoad() {
		$plugin = new SpiderBlocker();

		\WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( $plugin, 'view_handler_scripts' ) );
		$plugin->view_handler_load();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::view_handler_scripts
	 */
	public function testViewHandlerScripts() {
		$plugin = new SpiderBlocker();

		\WP_Mock::userFunction(
			'wp_enqueue_script',
			array(
				'return' => true,
			)
		);
		\WP_Mock::userFunction(
			'wp_enqueue_style',
			array(
				'return' => true,
			)
		);
		\WP_Mock::userFunction(
			'wp_localize_script',
			array(
				'return' => true,
			)
		);
		\WP_Mock::userFunction(
			'wp_create_nonce',
			array(
				'return' => true,
			)
		);

		$plugin->view_handler_scripts();
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_server_software
	 */
	public function testGetServerSoftwareEnv() {
		$plugin = new SpiderBlocker();

		$_ENV['SERVER_SOFTWARE'] = 'Apache';

		\WP_Mock::userFunction(
			'sanitize_text_field',
			array(
				'return' => 'Apache',
			)
		);

		$this->assertEquals(
			'Apache',
			$plugin->get_server_software( 'Apache' )
		);
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_server_software
	 */
	public function testGetServerSoftwareServer() {
		$plugin = new SpiderBlocker();

		$_ENV['SERVER_SOFTWARE']    = '';
		$_SERVER['SERVER_SOFTWARE'] = 'Apache';

		\WP_Mock::userFunction(
			'sanitize_text_field',
			array(
				'return' => 'Apache',
			)
		);

		$this->assertEquals(
			'Apache',
			$plugin->get_server_software( 'Apache' )
		);
	}

	/**
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::__construct
	 * @covers \Niteoweb\SpiderBlocker\SpiderBlocker::get_server_software
	 */
	public function testGetServerSoftwareFalse() {
		$plugin = new SpiderBlocker();

		$_ENV['SERVER_SOFTWARE']    = '';
		$_SERVER['SERVER_SOFTWARE'] = '';

		$this->assertFalse( $plugin->get_server_software( 'Apache' ) );
	}

}
