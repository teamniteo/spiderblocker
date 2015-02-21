<?php

	class TestExport extends PHPUnit_Framework_TestCase {

		function setUp() {
			\WP_Mock::setUp();
			parent::setUp();
		}

		function tearDown() {
			\WP_Mock::tearDown();
			parent::tearDown();
		}

	}