<?php

namespace WriteligentTests\Core;

/**
* Test: Template repository
*/
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class TemplateRepositoryTest extends TestCase {

	private $templateRepository;

	public function setUp()
	{
		$this->templateRepository = $this->getContainer()->getByType('Writeligent\Core\TemplateRepository');
		$this->templateRepository->addDirectory(__DIR__ . '/mocks/templates', 10);
		$this->templateRepository->addDirectory(__DIR__ . '/mocks/force-templates', 1);
	}

	public function testGetGlobalLayout()
	{
		$layoutFile = $this->templateRepository->getLayout('Frontend:Homepage');
		Assert::same(__DIR__ . '/mocks/templates/@layout.latte', $layoutFile);
	}

	public function testGetModuleLayout()
	{
		$layoutFile = $this->templateRepository->getLayout('Admin:Dashboard');
		Assert::same(__DIR__ . '/mocks/templates/AdminModule/@layout.latte', $layoutFile);
	}

	public function testGetPresenterLayout()
	{
		$layoutFile = $this->templateRepository->getLayout('Admin:Login');
		Assert::same(__DIR__ . '/mocks/templates/AdminModule/Login/@layout.latte', $layoutFile);
	}

	public function testGetTemplate()
	{
		$templateFile = $this->templateRepository->getTemplate('Homepage', 'default');
		Assert::same(__DIR__ . '/mocks/templates/Homepage/default.latte', $templateFile);
	}

	public function testGetTemplateInModule()
	{
		$templateFile = $this->templateRepository->getTemplate('Frontend:Homepage', 'default');
		Assert::same(__DIR__ . '/mocks/templates/FrontendModule/Homepage/default.latte', $templateFile);
	}

	public function testGetControlTemplateByPresenterAndAction()
	{
		$templateFile = $this->templateRepository->getTemplate('Frontend:Homepage', 'default', 'Login', 'small');
		Assert::same(__DIR__ . '/mocks/templates/FrontendModule/Homepage/controls/Login/default/small.latte', $templateFile);
	}

	public function testGetControlTemplateByPresenter()
	{
		$templateFile = $this->templateRepository->getTemplate('Frontend:Homepage', 'detail', 'Login', 'small');
		Assert::same(__DIR__ . '/mocks/templates/FrontendModule/Homepage/controls/Login/small.latte', $templateFile);
	}

	public function testGetControlTemplateForModule()
	{
		$templateFile = $this->templateRepository->getTemplate('Admin:Dashboard', 'detail', 'Login', 'small');
		Assert::same(__DIR__ . '/mocks/templates/AdminModule/controls/Login/small.latte', $templateFile);
	}

	public function testGetControlTemplateGlobal()
	{
		$templateFile = $this->templateRepository->getTemplate('Admin:Dashboard', 'detail', 'Login', 'big');
		Assert::same(__DIR__ . '/mocks/templates/controls/Login/big.latte', $templateFile);
	}

	public function testGetControlTemplatePriority()
	{
		$templateFile = $this->templateRepository->getTemplate('Admin:Dashboard', 'detail', 'Login', 'medium');
		Assert::same(__DIR__ . '/mocks/force-templates/controls/Login/medium.latte', $templateFile);
	}


}

run(new TemplateRepositoryTest);
