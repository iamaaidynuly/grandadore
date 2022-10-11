<footer>
    <div class="container-fluid">
        <div class="row d-lg-flex justify-content-between">
            @if(count($in_footer))
                <div class="col-12 col-sm-6 col-lg-auto mb-4 mb-lg-0 footer__section">
                    <span class="footer__section-name">{{ t('footer.info') }}</span>
                    <ul>
                        @foreach($in_footer as $page)
                            @if($page->static)
                                <li>
                                    <a href="{{ url($page->url) }}" class="footer-link">
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-12 col-sm-6 col-lg-auto mb-4 mb-lg-0 footer__section">
                    <span class="footer__section-name">{{ t('footer.buyers') }}</span>
                    <ul>
                        @foreach($in_footer as $page)
                            @if(!$page->static)
                                <li>
                                    <a href="{{ url($page->url) }}" class="footer-link">
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(count($footer_categories))
                <div class="col-12 col-sm-6 col-lg-auto mb-4 mb-lg-0 footer__section">
                    <span class="footer__section-name">{{ t('footer.categories') }}</span>
                    <ul>
                        @foreach($footer_categories as $category)
                            <li>
                                <a class="footer-link" href="{{ route('products.category.list', ['url' => $category->url]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($infos->socials)
                <div class="col-12 col-sm-6 col-lg-auto mb-4 mb-lg-0 footer__section">
                    <span class="footer__section-name">{{ t('footer.social') }}</span>
                    <ul class="social_icon">
                        @foreach($infos->socials as $row=>$socials)
                            @if($socials->icon)
                                <li>
                                    <a @if($socials->url) target="_blank" @endif
                                       class="footer-link" rel="nofollow noopener noreferrer"
                                       href="{{ $socials->url }}"
                                       title="{{ $socials->title }}">
                                        <img class="footer-soc-icon" src="{{ asset('u/banners/'.$socials->icon) }}" alt="{{ $socials->title }}" title="{{ $socials->title }}">
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-12 col-sm-6 col-lg-auto mb-4 footer__section">
                <span class="footer__section-name">{{ t('footer.our contacts') }}</span>
                <ul>
                    @if($infos->address)
                        <li class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <a target="_blank"
                               href="/Google-map-href"
                               class="footer-link">
                                {{ $infos->address[0]->text }}
                            </a>
                        </li>
                    @endif

                    @if($infos->contacts)
                        @foreach($infos->contacts as $contact)
                            @if($contact->phone)
                                <li class="d-flex align-items-cneter">
                                    <i class="fas fa-phone-volume mr-2"></i>
                                    <a target="_blank" href="tel:+{{ $contact->phone }}" class="footer-link">
                                        {{ $contact->phone }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif

                    @if($infos->data[0]->contact_email)
                        <li class="d-flex align-items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <a target="_blank" href="mailto:{{ $infos->data[0]->contact_email }}"  class="footer-link">
                                {{ $infos->data[0]->contact_email }}
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

        <div class="shop-info d-flex flex-column">
            <span class="shop-info-span d-none d-lg-inline">
                {{ t('footer.description desktop') }}
            </span>
            <span class="shop-info-span d-lg-none">
                {{ t('footer.description mobile') }}
            </span>
        </div>
    </div>
</footer>
