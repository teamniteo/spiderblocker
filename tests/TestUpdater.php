<?php

	use Niteoweb\SpiderBlocker\Updater;


	class TestUpdater extends PHPUnit_Framework_TestCase {
		public $plugin_info = array(
			'Name'        => 'Spider Blocker',
			'PluginURI'   => '',
			'Version'     => '1.0.0',
			'Description' => 'Spider Blocker will block most common bots that consume bandwidth and slow down your server.',
			'Author'      => 'NiteoWeb Ltd.',
			'AuthorURI'   => 'http://www.niteoweb.com',
			'TextDomain'  => '',
			'DomainPath'  => '',
			'Network'     => false,
			'Title'       => 'Spider Blocker',
			'AuthorName'  => 'NiteoWeb Ltd.',
		);

		function setUp() {
			\WP_Mock::setUsePatchwork( true );
			\WP_Mock::setUp();
		}

		function tearDown() {
			\WP_Mock::tearDown();
		}

		public function test_init_admin() {
			\WP_Mock::wpFunction( 'get_plugin_data', array(
					'return' => $this->plugin_info,
				)
			);
			\WP_Mock::wpFunction( 'plugin_basename', array(
					'return' => 'spider_blocker/index.php',
				)
			);

			$plugin = new Updater;
			\WP_Mock::expectFilterAdded( 'pre_set_site_transient_update_plugins', array( $plugin, 'checkUpdate' ) );
			$plugin->__construct();
			\WP_Mock::assertHooksAdded();
		}

		public function test_remote() {
			\WP_Mock::wpFunction( 'get_plugin_data', array(
					'return' => $this->plugin_info,
				)
			);
			\WP_Mock::wpFunction( 'plugin_basename', array(
					'return' => 'spider_blocker/index.php',
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_get', array(
					'args'   => '*',
					'return' => array(
						'body' => file_get_contents( __DIR__ . '/fixtures/releases.json' ),
					),
				)
			);
			\WP_Mock::wpFunction( 'is_wp_error', array(
					'args'   => '*',
					'return' => false,
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_retrieve_response_code', array(
					'args'   => '*',
					'return' => 200,
				)
			);

			$plugin = new Updater;
			$plugin->getRemoteInformation();

		}

		public function test_remote_failed() {
			\WP_Mock::wpFunction( 'get_plugin_data', array(
					'return' => $this->plugin_info,
				)
			);
			\WP_Mock::wpFunction( 'plugin_basename', array(
					'return' => 'spider_blocker/index.php',
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_get', array(
					'args'   => '*',
					'return' => array(
						'body' => file_get_contents( __DIR__ . '/fixtures/releases.json' ),
					),
				)
			);
			\WP_Mock::wpFunction( 'is_wp_error', array(
					'args'   => '*',
					'return' => true,
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_retrieve_response_code', array(
					'args'   => '*',
					'return' => 404,
				)
			);

			$plugin = new Updater;
			$plugin->getRemoteInformation();

		}

		public function test_should_update() {
			\WP_Mock::wpFunction( 'get_plugin_data', array(
					'return' => $this->plugin_info,
				)
			);
			\WP_Mock::wpFunction( 'plugin_basename', array(
					'return' => 'spider_blocker/index.php',
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_get', array(
					'args'   => '*',
					'return' => array(
						'body' => file_get_contents( __DIR__ . '/fixtures/releases.json' ),
					),
				)
			);
			\WP_Mock::wpFunction( 'is_wp_error', array(
					'args'   => '*',
					'return' => false,
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_retrieve_response_code', array(
					'args'   => '*',
					'return' => 200,
				)
			);

			$plugin = new Updater;
			$plugin->getRemoteInformation();

		}

		public function test_update() {
			\WP_Mock::wpFunction( 'get_plugin_data', array(
					'return' => $this->plugin_info,
				)
			);
			\WP_Mock::wpFunction( 'plugin_basename', array(
					'return' => 'spider_blocker/index.php',
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_get', array(
					'args'   => '*',
					'return' => array(
						'body' => file_get_contents( __DIR__ . '/fixtures/releases.json' ),
					),
				)
			);
			\WP_Mock::wpFunction( 'is_wp_error', array(
					'args'   => '*',
					'return' => false,
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_retrieve_response_code', array(
					'args'   => '*',
					'return' => 200,
				)
			);
			$transient           = new \stdClass();
			$transient->checked  = 'not_empty';
			$transient->response = array();
			$plugin              = new Updater;
			$obj                 = new \stdClass();
			$obj->slug           = 'index';
			$obj->new_version    = '1.0.1';
			$obj->url            = 'https://api.github.com/repos/niteoweb/spiderblocker/releases/latest';
			$obj->package        = 'https://github.com/niteoweb/spiderblocker/releases/download/v1.0.1/spider_blocker.zip';
			$this->assertEquals( array(
				'spider_blocker/index.php' => $obj,
			), $plugin->checkUpdate( $transient )->response );

		}

		public function test_update_skip() {
			\WP_Mock::wpFunction( 'get_plugin_data', array(
					'return' => $this->plugin_info,
				)
			);
			\WP_Mock::wpFunction( 'plugin_basename', array(
					'return' => 'spider_blocker/index.php',
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_get', array(
					'args'   => '*',
					'return' => array(
						'body' => file_get_contents( __DIR__ . '/fixtures/releases.json' ),
					),
				)
			);
			\WP_Mock::wpFunction( 'is_wp_error', array(
					'args'   => '*',
					'return' => false,
				)
			);
			\WP_Mock::wpFunction( 'wp_remote_retrieve_response_code', array(
					'args'   => '*',
					'return' => 200,
				)
			);
			$transient           = new \stdClass();
			$transient->checked  = false;
			$transient->response = array();
			$plugin              = new Updater;
			$this->assertEquals( array(), $plugin->checkUpdate( $transient )->response );

		}

	}