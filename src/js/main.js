(function ($, window, undefined) {
    $(function () {
       $('.current_page_sidemenu_item').eq(0)
           .closest('.level-2').addClass('current_page_top_parent')
           .end()
           .closest('.level-3').addClass('current_page_parent')
           .end()
           .closest('.level-1').show();
    });
})(jQuery, window);