<?php

namespace CirrusSearch\Maintenance;

use CirrusSearch\Search\TextIndexField;

class ArchiveMappingConfigBuilder extends MappingConfigBuilder {
	public const VERSION = '1.0';

	public function buildConfig() {
		return [
			'dynamic' => false,
			'properties' => [
				'namespace' => $this->searchIndexFieldFactory
					->newLongField( 'namespace' )
					->getMapping( $this->engine ),
				'title' => $this->searchIndexFieldFactory->newStringField( 'title',
					TextIndexField::ENABLE_NORMS )->setMappingFlags( $this->flags )->getMapping( $this->engine ),
				'wiki' => $this->searchIndexFieldFactory
					->newKeywordField( 'wiki' )
					->getMapping( $this->engine ),
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function isForPageIndexes(): bool {
		return false;
	}

	/**
	 * @return bool
	 */
	public function canOptimizeAnalysisConfig() {
		return true;
	}
}
