jQuery.noConflict();
jQuery(function ($) {

	if (document.getElementsByClassName('slideshow-wrapper__video').length > 0) {
		//document.getElementsByClassName('slideshow-wrapper__video').play();
	}

	var $window = $(window);
	var ww = $window.width();
	var wh = $window.height();

	if ($('#timer').length) {
		var clock;
		var currentDate = Math.round(new Date() / 1000);

		clock = $('#timer').FlipClock({
			clockFace: 'HourlyCounter',
			autoStart: true,
			language: 'ru',
			callbacks: {
				start: function () {
					//var time = clock.getTime().time;
					//if (time % 60 == 0) {
					$('#popup-timer').addClass('active');
					//}
				},
				stop: function () {
					$('#popup-timer').removeClass('active');
				}
			}
		});

		var counter = Math.round((Date.now() + $('#popup-timer').data('time')) / 1000) - currentDate;

		clock.setTime(counter);
		clock.setCountdown(true);
		clock.start();
	}

	$(".chat-24__item").hover(function () {
		var title = $(this).data("title");
		$(this).parents(".chat-24").find(".chat-24__text").text(title);
	});

	$(".js-chat-show").click(function () {
		$(this).addClass("hidden");
		$(this).parent().children('.chat-24__content').addClass("active");
		return false;
	});

	$(".js-chat-hide").click(function () {
		$(this).parents(".chat-24").children(".js-chat-show").removeClass("hidden");
		$(this).parents(".chat-24").children('.chat-24__content').removeClass("active");
		return false;
	});

	if ($(".mod-phone-line").length) {
		var sc = $(".mod-phone-line").offset().top;
	}
	$(document).scroll(function () {
		if ($("body, html").scrollTop() >= sc) {
			$(".mod-phone-line").addClass("fixed");
		} else {
			$(".mod-phone-line").removeClass("fixed");
		}
	});

	$(".time-box__link").click(function () {
		var id = $(this).data("id");
		var t = $(this).offset().top;
		var l = $(this).offset().left;

		$(id).fadeIn(300).children(".time-box-hint__wrap").css("top", t).css("left", l).css("right", "auto");

		var w = $(id).children(".time-box-hint__wrap").outerWidth();
		var fw = (parseInt(l) + parseInt(w));

		console.log(fw + " - " + ww);

		if (parseInt(fw) > parseInt(ww)) {
			var r = parseInt(ww) - (parseInt(l) + parseInt($(this).outerWidth()));
			$(id).children(".time-box-hint__wrap").css("left", "auto").css("right", r);
		}

		return false;
	});

	$(".time-box-hint__close, .time-box-hint__overflow").click(function () {
		$(this).parents(".time-box-hint").fadeOut(300);
		return false;
	});

	// input file

	var wrapper = $(".upload-box"),
		inp = wrapper.find("input"),
		btn = wrapper.find("a"),
		lbl = wrapper.find(".upload-box__result");
	btn.focus(function () {
		inp.focus()
	});
	// Crutches for the :focus style:
	inp.focus(function () {
		wrapper.addClass("focus");
	}).blur(function () {
		wrapper.removeClass("focus");
	});

	btn.add(lbl).click(function () {
		inp.click();
	});

	var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;

	inp.change(function () {
		var file_name;
		if (file_api && inp[0].files[0])
			file_name = inp[0].files[0].name;
		else
			file_name = inp.val().replace("C:\\fakepath\\", '');

		if (!file_name.length)
			return;

		if (lbl.is(":visible")) {
			lbl.text(file_name);
			//btn.text( "Выбрать" );
		} else
			btn.text(file_name);
	}).change();

	// input file end

	if ($(".fancybox").length) {
		$(".fancybox").fancybox({
			// Options will go here
		});
	}

	if ($(".slick").length) {
		$(".slick").slick();
	}

	$(".scroll-to").click(function () {
		var href = $(this).attr("href");
		$('html, body').animate({ scrollTop: $(href).offset().top }, 400);
		return false;
	});

	$(".popup-to").click(function () {
		var href = $(this).attr("href");
		$(".popup-wrap").removeClass("active");
		$(href).addClass("active");
		$("body").addClass("overflow");

		return false;
	});

	var foo = function () {
		// Code to handle some kind of event
	};
	$("#club-popup .popup-wrap__overflow").click(function () {
		$(this).unbind("click", foo);
		return false;
	});

	$(document).on("click", ".popup-wrap__overflow, .close", function () {
		$(this).parents('.popup-wrap').removeClass("active");
		$("body").removeClass("overflow");

		if ($(this).parents('#popup-timer').length) {
			Cookies.set('popup-timer', '1', { expires: 1 / 12 });
		}

		if ($(this).parents('#bonus-popup, #bonus-popup-2').length) {
			Cookies.set('bonus-popup', '1', { expires: 1 / 12 });
		}

		return false;
	});

	$(".tab-nav a").click(function () {
		var href = $(this).attr("href");
		$(".tab-nav__item, .tab-box").removeClass("active");
		$(this).parent().addClass("active");
		$(href).addClass("active");
		return false;
	});

	$(".show-tab").click(function () {
		var href = $(this).attr("href");
		var nav_id = $(this).data("navid");
		$(".tab-nav__item, .tab-box").removeClass("active");
		$(nav_id).addClass("active");
		$(href).addClass("active");
		return false;
	});

	$(".show-box").click(function () {
		var addclass = $(this).data("class");

		if ($(this).data("show")) {
			var href = $(this).data("show");
		} else {
			var href = $(this).attr("href");
		}

		var hide = $(this).data("hide");

		if ($(this).data("toggle") == true && $(this).hasClass(addclass) == true) {
			$(hide).addClass("show");
			$(this).removeClass(addclass);
			$(href).removeClass("show");
		} else {
			$(hide).removeClass("show");

			$(href).addClass("show");

			$(this).parent().parent().parent().find(".show-box").removeClass(addclass);
			$(this).addClass(addclass);
		}

		return false;
	});

	$(".show-menu").click(function () {
		//$(this).toggleClass("active");
		$(".hide-menu").slideToggle(300);
		return false;
	});

	// hide menu accardion
	$(".mob-menu__item.parent").each(function () {
		$('<i class="show-sub"></i>').appendTo($(this));
	});
	$(".mob-menu__sub").each(function () {
		$('<a href="#" class="back-menu"></a>').appendTo($(this));
	});

	$("body").on("click", ".show-sub, .show-sub2", function () {
		var submenu = $(this).parent().find(".mob-menu__sub").clone();
		$(submenu).appendTo($(".hide-menu__menu"));
		$(this).parents(".mob-menu__sub, .mob-menu").removeClass("show-box").addClass("hide-box");
		$(submenu).addClass("show-box");
		return false;
	});

	$("body").on("click", ".back-menu", function () {
		$(this).parents(".mob-menu__sub").removeClass("show-box").addClass("hide-box").prev().removeClass("hide-box").addClass("show-box");
		var th = $(this).parents(".mob-menu__sub");
		setTimeout(function () {
			$(th).remove();
		}, 200);
		return false;
	});
	// hide menu accardion end

	$window.resize(function () {
		if ($window.width() != ww) {
			ww = $window.width();
			wh = $window.height();

			//slideshowinit();
		}
	});

//Calltouch requests
jQuery(document).on('click', 'form#popup-timer [type="submit"]', function() {
    var m = jQuery(this).closest('form');
    var fio = m.find('input[name="name"]').val();
    var phone = m.find('input[name="tel"]').val();
    var ct_site_id = '';
	if (/piter.extrasport.ru/.test(document.location.href)) {
		ct_site_id='12407';
	} else if (/de-vision.ru/.test(document.location.href)) {
		ct_site_id='12408';
	}else if (/iyun.extrasport.ru/.test(document.location.href)) {
		ct_site_id='12482';
	} else if (/polyus.extrasport.ru/.test(document.location.href)) {
		ct_site_id='12483';
	} else if (/matros.extrasport.ru/.test(document.location.href)) {
		ct_site_id='39350';
	}

	var sub = 'Заявка c всплывающей формы popup ' + location.hostname;
	
    var ct_data = {
        fio: fio,
        phoneNumber: phone,
        subject: sub,
		requestUrl: location.href,
        sessionId: window.call_value
    };
	console.log(ct_data);
    if (!!fio && !!phone && ct_site_id != '' && window.ct_snd_flag != 1){
		window.ct_snd_flag = 1; setTimeout(function(){ window.ct_snd_flag = 0; }, 20000);
        jQuery.ajax({
            url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/'+ct_site_id+'/register/',
            dataType: 'json', type: 'POST', data: ct_data, async: false
        });
    }
});

});



