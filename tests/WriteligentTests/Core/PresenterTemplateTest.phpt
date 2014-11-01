<?php

namespace WriteligentTests\Core;

/**
* Test: Presenter templates
*/
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/presenters.php';
class PresenterTemplateTest extends TestCase {

	private $tester;

	public function setUp()
	{
		$templateRepository = $this->getContainer()->getByType('Writeligent\Core\TemplateRepository');
		$templateRepository->addDirectory(__DIR__ . '/mocks/templates', 10);
	}

	public function testTemplate()
	{

		$this->tester = new \PresenterTester\PresenterTester($this->getContainer()->getByType('\Nette\Application\IPresenterFactory'));
		$this->tester->setPresenter('Frontend:Homepage');
		$response = $this->tester->run();
		Assert::same('foo-bar', trim((string) $response->getSource()));
	}

	public function testTemplateAction()
	{

		$this->tester = new \PresenterTester\PresenterTester($this->getContainer()->getByType('\Nette\Application\IPresenterFactory'));
		$this->tester->setPresenter('Frontend:Homepage');
		$this->tester->setAction('foo');
		$response = $this->tester->run();
		Assert::same('foo-foo', trim((string) $response->getSource()));
	}

	public function testLayout()
	{

		$this->tester = new \PresenterTester\PresenterTester($this->getContainer()->getByType('\Nette\Application\IPresenterFactory'));
		$this->tester->setPresenter('Admin:Login');
		$response = $this->tester->run();
		Assert::same('bar-bar', trim((string) $response->getSource()));
	}

	public function testWihoutLayout()
	{

		$this->tester = new \PresenterTester\PresenterTester($this->getContainer()->getByType('\Nette\Application\IPresenterFactory'));
		$this->tester->setPresenter('Admin:Login');
		$this->tester->getPresenterComponent()->setLayout(false);
		$response = $this->tester->run();
		Assert::same('bar', trim((string) $response->getSource()));
	}

	public function testWihtOtherLayout()
	{

		$this->tester = new \PresenterTester\PresenterTester($this->getContainer()->getByType('\Nette\Application\IPresenterFactory'));
		$this->tester->setPresenter('Admin:Login');
		$this->tester->getPresenterComponent()->setLayout('foo');
		$response = $this->tester->run();
		Assert::same('foo2-bar', trim((string) $response->getSource()));
	}


}

run(new PresenterTemplateTest);
