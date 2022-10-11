var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
function onYouTubeIframeAPIReady() {
    window.videos = [];
    window.videoId = 1;
    addVideoEvents();
    window.playEvent = function(id) {
        for (var i in videos) {
            if (videos[i].getIframe().id !== id && videos[i].getPlayerState() === 1) {
                videos[i].pauseVideo();
            }
        }
    }
}
var addVideoEvents = function(){
    $('.video-player').on('click', function(){
        var self=$(this);
        self.find('.video-data').remove();
        var thisId = 'youtube-video-'+(videoId++);
        videos.push(new YT.Player($('<div id="'+thisId+'"></div>').insertAfter(self)[0], {
            videoId: self.data('id'),
            playerVars: { 'autoplay': 1, 'rel': 0 },
            width:false,
            height:false,
            events: {
                'onStateChange': function (event) {
                    if (event.data === 1) {
                        playEvent(thisId);
                    }
                },
                'onReady': function() {
                    self.remove();
                }
            }
        }));
    });
    $('.video-data').addClass('loaded');
};
