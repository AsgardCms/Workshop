@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('workshop::modules.title') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('user::users.breadcrumb.home') }}</a></li>
    <li class="active">{{ trans('workshop::modules.breadcrumb.modules') }}</li>
</ol>
@stop

@section('styles')
    {!! Theme::style('css/vendor/iCheck/flat/blue.css') !!}
    <style>
        .jsUpdateModule {
            transition: all .5s ease-in-out;
        }
    </style>
@stop

@section('content')
{!! Form::open(['route' => 'admin.workshop.modules.store', 'method' => 'post']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <?php foreach ($modules->chunk((int)ceil($modules->count()/2)) as $moduleChunk): ?>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                @foreach($moduleChunk as $module)
                                    <li style="margin: 5px 0;">
                                        <div class="checkbox" style="display: inline;">
                                            <label for="{{ $module }}">
                                                <input id="{{ $module }}" name="modules[{{ $module }}]" type="checkbox" class="flat-blue" <?php echo Module::active($module) ? 'checked' : '' ?> <?php echo isset($coreModules[$module->getName()]) ? 'disabled' : ''; ?> value="true" /> {{ $module }}
                                            </label>
                                        </div>
                                        <a href="{{ $module->versionUrl }}" target="_blank">
                                            <span class="label label-default">{{ $module->version }}</span>
                                        </a>
                                        <button type="button" data-loading-text="Updating..." class="btn btn-primary btn-xs jsUpdateModule" autocomplete="off" data-module="{{ $module->getName() }}">
                                            Update
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" id="myButton" class="btn btn-primary btn-flat">{{ trans('workshop::modules.button.save module configuration') }}</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop

@section('scripts')
<script>
$( document ).ready(function() {
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
    $('.jsUpdateModule').on('click', function(e) {
        $(this).data('loading-text', '<i class="fa fa-spinner fa-spin"></i> Loading ...');
        var $btn = $(this).button('loading');
        var token = '<?= csrf_token() ?>';
        $.ajax({
            type: 'POST',
            url: '<?= route('admin.workshop.modules.update') ?>',
            data: {module: $btn.data('module'), _token: token},
            success: function(data) {
                console.log(data);
                if (data.updated) {
                    $btn.button('reset');
                    $btn.removeClass('btn-primary');
                    $btn.addClass('btn-success');
                    $btn.html('<i class="fa fa-check"></i> Module updated!')
                    setTimeout(function() {
                        $btn.removeClass('btn-success');
                        $btn.addClass('btn-primary');
                        $btn.html('Update')
                    }, 2000);
                }
            }
        });
    });
});
</script>
@stop
