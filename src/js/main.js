(function ($, window, undefined) {
    //jQuery goes here

    //Optional: Load Google Fonts Asynchronously with two sample fonts (Roboto Condensed and Lato)
    window.WebFontConfig = {
        google: {families: ['Roboto+Condensed:400,700:latin', 'Lato:400,700:latin']}
    };
    (function () {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();
})(jQuery, window);