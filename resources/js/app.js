$.noConflict();
(function($){
    window.appJS = window.appJS || {};

    appJS.main = function() {
        var mobileMenuToggle = function(event) {
            if($('body').hasClass('menu-open')) {
                $('body').removeClass('menu-open');
            } else {
                $('body').addClass('menu-open');
            }   
        },

        pjaxCallback = function() {
            if($('body').hasClass('menu-open')) {
                $('body').removeClass('menu-open');
            }
        };

        return {
            mobileMenuToggle: mobileMenuToggle,
            pjaxCallback: pjaxCallback
        }
    }();

    $(document).pjax('.pjax-link', '#pjax-container');
    $(document).on('pjax:complete', appJS.main.pjaxCallback);
    
    $(document).ready(function() {
        $('.menu-toggle').click(appJS.main.mobileMenuToggle);
    });
})(jQuery);