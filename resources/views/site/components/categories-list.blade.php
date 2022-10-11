<script>
    function someFunction(e){
        if (screen.width > 992) {
            window.location.href = e.getAttribute("data-location");
        }
    }
</script>


@if(count($categories_home))
    <nav class="nav__mobile mb-5">
        <div style="position: relative;" class="megadropdown">
            <div class="level-1-open">{{ t('Products.catalogue') }}</div>
            <div class="level-1 display-none">
                <div class="level-1-back d-lg-none">
                    <i class="fas fa-chevron-left mr-3"></i>
                    {{ t('Products.catalogue main menu') }}
                </div>
                <div class="web__structure">
                    @foreach($categories_home as $index => $category)
                        @if($category->ItemsCount)
                            <div class="level-2-open">{{ $category->name }}</div>
                            <div class="level-2 display-none d-lg-flex">
                                <div class="level-2-back" onclick="someFunction(this)" style="cursor: pointer" data-location="{{ route('products.category.list', ['url' => $category->url]) }}">
                                    <i class="fas fa-chevron-left mr-3"></i>
                                    {{ $category->name }}
                                </div>
                                @if($category->childrens)

                                    <ul class="mobile__menu__catalog__ul">

                                        @foreach($category->childrens as $row => $child)
                                            @if($row < 5)
                                                <li>
                                                    <a href="{{ route('products.category.list',['url'=>$child->url]) }}">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                        <li>
                                            <a href="{{route('products.category.list',['url'=>$category->url])}}" class="list-all-categories">
                                                {{ t('Products.catalogue all') }}
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <ul class="ul__mobile m-0 p-0 d-flex flex-column">
            @foreach($menu_pages as $page)
                @if($page->static)
                    <li class="li__mobile">
                        <a class="nav-link-mobile" href="{{ route('page', ['url'=>$page->static==$homepage->static?null:$page->url]) }}">
                            {{ $page->title }}
                            @if(($current_page ?? null) === $page->id)
                                <span class="sr-only">(current)</span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

    </nav>

@endif
