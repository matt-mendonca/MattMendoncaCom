$.noConflict();
(function($){
    window.appJS = window.appJS || {};

    appJS.main = function() {
        var mobileMenuToggle = function(event) {
            $('body').toggleClass('menu-open');
        },

        pjaxBeforeSend = function() {
            $('body').removeClass('loaded');
        }

        pjaxCallback = function() {
            $('body').removeClass('menu-open');
            
            $('body').addClass('loaded');
        };

        return {
            mobileMenuToggle: mobileMenuToggle,
            pjaxBeforeSend: pjaxBeforeSend,
            pjaxCallback: pjaxCallback
        }
    }();

    $.pjax.defaults.timeout = 1000;
    $(document).pjax('.pjax-link', '#pjax-container');
    $(document).on('pjax:beforeSend', appJS.main.pjaxBeforeSend);
    $(document).on('pjax:success', appJS.main.pjaxCallback);
    
    $(document).ready(function() {
        $('body').addClass('loaded');

        $('.menu-toggle').click(appJS.main.mobileMenuToggle);
    });
})(jQuery);