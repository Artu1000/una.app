var slideshow = {
    animate: {
        start: function(){
            // picto
            $('#carousel .slide_content .picto').css({
                '-webkit-transform-origin': 'center center',
                '-moz-transform-origin': 'center center',
                '-ms-transform-origin': 'center center',
                '-o-transform': 'center center',
                'transform-origin': 'center center',

                // rotation 360°
                '-webkit-transform': 'translate(0, 0) rotate(0deg)',
                '-moz-transform': 'translate(0, 0) rotate(0deg)',
                '-ms-transform': 'translate(0, 0) rotate(0deg)',
                '-o-transform': 'translate(0, 0) rotate(0deg)',
                transform: 'translate(0, 0) rotate(0deg)',

                // transition duration
                '-webkit-transition': '1s ease-out',
                '-moz-transition': '1s ease-out',
                '-ms-transition': '1s ease-out',
                '-o-transition': '1s ease-out',
                transition: '1s ease-out',

                // fade in
                '-webkit-animation': 'fadeIn 8s ease-out',
                '-moz-animation': 'fadeIn 8s ease-out',
                '-ms-animation': 'fadeIn 8s ease-out',
                '-o-animation': 'fadeIn 8s ease-out',
                'animation': 'fadeIn 8s ease-out'
            }).promise().done(function(){
                // keep visible
                $(this).css('opacity', 1);
            });

            // title
            $('#carousel .slide_content .title').css({
                // move
                '-webkit-transform': 'translate(0, 0)',
                '-moz-transform': 'translate(0, 0)',
                '-ms-transform': 'translate(0, 0)',
                '-o-transform': 'translate(0, 0)',
                transform: 'translate(0, 0)',

                // transition duration
                '-webkit-transition': '6s ease-out',
                '-moz-transition': '6s ease-out',
                '-ms-transition': '6s ease-out',
                '-o-transition': '6s ease-out',
                transition: '6s ease-out',

                // fade in
                '-webkit-animation': 'fadeIn 6s',
                '-moz-animation': 'fadeIn 6s',
                '-ms-animation': 'fadeIn 6s',
                '-o-animation': 'fadeIn 6s',
                'animation': 'fadeIn 6s'
            }).promise().done(function(){
                // keep visible
                $(this).css('opacity', 1);
            });

            // quote
            setTimeout(function(){
                $('#carousel .slide_content .quote').css({
                    // move
                    '-webkit-transform': 'translate(0, -0.3em)',
                    '-moz-transform': 'translate(0, -0.3em)',
                    '-ms-transform': 'translate(0, -0.3em)',
                    '-o-transform': 'translate(0, -0.3em)',
                    transform: 'translate(0, -0.3em)',

                    // transition duration
                    '-webkit-transition': '5s ease-out',
                    '-moz-transition': '5s ease-out',
                    '-ms-transition': '5s ease-out',
                    '-o-transition': '5s ease-out',
                    transition: '5s ease-out',

                    // fade in
                    '-webkit-animation': 'fadeIn 5s',
                    '-moz-animation': 'fadeIn 5s',
                    '-ms-animation': 'fadeIn 5s',
                    '-o-animation': 'fadeIn 5s',
                    'animation': 'fadeIn 5s'
                }).promise().done(function(){
                    // keep visible
                    $(this).css('opacity', 1);
                });
            }, 1000);
        },
        end: function(){
            // all elements
            $('#carousel .slide_content .picto, #carousel .slide_content .title, #carousel .slide_content .quote').css({
                // fade out
                '-webkit-animation': 'fadeOut 200ms',
                '-moz-animation': 'fadeOut 200ms',
                '-ms-animation': 'fadeOut 200ms',
                '-o-animation': 'fadeOut 200ms',
                'animation': 'fadeOut 200ms'
            }).promise().done(function(){
                // keep hidden
                $('#carousel .slide_content .picto, #carousel .slide_content .title, #carousel .slide_content .quote').css('opacity', 0);

                // cancel picto rotation and move
                $('#carousel .slide_content .picto').css({
                    '-webkit-transform': 'translate(20em, 0) rotate(360deg)',
                    '-moz-transform': 'translate(20em, 0) rotate(360deg)',
                    '-ms-transform': 'translate(20em, 0) rotate(360deg)',
                    '-o-transform': 'translate(20em, 0) rotate(360deg)',
                    transform: 'translate(20em, 0) rotate(360deg)',

                    '-webkit-transition': '200ms linear',
                    '-moz-transition': '200ms linear',
                    '-ms-transition': '200ms linear',
                    '-o-transition': '200ms linear',
                    transition: '200ms linear'
                });

                // cancel title move
                $('#carousel .slide_content .title').css({
                    '-webkit-transform': 'translate(-0.6em, 0)',
                    '-moz-transform': 'translate(-0.6em, 0)',
                    '-ms-transform': 'translate(-0.6em, 0)',
                    '-o-transform': 'translate(-0.6em, 0)',
                    transform: 'translate(-0.6em, 0)',

                    '-webkit-transition': '200ms linear',
                    '-moz-transition': '200ms linear',
                    '-ms-transition': '200ms linear',
                    '-o-transition': '200ms linear',
                    transition: '200ms linear'
                });

                // cancel quote move
                $('#carousel .slide_content .quote').css({
                    '-webkit-transform': 'translate(-0.6em, 0.3em)',
                    '-moz-transform': 'translate(-0.6em, 0.3em)',
                    '-ms-transform': 'translate(-0.6em, 0.3em)',
                    '-o-transform': 'translate(-0.6em, 0.3em)',
                    transform: 'translate(-0.6em, 0.3em)',

                    '-webkit-transition': '200ms linear',
                    '-moz-transition': '200ms linear',
                    '-ms-transition': '200ms linear',
                    '-o-transition': '200ms linear',
                    transition: '200ms linear'
                });
            });
        }
    }
};

$(function() {

    // if there are more than one slide, start glide js
    if(app.slides_count > 1){
        var carousel = $('#carousel').glide({
            type: 'slideshow',
            autoplay: '8000',
            animationDuration: 1200,
            afterInit : function(){
                // treatment after slidehow initiation here
            },
            beforeTransition : function(){
                slideshow.animate.end();
            },
            afterTransition : function(){
                slideshow.animate.start();
            }
        });


    }

});