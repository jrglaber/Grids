<?php
/** @var Nayjest\Grids\Components\Filters\DateRangePicker $component */
    $id = uniqid();
    $pos = strpos($component->getStartValue(), '-') === false;
    $startDate = !empty($component->getStartValue()) ? \Carbon\Carbon::createFromFormat($pos ? trans('date.short') : 'Y-m-d', $component->getStartValue())->format('Y-m-d') : '';
    $endDate = !empty($component->getEndValue()) ? \Carbon\Carbon::createFromFormat($pos ? trans('date.short') : 'Y-m-d', $component->getEndValue())->format('Y-m-d') : '';
?>
@if($component->getLabel())
    <span>
        <span class="glyphicon glyphicon-calendar"></span>
        {!! $component->getLabel() !!}
    </span>
@endif

<input
    class="form-control input-sm"
    style="display: inline; width: 100%; margin-right: 10px"
    name="{!! $component->getInputName() !!}"
    type="text"
    id="{!! $id !!}"
    >

<script>
    document.addEventListener("DOMContentLoaded", function(){
        var options = {!! json_encode($component->getJsOptions()) !!};
        if (!options.format) {
            options.format = 'YYYY-MM-DD';
        }
        var cb = function(start, end) {
            var text;
            if (start.isValid() && end.isValid()) {
                text = start.format(options.format) + ' - ' + end.format(options.format);
            } else {
                text = '';
            }
            $('#{!! $id !!}').val(text);
        };
        var onApplyDate = function(ev, picker) {
            var start = $('[name="{!! $component->getStartInputName() !!}"]');
            start.val(picker.startDate.format(options.format));
            var end = $('[name="{!! $component->getEndInputName() !!}"]');
            end.val(picker.endDate.format(options.format));
            @if($component->isSubmittedOnChange())
            	end.get(0).form.submit();
            @endif
        };
        $('#{!! $id !!}')
            .daterangepicker(options, cb)
            .on('apply.daterangepicker', onApplyDate)
            .on('change', function(){
                if (!$('#{!! $id !!}').val()) {
                    $('[name="{!! $component->getStartInputName() !!}"]').val('');
                    $('[name="{!! $component->getEndInputName() !!}"]').val('');
                }
            });
        cb(
            moment("{{ $startDate }}"),
            moment("{{ $endDate }}")
        );
    });
</script>

{!! Form::hidden($component->getStartInputName(), $startDate) !!}
{!! Form::hidden($component->getEndInputName(), $endDate) !!}

