<?php

namespace Nayjest\Grids\Config;


use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\THead;

class GridTHead
{
    private $thead;
    private $useHead = true;
    private $componentsFilter = [];

    public function __construct()
    {
        $this->thead = new THead();
        $this->defaultComponentsFilter();
    }

    public function setUseHead($use = true)
    {
        $this->useHead = $use;
        return $this;
    }

    public function isUseHead()
    {
        return $this->useHead;
    }

    public function addComponentHead(HtmlTag $filter)
    {
        $this->componentsFilter[] = $filter;
    }

    public function render($name, $columnDate = '', $endDefaultDate = '')
    {
        $filterRow = new FiltersRow();
        if (!empty($columnDate)) {
            $dateConfig = new GridDateConfig($columnDate,$endDefaultDate);
            $filterRow->addComponents($dateConfig->getConfig());
        }

        $componentsHead = [
            new ColumnHeadersRow(),
            $filterRow,
        ];
        if(count($this->componentsFilter)){
            $componentsHead[] = (new OneCellRow())->setComponents($this->componentsFilter)->setRenderSection(THead::SECTION_BEGIN);
        }

        $this->thead->setComponents($componentsHead);

        return $this->thead;
    }

    private function defaultComponentsFilter()
    {
        $this->componentsFilter = [
            (new RecordsPerPage())
                ->setVariants([20,50,100])
                ->setTemplate('grids::grid.records_per_page'),
            //(new ColumnsHider())->setTemplate('grids::grid.columns_hider'),
            (new HtmlTag())
                ->setTagName('button')
                ->setAttributes([
                    'type' => 'submit',
                    'class' => 'btn btn-default btn-label-left no-loading',
                ])
                ->setContent('<span><i class="fa fa-search"></i></span> ' . trans('grids::grid.filtrar')),
        ];
    }
}
