<?php

namespace WriteligentTests\Core;

use Nette;
use Tester;

class TestCase extends Tester\TestCase {

	/** @var Nette\DI\Container $container */
	private $container;

	protected function getContainer()
	{
		if (empty($this->container)) {
			$this->createContainer();
		}
		return $this->container;
	}

	public function createContainer()
	{
		$rootDir = __DIR__ . '/../../';
		$config = new Nette\Configurator();
		$this->container = $config->setTempDirectory(TEMP_DIR)
			->addConfig(__DIR__ . '/../../config/base.neon', $config::NONE)
			->addParameters(array(
				'appDir' => $rootDir,
				'wwwDir' => $rootDir,
			))
			->createContainer();
	}
}
