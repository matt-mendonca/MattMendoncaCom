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

        pjaxBeforeSend = function() {
            $('body').removeClass('loaded');
        }

        pjaxCallback = function() {
            if($('body').hasClass('menu-open')) {
                $('body').removeClass('menu-open');
            }
            
            $('body').addClass('loaded');
        };

        return {
            mobileMenuToggle: mobileMenuToggle,
            pjaxBeforeSend: pjaxBeforeSend,
            pjaxCallback: pjaxCallback
        }
    }();

    $(document).pjax('.pjax-link', '#pjax-container');
    $(document).on('pjax:beforeSend', appJS.main.pjaxBeforeSend);
    $(document).on('pjax:complete', appJS.main.pjaxCallback);
    
    $(document).ready(function() {
        $('body').addClass('loaded');

        $('.menu-toggle').click(appJS.main.mobileMenuToggle);
    });
})(jQuery);