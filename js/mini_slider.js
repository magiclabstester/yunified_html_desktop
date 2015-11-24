/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function changeHeight() {
    var wHeight = $(window).height();
    var dHeight = $('#page-bg').height();
    var spHeight = $('.sp:visible').height();
    if($('#button-previous').length >0) spHeight += 150;
    $('#content').css('height', spHeight);
	
    if (dHeight < wHeight){
        $('#page-bg').height(wHeight - 40);
		
		//alert('ddd');
	}
    else {
        $('#page-bg').height('auto');
        var wHeight = $(window).height();
        var dHeight = $('#page-bg').height();
        if (dHeight < wHeight)$('#page-bg').height(wHeight - 40);
    }
	var vHeight = $('#vertical').height() + 420;
	if (vHeight< wHeight)$('#vertical').css('margin-top', (wHeight - vHeight)/2 + 'px');
	else $('#vertical').css('margin-top', 0);
}
;

$(document).ready(function () {
    changeHeight();
    $(window).resize(function () {
        changeHeight();
    });
    $('.sp').first().addClass('active');
    $('.sp').hide();
    $('.active').show();

    $('#button-next').click(function () {

        $('.active').removeClass('active').addClass('oldActive');
        if ($('.oldActive').is(':last-child')) {
            $('.sp').first().addClass('active');
        } else {
            $('.oldActive').next().addClass('active');
        }
        $('.oldActive').removeClass('oldActive');
        $('.sp').fadeOut();
        $('.active').fadeIn();
    });

    $('#button-previous').click(function () {
        $('.active').removeClass('active').addClass('oldActive');
        if ($('.oldActive').is(':first-child')) {
            $('.sp').last().addClass('active');
        } else {
            $('.oldActive').prev().addClass('active');
        }
        $('.oldActive').removeClass('oldActive');
        $('.sp').fadeOut();
        $('.active').fadeIn();
    });
});
