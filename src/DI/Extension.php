<?php

namespace Writeligent\Core\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Loaders\NetteLoader;
use Nette\Configurator;

// Nette 2.0 & 2.1 compatibility, thx @HosipLan
if (!class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
	class_alias('Nette\Config\Compiler', 'Nette\DI\Compiler');
	class_alias('Nette\Config\Helpers', 'Nette\DI\Config\Helpers');
}
if (isset(NetteLoader::getInstance()->renamed['Nette\Configurator']) || !class_exists('Nette\Configurator')) {
	unset(NetteLoader::getInstance()->renamed['Nette\Configurator']);
	class_alias('Nette\Config\Configurator', 'Nette\Configurator');
}

class Extension extends CompilerExtension
{
	const NAME = 'Writeligent.Core';

	protected $defaults = [
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('templateRepository'))
			->setClass('Writeligent\Core\TemplateRepository');
	}

	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function (Configurator $sender, Compiler $compiler) {
			$compiler->addExtension(Extension::NAME, new Extension());
		};
	}
}
