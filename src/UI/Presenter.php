<?php

namespace Writeligent\Core\UI;

use Nette;

class Presenter extends Nette\Application\UI\Presenter
{
	/**
	 * @return void
	 * @throws Nette\Application\BadRequestException if no template found
	 * @throws Nette\Application\AbortException
	 */
	public function sendTemplate()
	{
		$template = $this->getTemplate();
		if (!$template->getFile()) {
			$file = $this->getTemplateRepository()->getTemplate($this->name, $this->action);
			$template->setFile($file);
		}

		$this->sendResponse(new \Nette\Application\Responses\TextResponse($template));
	}

	public function findLayoutTemplateFile()
	{
		if ($this->layout === false) {
			return;
		}
		return $this->getTemplateRepository()->getLayout($this->name, $this->layout ? $this->layout : 'layout');
	}

	/**
	 * @return Writeligent\Core\TemplateRepository
	 */
	protected function getTemplateRepository()
	{
		return $this->context->getByType('Writeligent\Core\TemplateRepository');
	}
}
