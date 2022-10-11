<div class="container">
    <div class="title-design">
        <div class="title-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                <g id="Group_5241" data-name="Group 5241" transform="translate(-306 -754)">
                    <rect id="Rectangle_1395" data-name="Rectangle 1395" width="6" height="6"
                          transform="translate(306 754)" fill="#212121" opacity="0.4"/>
                    <rect id="Rectangle_1396" data-name="Rectangle 1396" width="6" height="6"
                          transform="translate(316 754)" fill="#212121"/>
                    <rect id="Rectangle_1397" data-name="Rectangle 1397" width="6" height="6"
                          transform="translate(306 764)" fill="#212121" opacity="0.63"/>
                    <rect id="Rectangle_1398" data-name="Rectangle 1398" width="6" height="6"
                          transform="translate(316 764)" fill="#212121" opacity="0.8"/>
                </g>
            </svg>
            <span>{{ $title ?? '' }}</span>
        </div>
        @if(!empty($url))
            <a href="{{ $url }}">
                <svg class="arrow-svg" id="Group_4520" data-name="Group 4520" xmlns="http://www.w3.org/2000/svg"
                     width="19.885" height="9.632" viewBox="0 0 19.885 9.632">
                    <path id="Path_11199" data-name="Path 11199"
                          d="M19.108,136.039H2.658l2.725-2.712a.777.777,0,0,0-1.1-1.1L.228,136.265h0a.777.777,0,0,0,0,1.1h0l4.059,4.039a.777.777,0,0,0,1.1-1.1l-2.725-2.712h16.45a.777.777,0,0,0,0-1.554Z"
                          transform="translate(0 -132)" fill="#212121"/>
                </svg>
            </a>
        @endif
    </div>
</div>
