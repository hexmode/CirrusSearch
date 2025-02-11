<?php

namespace CirrusSearch\Maintenance;

use CirrusSearch\CirrusTestCase;
use CirrusSearch\Test\NoopPrinter;

class ConfigUtilsTest extends CirrusTestCase {
	public function scanAvailablePluginsProvider() {
		return [
			'no plugins reported' => [
				[], [], []
			],
			'plugins included but empty' => [
				[], [], [ 'plugins' => [] ],
			],
			'with some custom plugins' => [
				[ 'test-plugin' ],
				[],
				[
					'plugins' => [
						[ 'name' => 'test-plugin' ],
					]
				]
			],
			'filters plugins if requested' => [
				[],
				[ 'test-plugin' ],
				[
					'plugins' => [
						[ 'name' => 'test-plugin' ],
					]
				]
			],
		];
	}

	/**
	 * @covers \CirrusSearch\Maintenance\ConfigUtils::scanAvailablePlugins
	 * @covers \CirrusSearch\Maintenance\ConfigUtils::scanModulesOrPlugins
	 * @dataProvider scanAvailablePluginsProvider
	 */
	public function testScanAvailablePlugins( array $expectedPlugins, array $bannedPlugins, array $nodeResponse ) {
		$client = $this->createMock( \Elastica\Client::class );
		$client->method( 'request' )
			->with( '_nodes' )
			->willReturn( new \Elastica\Response( [
				'nodes' => [
					'somenode' => $nodeResponse
				]
			], 200 ) );

		$utils = new ConfigUtils( $client, new NoopPrinter() );
		$availablePlugins = $utils->scanAvailablePlugins( $bannedPlugins );
		$this->assertEquals( $expectedPlugins, $availablePlugins );
	}
}
