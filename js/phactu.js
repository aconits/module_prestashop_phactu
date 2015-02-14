$(function() {
        $('#liste-phactu li .expand').unbind('click').bind('click', function() {
                var el = $(this).parent();
                if (!$(el).hasClass('active')) {
                        $('#liste-phactu li.active').removeClass('active').children('div.short_description').slideUp(800, function() {
                                $(this).prev().slideDown(400);
                                $(el).children('.preview').slideUp(400, function() {
                                        $(el).children('div.short_description').delay(200).slideDown(800, function() { $(el).addClass('active'); });
                                });  
                            
                        });
                }                
        });
});