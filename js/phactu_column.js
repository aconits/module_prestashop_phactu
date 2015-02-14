$(function() {
    if (typeof phactu_speed !== 'undefined' && typeof phactu_execution_time !== 'undefined') {
        var phactu_li_left = $('#liste-left-phactu li');
        var phactu_nb_li = phactu_li_left.length;
        var phactu_max_height = 0;
        var phactu_sto;
        var phactu_current_index = 0;
        var phactu_new_index = 1;
        var phactu_stop = false;
        initBlockLeft();
    }

    function initBlockLeft() {
        for (var i = 0; i < phactu_nb_li; i++) {
            var ow = $(phactu_li_left[i]).outerHeight();
            if (ow > phactu_max_height)
                phactu_max_height = ow;
        }

        $('#liste-left-phactu').css('height', phactu_max_height+'px');
        phactu_max_height += 20;
        $('#liste-left-phactu li:not(:first-child)').css('top', phactu_max_height+'px').css('display', 'block');
        if (phactu_nb_li > 1) {
            startPhActuSlide(0);
            $('#liste-left-phactu').hover(function() {
                phactu_stop = true;
                clearTimeout(phactu_sto);
            }, function() {
                phactu_stop = false;
                startPhActuSlide(phactu_current_index);
            });
        }
    }

    function startPhActuSlide(index) {
        clearTimeout(phactu_sto);
        phactu_sto = setTimeout(function() {
            phactu_new_index = (index < phactu_nb_li-1 ? index+1 : 0);

            $(phactu_li_left[index]).animate({
                top: '-='+phactu_max_height+'px'
            }, phactu_execution_time);

            $(phactu_li_left[phactu_new_index]).animate({
                top: '-='+phactu_max_height+'px'
            }, phactu_execution_time, function() {
                $(phactu_li_left[index]).css('top', phactu_max_height+'px');
                phactu_current_index = phactu_new_index;
                if (!phactu_stop)
                    startPhActuSlide(phactu_new_index);
            });
        }, phactu_speed);
    }
});