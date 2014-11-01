<?php

namespace Writeligent\Core;

use Nette;

class TemplateRepository extends Nette\Object
{

	private $directories = array();
	private $sorted = true;

	public function getLayout($presenter, $layout = 'layout')
	{
		return $this->getTemplate($presenter, '@'.$layout);
	}

	public function getTemplate($presenter, $action, $control = null, $controlAction = null)
	{
		$directories = $this->getDirectories();
		$parts = $this->splitPresenterName($presenter);

		$files = array($action);
		if (!empty($controlAction)) {
			$controlPath = 'controls/' . str_replace('-', '/', $control);
			$files = array(
				$controlPath . '/' . $action . '/' . $controlAction,
				$controlPath . '/' . $controlAction
			);
		}

		$candidates = array();
		$candidates = $this->extendCandidates($candidates, $directories, $parts, $files);
		do {
			array_pop($parts);
			$candidates = $this->extendCandidates($candidates, $directories, $parts, $files);
		} while (!empty($parts));
		return $this->checkCandidates($candidates);
	}

	public function getDirectories()
	{
		$this->sortDirectories();
		$flat_directories = array();
		foreach ($this->directories as $levelDirectories) {
			foreach ($levelDirectories as $directory) {
				$flat_directories[] = $directory;
			}
		}
		return $flat_directories;
	}

	public function addDirectory($directory, $priority = 10)
	{
		$this->directories[$priority][] = $directory;
		$this->sorted = false;
	}

	protected function splitPresenterName($name)
	{
		$parts = explode(':', $name);
		for ($i = 0; $i < count($parts) - 1; $i++) {
			$parts[$i] = $parts[$i] . 'Module';
		}
		return $parts;
	}

	protected function extendCandidates($candidates, $directories, $parts, $files)
	{
		foreach ($directories as $directory) {
			foreach ($files as $file) {
				$candidates[] = $this->parsePath($directory, $parts, $file);
			}
		}
		return $candidates;
	}

	protected function parsePath($directory, $parts, $file)
	{
		return $directory . '/' . (!empty($parts) ? implode('/', $parts) . '/' : '') . $file . '.latte';
	}

	protected function checkCandidates($candidates)
	{
		foreach ($candidates as $candidate) {
			if (is_file($candidate)) {
				return $candidate;
			}
		}
		throw new \LogicException('Template not found. Candidates are '. implode(', ', $candidates));
	}

	protected function sortDirectories()
	{
		if (!$this->sorted) {
			ksort($this->directories);
			$this->sorted = true;
		}
	}

}
