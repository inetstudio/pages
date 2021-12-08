@extends('admin::back.layouts.app')

@php
    $title = ($item->id) ? 'Редактирование страницы' : 'Создание страницы';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.pages::back.partials.breadcrumbs.form')
    @endpush

    <div class="wrapper wrapper-content">
        <div class="ibox">
            <div class="ibox-title">
                <a class="btn btn-sm btn-white m-r-xs" href="{{ route('back.pages.index') }}">
                    <i class="fa fa-arrow-left"></i> Вернуться назад
                </a>
                @if ($item->id && $item->href)
                    <a class="btn btn-sm btn-white" href="{{ $item->href }}" target="_blank">
                        <i class="fa fa-eye"></i> Посмотреть на сайте
                    </a>
                @endif
            </div>
        </div>

        {!! Form::info() !!}

        {!! Form::open(['url' => (! $item->id) ? route('back.pages.store') : route('back.pages.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data']) !!}

        @if ($item->id)
            {{ method_field('PUT') }}
        @endif

        {!! Form::hidden('page_id', (! $item->id) ? '' : $item->id, ['id' => 'object-id']) !!}

        {!! Form::hidden('page_type', get_class($item), ['id' => 'object-type']) !!}

        <div class="ibox">
            <div class="ibox-title">
                {!! Form::buttons('', '', ['back' => 'back.pages.index']) !!}
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel-group float-e-margins" id="mainAccordion">

                            {!! Form::meta('', $item) !!}

                            {!! Form::social_meta('', $item, ['config' => config('pages.images.og_image') ?? []]) !!}

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain"
                                           aria-expanded="true">Основная информация</a>
                                    </h5>
                                </div>
                                <div id="collapseMain" class="collapse show" aria-expanded="true">
                                    <div class="panel-body">

                                        {!! Form::string('title', $item->title, [
                                            'label' => [
                                                'title' => 'Заголовок',
                                            ],
                                            'field' => [
                                                'class' => 'form-control slugify',
                                                'data-slug-url' => route('back.pages.getSlug'),
                                                'data-slug-target' => 'slug',
                                            ],
                                        ]) !!}

                                        {!! Form::string('slug', $item->slug, [
                                            'label' => [
                                                'title' => 'URL',
                                            ],
                                            'field' => [
                                                'class' => 'form-control slugify',
                                                'data-slug-url' => route('back.pages.getSlug'),
                                                'data-slug-target' => 'slug',
                                            ],
                                        ]) !!}

                                        <x-inetstudio.uploads-package.uploads::fields.back.media
                                            label="Превью"
                                            field-name="preview"
                                            :item="$item"
                                        />

                                        <x-inetstudio.admin-panel.base::fields.back.wysiwyg
                                            label="Лид"
                                            field-name="description"
                                            :item="$item"
                                            :simple="true"
                                        />

                                        <x-inetstudio.admin-panel.base::fields.back.wysiwyg
                                            label="Содержимое"
                                            field-name="content"
                                            :item="$item"
                                        />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-footer">
                {!! Form::buttons('', '', ['back' => 'back.pages.index']) !!}
            </div>
        </div>

        {!! Form::close()!!}
    </div>
@endsection
