@if(!empty($breadcrumbs))
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0 m-0 mt-4">
                <li class="breadcrumb-item d-flex align-items-center">
                    <a href="{{ url('/') }}" class="breadcrumb-link">{{ t('app.main') }}</a>
                </li>
                @foreach($breadcrumbs as $row => $breadcrumb)
                    @if(!empty($breadcrumb['url']))
                        <li class="breadcrumb-item d-flex align-items-center">
{{--                                                        @dd($breadcrumb['url'])--}}
                            <a test href="{{ $breadcrumb['url'] }}"
                               class="breadcrumb-link">{!! $breadcrumb['title'] !!}</a>
                        </li>
                    @else

                        @php $val = \App\Models\Category::where('name->ru', $breadcrumb['title'])->first(); @endphp
                        @if(isset($val->url))
                            <li class="breadcrumb-item">
{{--                                @dd($val->url)--}}
                                <a href="{{ route('products.category.list', ['url' => $val->url])}}"
                                   class="breadcrumb-item breadcrumb-link d-flex align-items-center"
                                   aria-current="page">{!! $breadcrumb['title'] !!}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item d-flex align-items-center breadcrumb-link"
                                aria-current="page">{!! $breadcrumb['title'] !!}</li>
                        @endif
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
@endif
