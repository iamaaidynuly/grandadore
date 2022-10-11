<div class="col-12 col-sm-6 col-lg-4 mb-4">
    <div class="card d-flex flex-column">
        <a href="{{ route('news', ['url' => $item->url]) }}">
            <img class="img-fluid" src="{{ asset('u/news/'.$item->image) }}" alt="{{ $item->title }}" title="{{ $item->title }}">
        </a>
        <div class="new__info">
            <h2 class="news__name">
                <a href="{{ route('news', ['url' => $item->url]) }}">{{ $item->title }}</a>
            </h2>
            <p class="text__information">{{ $item->short }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <p class="date">
                    <a href="{{ route('news', ['url' => $item->url]) }}">{{ $item->created_at->calendar() }}</a>
                </p>
                <div class="d-flex align-items-center views__box">
                    <i class="fas fa-eye views__icon"></i>
                    <p class="m-0 views__count">{{ $item->views_count }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
