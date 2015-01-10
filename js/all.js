/* show ul.children */
jQuery(function($) {
	function showUl(menu) {
		menu.find('li > ul').hide();
		menu.find('li > ul').each(function() {
			$(this).parent('li').hover(function() {
				$(this).children('ul:not(:animated)').animate({opacity: 'show', height: 'show'}, 'slow');
			}, function() {
				$(this).children('ul').animate({opacity: 'hide', height: 'hide'}, '100');
			});
		});
	}
	showUl($('#access'));
	showUl($('.widget-area > ul > li'));
});

/* hide or expand comments */
jQuery(function($) {
	$('.commentlist > li > .comment-wrap').find('.reply').after('<div class="hide">Hide</div>');
	$('.hide').toggle(function() {
		$(this).siblings('.comment-body').slideUp('800').end()
			.parents('.comment-wrap').siblings().slideUp('800');
		$(this).removeClass().addClass('expand');
	}, function() {
		$(this).parents('li').find(':hidden').slideDown('800');
		$(this).removeClass().addClass('hide');
	});
});

/* odd background for related posts */
jQuery(document).ready(function($) {
	$('.related_post').find('li').filter(':odd').addClass('alt');
});

/* search label */
jQuery(function($) {
	$('#s').attr('value', 'Input keywords to search');
	$('#s').focus(function() {
		if ($(this).val() == 'Input keywords to search') {
			$(this).val('')
		}
	}).blur(function() {
		if ($(this).val() == '') {
			$(this).val('Input keywords to search')
		}
	});
});

/* widgets */
jQuery(function($) {
	$('.widget-area').find('select').wrap('<div class="select"></div>');
});

/* wave background of img in hyper link */
jQuery(function($) {
    $('.entry-content a img').parent('a').css({'background': 'none', 'padding': '0'});
});

/* hide author info */
jQuery(function($) {
    if ($('#author').val()) {
        var info = $('#author_info');
        var author = $('#author').val();
        info.hide();
        $('#author').css('fontWeight', 'bold');
        info.before('<div id="welcome_back">Welcome Back '+author+'. <span id="edit_info" style="cursor: pointer; color: #BF514C;">Edit your info.</span></div>');
        var editInfo = $('#edit_info');
        editInfo.click(function() {
            info.animate({opacity: 'toggle'}, 'slow');
            if (editInfo.text() == 'Edit your info.') {
                $(this).text('Close.');
            } else {
                $(this).text('Edit your info.');
            }
        });
    }
});

/* Reply with @ somebody */
jQuery(function($) {
	$('.inner-wrap .reply').click(function() {
		var commentid = $(this).parent().parent().attr('id');
		var name = $(this).parent().find('.comment-author .fn').text();
		$('#comment').attr('value','<a href="#'+commentid+'">@'+name+'</a>: ').focus();
	});
	$('#cancel-comment-reply-link').click(function() {
		$('#comment').attr('value','');
	});
});

/* submit comment with Ctrl+Enter */
window.onload = function() {
	if (!document.getElementById) return false;
	if (!document.getElementById("comment") || !document.getElementById("submit")) return false;
	document.getElementById("comment").onkeydown = function (moz_ev) {
		var ev = null;
		if (window.event){
			ev = window.event;
		}
		else{
			ev = moz_ev;
		}
		if (ev != null && ev.ctrlKey && ev.keyCode == 13) {
			document.getElementById("submit").click();
		}
	}
};