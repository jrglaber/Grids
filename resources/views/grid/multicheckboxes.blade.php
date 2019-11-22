<?php
$cfg = $filter->getConfig();
$rand = rand(1000,9999);
?>
<div style="position: relative;">
    <button class="btn btn-default no-loading btn-{{$rand}}"><i class="glyphicon glyphicon-th-list"></i></button>
    <div class="box-options box{{$rand}}" style="width: auto; min-width: 150px; position: absolute; background: #fff; padding: 5px; border: 1px solid #ccc; border-radius: 3px; left: 35px; top: 0; display: none;">
        <?php foreach ($filter->getConfig()->getOptions() as $value => $label): ?>
        <?php
        $maybe_selected = (
                (
                        (is_array($filter->getValue()) && in_array($value, $filter->getValue())) ||
                        $filter->getValue() == $value
                )
                && $filter->getValue() !== ''
                && $filter->getValue() !== null
        ) ? 'checked="cheked"' : ''
        ?>
        <label style="margin-right: 5px; display: block;">
            <input type="checkbox" class="input-sm" value="{{ $value }}" name="{{ $filter->getInputName() }}{{ $cfg->isMultipleMode() ? '[]' : '' }}" {{ $maybe_selected }} style="float: left; margin-right: 5px;"> {{ $label }}
        </label>
        <?php endforeach ?>
    </div>
</div>

<script>
    $(".btn-{{$rand}}").click(function(event){
        event.preventDefault();
        {{--var box  = $(".box{{$rand}}");--}}
        var box  = $(this).parent().find('.box-options');
        if(box.css('display') == 'none'){
            $('.box-options').hide();
            box.show();
        }
        else{
            box.hide();
        }
    });

    {{--var $btn = $(".box{{$rand}}");--}}
    {{--$('body').on('click', function (e) {--}}
        {{--if (--}}
                {{--!$btn.is(e.target)--}}
                {{--&& $btn.has(e.target).length === 0--}}
                {{--&& $(".btn-{{$rand}}").has(e.target).length === 0--}}
        {{--) {--}}
            {{--$btn.hide();--}}
        {{--}--}}
    {{--});--}}
</script>