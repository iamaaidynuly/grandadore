<div class="container-fluid banners__block d-flex flex-column flex-sm-row justify-content-between">
    @if($banner->left)
        <a @if($banner->left_link) target="_blank" @endif
        class="mb-3 mb-sm-0 mr-sm-2 mr-lg-3"
           href="@if($banner->left_link) {{ $banner->left_link }} @else javascript:void(0) @endif">
            <img class="img-fluid" src="{{ asset('u/banners/'.$banner->left) }}" alt="" title="">
        </a>
    @endif

    @if($banner->right)
        <a @if($banner->right_link) target="_blank" @endif
        class="mb-3 mb-sm-0 mr-sm-2 mr-lg-3"
           href="@if($banner->right_link) {{ $banner->right_link }} @else javascript:void(0) @endif">
            <img class="img-fluid" src="{{ asset('u/banners/'.$banner->right) }}" alt="" title="">
        </a>
    @endif
</div>
