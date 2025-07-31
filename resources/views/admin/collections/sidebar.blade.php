<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="fs-5">Similar Collections</div>
    </div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            @foreach($similarCollections as $item)
                <li class="d-flex align-items-center mb-4">
                    <div class="badge bg-label-secondary text-body p-2 me-4 rounded">
                        <i class="icon-base fa-regular fa-list-alt icon-md"></i>
                    </div>
                    <div class="d-flex justify-content-between w-100 gap-2">
                        <div>
                            <a href="{{ cms_route($item->type . '.index', [$item->id]) }}"
                               class="fs-6 d-block text-{{ $item->id == $parent->id ? 'primary' : 'black' }}">
                                {{ $item->title }}
                            </a>
                            <small class="text-body">{{ $item->description }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="{{ $item->id == $parent->id ? 'count' : 'count-items-' . $item->id }} badge bg-label-{{ $item->id == $parent->id ? 'primary' : 'gray' }}">
                                {{ number_format($item->{$item->type . '_count'}) }}
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
