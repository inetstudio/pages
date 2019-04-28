@inject('pagesService', 'InetStudio\PagesPackage\Pages\Services\Back\ItemsService')

@php
    $count = $pagesService->getPagesStatistic();
@endphp

<li>
    <small class="label label-default">{{ $count }}</small>
    <span class="m-l-xs">Страницы</span>
</li>
