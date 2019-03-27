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
                <a class="btn btn-sm btn-white m-r-xs" href="{{ route('back.articles.index') }}">
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

                                {!! Form::social_meta('', $item) !!}

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain" aria-expanded="true">Основная информация</a>
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

                                            @php
                                                $previewImageMedia = $item->getFirstMedia('preview');
                                                $previewCrops = config('pages.images.crops.page.preview') ?? [];

                                                foreach ($previewCrops as &$previewCrop) {
                                                    $previewCrop['value'] = isset($previewImageMedia) ? $previewImageMedia->getCustomProperty('crop.'.$previewCrop['name']) : '';
                                                }
                                            @endphp

                                            {!! Form::crop('preview', $previewImageMedia, [
                                                'label' => [
                                                    'title' => 'Превью',
                                                ],
                                                'image' => [
                                                    'filepath' => isset($previewImageMedia) ? url($previewImageMedia->getUrl()) : '',
                                                    'filename' => isset($previewImageMedia) ? $previewImageMedia->file_name : '',
                                                ],
                                                'crops' => $previewCrops,
                                                'additional' => [
                                                    [
                                                        'title' => 'Описание',
                                                        'name' => 'description',
                                                        'value' => isset($previewImageMedia) ? $previewImageMedia->getCustomProperty('description') : '',
                                                    ],
                                                    [
                                                        'title' => 'Copyright',
                                                        'name' => 'copyright',
                                                        'value' => isset($previewImageMedia) ? $previewImageMedia->getCustomProperty('copyright') : '',
                                                    ],
                                                    [
                                                        'title' => 'Alt',
                                                        'name' => 'alt',
                                                        'value' => isset($previewImageMedia) ? $previewImageMedia->getCustomProperty('alt') : '',
                                                    ],
                                                ],
                                            ]) !!}

                                            {!! Form::wysiwyg('description', $item->description, [
                                                'label' => [
                                                    'title' => 'Лид',
                                                ],
                                                'field' => [
                                                    'class' => 'tinymce-simple',
                                                    'type' => 'simple',
                                                    'id' => 'description',
                                                ],
                                            ]) !!}

                                            {!! Form::wysiwyg('content', $item->content, [
                                                'label' => [
                                                    'title' => 'Содержимое',
                                                ],
                                                'field' => [
                                                    'class' => 'tinymce',
                                                    'id' => 'content',
                                                    'hasImages' => true,
                                                ],
                                                'images' => [
                                                    'media' => $item->getMedia('content'),
                                                    'fields' => [
                                                        [
                                                            'title' => 'Описание',
                                                            'name' => 'description',
                                                        ],
                                                        [
                                                            'title' => 'Copyright',
                                                            'name' => 'copyright',
                                                        ],
                                                        [
                                                            'title' => 'Alt',
                                                            'name' => 'alt',
                                                        ],
                                                    ],
                                                ],
                                            ]) !!}

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
