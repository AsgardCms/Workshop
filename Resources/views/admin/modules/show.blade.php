@extends('layouts.master')

@section('content-header')
    <h1>
        <small>
            <a href="{{ route('admin.workshop.modules.index') }}" data-toggle="tooltip"
               title="" data-original-title="{{ trans('core::core.back') }}">
                <i class="fa fa-reply"></i>
            </a>
        </small>
        {{ $module->name }} <small>{{ trans('workshop::modules.module') }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('user::users.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.workshop.modules.index') }}">{{ trans('workshop::modules.breadcrumb.modules') }}</a></li>
        <li class="active">{{ trans('workshop::modules.viewing module') }} {{ $module->name }}</li>
    </ol>
@stop

@section('styles')
    <style>
        .module-type {
            text-align: center;
        }
        .module-type span {
            display: block;
        }
        .module-type i {
            font-size: 124px;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <?php $status = $module->enabled() ? 'disable' : 'enable'; ?>
                        <button class="btn btn-box-tool" data-toggle="tooltip"
                                title="" data-original-title="{{ trans("workshop::modules.publish assets") }}">
                            <i class="fa fa-cloud-upload"></i>
                            {{ trans("workshop::modules.publish assets") }}
                        </button>
                        <button class="btn btn-box-tool" data-toggle="tooltip"
                                title="" data-original-title="{{ trans("workshop::modules.{$status}") }}">
                            <i class="fa fa-toggle-{{ $module->enabled() ? 'on' : 'off' }}"></i>
                            {{ trans("workshop::modules.{$status}") }}
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6 module-details">
                            <div class="module-type pull-left">
                                <i class="fa fa-cube"></i>
                                <span>{{ $module->json()->version }}</span>
                            </div>
                            <h2>{{ ucfirst($module->getName()) }}</h2>
                            <p>{{ $module->getDescription() }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><em>More coming soon...</em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
