<?php

namespace Nayjest\Grids\Config;

use Carbon\Carbon;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\Grid;

class GridConfig
{
    const EXTENSION_NAME = '_grid';

    private $gridConfig;
    private $gridThead;
    private $gridFoot;
    private $gridComponent;

    private $dataProvider;

    private $name = 'grid';
    private $columnDate = '';
	private $endDefaultDate = '';

    public function __construct($name)
    {
        $this->name = $name;

        $this->gridConfig = new \Nayjest\Grids\GridConfig();
        $this->gridConfig->setName($this->name . self::EXTENSION_NAME);

        $this->gridThead        = new GridTHead();
        $this->gridFoot         = new GridFoot();
        $this->gridComponent    = new GridComponent();
    }

	/**
	 * @return GridTHead
	 */
	public function getGridThead() {
		return $this->gridThead;
	}

	/**
	 * @return GridFoot
	 */
	public function getGridFoot() {
		return $this->gridFoot;
	}

	/**
	 * @return GridComponent
	 */
	public function getGridComponent() {
		return $this->gridComponent;
	}

    public function setPageSize($size = 50)
    {
        $this->gridConfig->setPageSize($size);
        return $this;
    }

    public function setDataProvider($query)
    {
        $this->dataProvider = new EloquentDataProvider($query);
        $this->gridConfig->setDataProvider($this->dataProvider);
        return $this;
    }

    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    public function setTimeCache($time = 0)
    {
        if($time > 0){
            $this->gridConfig->setCachingTime($time);
        }
        return $this;
    }

    public function addColumn(FieldConfig $column)
    {
        $this->gridConfig->addColumn($column);
        return $this;
    }

    public function addColumnOptions(array $options = [], array $params = [])
    {
        $this->addColumn(
	        (new GridOptions($options, $params))
	            ->field()
		        ->setName('options')
		        ->setLabel(trans('grids::grid.opcoes'))
        );
        return $this;
    }

    public function setColumnDate($columnName = '', $endDefaultDate = '')
    {
        if(!empty($columnName)){
            $this->columnDate = $columnName;
        }
        $this->endDefaultDate = !empty($endDefaultDate) || $endDefaultDate === false ? $endDefaultDate : Carbon::now()->addMonth(5);
        return $this;
    }

    public function render()
    {
        $components = [];

        if($this->gridThead->isUseHead()) {
            $components[] = $this->gridThead->render($this->name, $this->columnDate, $this->endDefaultDate);
        }

        if($this->gridFoot->isUseFoot()){
            $components[] = $this->gridFoot->render();
        }

        if(count($components)){
            $this->gridConfig->setComponents($components);
        }

        $grid = new Grid($this->gridConfig);

        return $grid->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
