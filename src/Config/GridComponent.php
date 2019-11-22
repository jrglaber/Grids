<?php

namespace Nayjest\Grids\Config;

use Nayjest\Grids\Components\HtmlTag;

class GridComponent {
	public function createComponent(array $options = [])
	{
		if(!isset($options['tag'])){
			throw new \Exception('Por favor, informe a tag');
		}

		$htmlTag = new HtmlTag();
		$htmlTag->setTagName($options['tag']);

		if(isset($options['content'])){
			$htmlTag->setContent($options['content']);
		}

		if(isset($options['attributes']) && is_array($options['attributes'])){
			$htmlTag->setAttributes($options['attributes']);
		}

		if(isset($options['component'])){
			$htmlTag->addComponent($options['component']);
		}

		if(isset($options['components']) && is_array($options['components']) && count($options['components'])){
			$htmlTag->addComponents($options['components']);
		}

		return $htmlTag;
	}
}