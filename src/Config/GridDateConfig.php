<?php

namespace Nayjest\Grids\Config;


use Carbon\Carbon;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\DataProvider;

class GridDateConfig
{
    const PRE_COLUMN_DATE = 'filters_row_column_';

    private $columnDate = '';
    private $endDefaultDate = '';
    private $options = [];

    public function __construct($columnDate,$endDefaultDate = '', array $options = [])
    {
        $this->columnDate = $columnDate;

        if(!empty($endDefaultDate) && $endDefaultDate !== false){
            if($endDefaultDate instanceof \DateTime){
                $endDefaultDate = $endDefaultDate->format('Y-m-d');
            }
            else{
                $endDefaultDate = Carbon::parse($this->formatDate($endDefaultDate))->format('Y-m-d');
            }
        }
        else{
            if($endDefaultDate === false) {
                unset($this->options['initial_date']);
                $endDefaultDate = '';
            }
            else{
                $endDefaultDate = Carbon::now()->format('Y-m-d');
            }
        }
        $this->endDefaultDate = $endDefaultDate;
        $this->options = $options;
    }

    public function getConfig()
    {
        $iniDateDefault = !empty($this->endDefaultDate) ? Carbon::createFromFormat('Y-m-d',date('Y-m-01'))->subMonth(3) : '';
        $jsOptions = ( isset($this->options['js']) ? $this->options['js'] : [] );

        if(!is_array($this->columnDate)){
            $this->columnDate = [$this->columnDate];
        }

        $returnDateConfig = [];
        foreach ($this->columnDate as $date) {
            $returnDateConfig[] = (new RenderFunc(function () {
                /*return \Html::style('js/daterangepicker/daterangepicker-bs3.min.css')
                . \HTML::script('plugins/moment/moment-with-locales.min.js')
                . \HTML::script('js/daterangepicker/daterangepicker.min.js')*/
                return "<style>.daterangepicker td.available.active,.daterangepicker li.active,.daterangepicker li:hover {color:black !important;font-weight: bold;}</style>";
            }))->setRenderSection(self::PRE_COLUMN_DATE . $date);

            $returnDateConfig[] = (new DateRangePicker())
                ->setName($date)
                ->setRenderSection(self::PRE_COLUMN_DATE . $date)
                ->setDefaultValue([( isset($this->options['initial_date']) ? $this->options['initial_date']->format('Y-m-d') : (!empty($iniDateDefault) ? $iniDateDefault->format('Y-m-d') : '') ), $this->endDefaultDate])
//                ->setDefaultValue([date('Y-01-01'), $this->endDefaultDate])
                ->setJsOptions(array_merge($jsOptions, [
                    'format' => trans('grids::grid.date.format'),
                    'locale' => [
                        'format' => trans('grids::grid.date.format'),
                        'applyLabel' => trans('grids::grid.date.usar'),
                        'cancelLabel' => trans('grids::grid.date.cancelar'),
                        'fromLabel' => trans('grids::grid.date.de'),
                        'toLabel' => trans('grids::grid.date.ate'),
                        'customRangeLabel' => trans('grids::grid.date.personalizado'),
                        'daysOfWeek' => [trans('grids::grid.date.dias.dom'),trans('grids::grid.date.dias.seg'),trans('grids::grid.date.dias.ter'),trans('core::grid.date.dias.qua'),trans('grids::grid.date.dias.qui'),trans('grids::grid.date.dias.sex'),trans('grids::grid.date.dias.sab')],
                        'monthNames' => [trans('grids::grid.date.meses.janeiro'),trans('grids::grid.date.meses.fevereiro'),trans('grids::grid.date.meses.marco'),trans('grids::grid.date.meses.abril'),trans('grids::grid.date.meses.maio'),trans('grids::grid.date.meses.junho'),trans('grids::grid.date.meses.julho'),trans('grids::grid.date.meses.agosto'),trans('grids::grid.date.meses.setembro'),trans('grids::grid.date.meses.outubro'),trans('grids::grid.date.meses.novembro'),trans('grids::grid.date.meses.dezembro')],
                    ]
                ]))
                ->setFilteringFunc(function($value, DataProvider $provider) use ($date) {
                    foreach ($value as $k => $v) {
                        if($v) {
                            $value[$k] = Carbon::parse($this->formatDate($v))->format('Ymd');
                        }
                    }
                    $provider->filter(\DB::raw("DATE_FORMAT({$date}, '%Y%m%d')"), '>=', $value[0]);
                    $provider->filter(\DB::raw("DATE_FORMAT({$date}, '%Y%m%d')"), '<=', $value[1]);
                })
                ->setTemplate('grids::grid.date_range_picker');
        }

        return $returnDateConfig;
    }

    private function formatDate($date)
    {
        if(app()->getLocale() != 'en'){
            $date = str_replace('/', '-', $date);
        }
        
        return $date;
    }
}
