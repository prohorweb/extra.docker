jQuery(function ($) {
    const video = document.querySelector('.js-pv-video');
    const loader = document.querySelector('.js-pv-loader');

    /*if (typeof video !== 'undefined') {
        video.addEventListener('loadeddata', () => {
            loader.classList.remove('is-active');
            video.classList.remove('is-hide');
            console.log(`Video size: ${video.videoWidth} x ${video.videoHeight}`);
            $(".js-pv-video").prop("muted", true);
            $('.js-pv').addClass('is-mute');
            $(".js-pv-video")[0].play();
        });
    }*/

    $(".js-pv-zoom").click(function () {
        $(".js-pv").removeClass('is-minimize');

        $(".js-pv-video").prop("muted", false);
        $('.js-pv').removeClass('is-mute');
    });

    $(".js-pv-minimize").click(function () {
        $(".js-pv").addClass('is-minimize');
        $('.js-pv').addClass('is-mute');
        $(".js-pv-video").prop("muted", true);
    });




    // Обработка нажатия кнопки "Play"
    $(".js-pv-play").click(function () {
        $('.js-pv').removeClass('is-pause');
        $(".js-pv-video")[0].play();
    });

    // Обработка нажатия кнопки "Pause"
    $(".js-pv-pause").click(function () {
        $('.js-pv').addClass('is-pause');
        $(".js-pv-video")[0].pause();
    });

    // Обработка нажатия кнопки "Stop"
    $(".js-pv-stop").click(function () {
        $('.js-pv').addClass('is-pause');
        $(".js-pv-video")[0].pause();
        $(".js-pv-video")[0].currentTime = 0;
    });

    $(".js-pv-mute").click(function () {
        $('.js-pv').removeClass('is-mute');
        $(".js-pv-video").prop("muted", false);
    });

    $(".js-pv-volume").click(function () {
        $('.js-pv').addClass('is-mute');
        $(".js-pv-video").prop("muted", true);
    });
});



// for browser Firefox
jQuery(".js-pv-close").click(function () {
    jQuery(".js-pv").removeClass('is-show');
    Cookies.set('popup-video', '1', {expires: 1});
});

jQuery(document).on('visibilitychange', function() {
    if (document.visibilityState == 'hidden') {
        Cookies.remove('popup-video', { path: '' });
    }
});

jQuery(window).on( "unload", function() {
    Cookies.remove('popup-video', { path: '' });
});

jQuery(window).on('pagehide', function() {
    Cookies.remove('popup-video', { path: '' });
});