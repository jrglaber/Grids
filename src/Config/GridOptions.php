<?php
namespace Nayjest\Grids\Config;

use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\EloquentDataRow;

class GridOptions extends FieldConfig{
	const CLASSE    = 'btn btn-default ';
	const STYLE     = 'margin-right: 3px;';

	private $options    = [];
	private $parameters = [];

	/**
	 * GridOptions constructor.
	 *
	 * @param array $options
	 * @param array $parameters
	 */
	public function __construct( array $options, array $parameters )
	{
		parent::__construct();
		$this->options    = $options;
		$this->parameters = $parameters;
	}

	public function field()
	{
		parent::setCallback(function ($val, EloquentDataRow $row){
			$data = $row->getSrc();
			foreach($this->parameters as $param){
				if(isset($data->{$param})){
					$paramsOptions[$param] = $data->{$param};
				}

				$options = [];
				foreach ( $this->options as $option ) {
					$options[] = $this->createLink($option, $paramsOptions);
				}
				return implode('', $options);
			}
		});
		return $this;
	}

	private function createLink($option, $parameters)
	{
		if(!isset($option['route']) || !\Route::has($option['route'])){
			return null;
		}

		if(isset($option['permission']) && \Gate::denies($option['permission'])){
			return null;
		}

		if(isset($option['default']) && !empty($option['default'])){
			$getDefault = $this->_defaults($option['default']);
			if($getDefault){
				$option = array_merge($getDefault,$option);
			}
		}

		if(!isset($option['content']) || is_null($option['content']) || empty($option['content'])){
			return null;
		}

		$route  = route($option['route'],$parameters);
		$url    = isset($option['return']) && $option['return'] === false ? "javascript:void(0);" : $route;
		return \Html::link(
			$url,
			$option['content'],
			[
				'class'         => self::CLASSE . (isset($option['class']) ? $option['class'] : null),
				'style'         => self::STYLE . (isset($option['style']) ? $option['style'] : null),
				'data-route'    => $route,
				'title'         => isset($option['title']) ? $option['title'] : null
			],
			null, false
		);
	}

	private function _defaults($default)
	{
		$defaults = [
			'edit' => [
				'content'   => '<i class="fa fa-edit"></i>',
				'title'     => 'Editar',
			],
			'delete' => [
				'content'   => '<i class="fa fa-trash"></i>',
				'return'    => false,
				'class'     => 'delete-item',
				'title'     => 'Deletar',
			],
		];

		return isset($defaults[$default]) ? $defaults[$default] : null;
	}
}