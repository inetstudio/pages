<div class="btn-nowrap">
    <a class="btn btn-xs btn-default m-r-xs" href="{{ $href }}" target="_blank"><i class="fa fa-eye"></i></a>
    <a href="{{ route('back.pages.edit', [$id]) }}" class="btn btn-xs btn-default m-r-xs"><i class="fa fa-pencil-alt"></i></a>
    <a href="#" class="btn btn-xs btn-danger delete" data-url="{{ route('back.pages.destroy', [$id]) }}"><i class="fa fa-times"></i></a>
</div>
