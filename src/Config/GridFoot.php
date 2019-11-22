<?php

namespace Nayjest\Grids\Config;


use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\TFoot;

class GridFoot {
	private $footer;
	private $componentsFooter = [];
	private $usePager = false;
	private $useFoot = true;

	public function __construct() {
		$this->footer = new TFoot();
	}

	/**
	 * @return bool
	 */
	public function isUseFoot() {
		return $this->useFoot;
	}

	/**
	 * @param bool $useFoot
	 */
	public function setUseFoot( $useFoot = true ) {
		$this->useFoot = $useFoot;
	}

	protected function getDefaultComponents()
	{
		return (new OneCellRow())
			->addComponent(new Pager());
	}

	public function addComponentFoot(HtmlTag $filter)
	{
		$this->componentsFooter[] = $filter;
		return $this;
	}

	public function viewPager($view = true)
	{
		$this->usePager = $view;
		return $this;
	}

	public function render()
	{
		if(count($this->componentsFooter)){
			$this->footer->setComponents($this->componentsFooter);

			if($this->usePager){
				$this->footer->addComponent($this->getDefaultComponents());
			}
		}
		return $this->footer;
	}
}