$.fn.inView = function() {
    var $this = $(this),
        elementTop = $this.offset().top,
        viewportTop = $(window).scrollTop();
    return elementTop + $this.outerHeight() > viewportTop && elementTop < viewportTop + $(window).height();
};
$.fn.onView = function(handler) {
    $.each(this, function (key, self) {
        var $window = $(window),
            $self = $(self),
            handlerFunction = function () {
                if ($self.inView()) {
                    $window.off('scroll resize', handlerFunction);
                    $self.one('onView', handler);
                    $self.trigger('onView');
                }
            };
        $window.on('scroll resize', handlerFunction);
        handlerFunction();
    });
};