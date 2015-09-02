// get screen width
var screen_width = $(window).width();

// cookie policy message
var cookie = {
    set: function (name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toGMTString();
        }
        else expires = '';
        document.cookie = name + '=' + value + expires + '; path=/';
    },
    get: function (name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },
    remove: function (name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    },
    showCookieBar: function () {
        var cookieBar = '<div id="cookie_bar" class="container">' +
            '<p>Le site <b>' + site_name + '</b> utilise des cookies afin d\'améliorer votre expérience utilisateur.</p>' +
            '<button id="cookie_acceptation" class="btn btn-success"><i class="fa fa-check-circle"></i> Je comprends</button>' +
            '<button id="cookie_information" class="btn btn-primary"><i class="fa fa-question-circle"></i> En savoir plus</button>' +
            '</div>';
        $('#footer_color_fill').append(cookieBar);
        $('#cookie_bar').hide();
        setTimeout(function () {
            $('#cookie_bar').slideToggle();
        }, 3000);
    },
    submit: function () {
        if (!cookie.get('cookie_acceptation')) {
            cookie.showCookieBar();
        }
        $('#cookie_acceptation').click(function () {
            cookie.set('cookie_acceptation', true, 60);
            $('#cookie_bar').slideToggle();
        });
        $('#cookie_information').click(function () {
            $(location).attr('href', url_base + 'politique-relative-aux-cookies');
        });
    }
};

// Google map
var gmap = {
    active: false,
    screenOnMap: false,
    url: 'https://maps.googleapis.com/maps/api/js?v=3.exp&',
    analyseScrollPos: function () {
        var scrollPos = $(document).scrollTop().valueOf();
        var pos = $('#map-canvas').offset().top - screen.height;
        if (scrollPos >= pos && gmap.screenOnMap === false || scrollPos < pos && gmap.screenOnMap === true) {
            gmap.screenOnMap = !gmap.screenOnMap;
        }
    },
    callScript: function () {
        window.loadGmap = function () {
            gmap.load();
        };
        $.ajax({
            url: gmap.url + 'callback=loadGmap',
            dataType: 'script'
        });
    },
    load: function () {
        var draggable = null;
        var latlng = new google.maps.LatLng(47.236516, -1.551113);

        // manage drag for large / mobile devices
        if (screen_width > 767) {
            draggable = true;
        }
        else {
            draggable = false;
        }

        // set map options
        var mapOptions = {
            zoom: 14,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggable: draggable,
            scrollwheel: false
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        // marker configuration
        var marker = new google.maps.Marker({
            draggable: false,
            position: latlng,
            map: map,
            title: site_name
        });

    },
    scrollTreatment: function () {
        gmap.analyseScrollPos();
        if (gmap.screenOnMap === true && gmap.active === false) {
            gmap.active = true;
            gmap.callScript();
        }
    },
};

var responsiveBackgroundImage = {
    responsive_width: null,
    setImageSize: function () {
        $('.background_responsive_img').each(function (index, element) {
            // we get the url of the background-image
            var bg_img_url = $(this).attr('data-background-image');
            // we remove the extension (has to be jpg)
            bg_img_url = bg_img_url.substr(0, bg_img_url.length - 4);
            // we add the responsive size
            $(this).css('background-image', 'url(' + bg_img_url + '_' + responsiveBackgroundImage.responsive_width + '.jpg)');
        });
    },
    process: function () {
        // we get the responsive size of the screen
        switch (true) {
            case screen_width >= 1921 :
                responsiveBackgroundImage.responsive_width = 2560;
                break;
            case screen_width >= 1200 && screen_width <= 1920:
                responsiveBackgroundImage.responsive_width = 1920;
                break;
            case screen_width >= 992 && screen_width <= 1199:
                responsiveBackgroundImage.responsive_width = 1199;
                break;
            case screen_width >= 768 && screen_width <= 991:
                responsiveBackgroundImage.responsive_width = 991;
                break;
            case screen_width <= 767:
                responsiveBackgroundImage.responsive_width = 767;
                break;
        }
        // we load the background image
        responsiveBackgroundImage.setImageSize();
    }
};

var anchor = {
    animate : function(target){
        $('html,body').animate({
            scrollTop: target.offset().top
        }, 1000);
        return false;
    },
    analyseCookies: function(){

        // we get the anchor cookie
        var str_anchor = cookie.get('anchor');

        // if it exists
        if(str_anchor){

            // we remove it
            cookie.remove('anchor');

            // we create a jquery object of the node element
            str_anchor = $('#'+str_anchor);

            // if the jquery node exists
            if (str_anchor.length) {

                // we trigger the animation after a moment
                setTimeout(function () {
                    anchor.animate(str_anchor);
                }, 500);
            }
        }
    },
    listen: function(){
        $('a[href*=#]:not([href=#])').click(function(event){

            // we prevent the redirection
            event.preventDefault();

            // we get the targetted url and the clicked anchor
            var target_url = $(this).attr('href');
            var splitted_target_url = target_url.split('#');

            // if the url of the link contain a hash
            if (splitted_target_url[1]) {
                console.log('click on link with anchor');

                // we set a cookie with the anchor
                cookie.set('anchor', splitted_target_url[1]);

                // if the targetted url is not the page where we are, we make a redirection
                if (location.href != splitted_target_url[0]+'/') {

                    // we load the new url
                    location.href = splitted_target_url[0];
                } else {

                    // we trigger the scroll animation
                    anchor.animate($('#'+splitted_target_url[1]));
                }
            } else {

                // we force the removal of the anchor cookie
                cookie.remove('anchor');

                // we load the url normally
                location.href = splitted_target_url[0];
            }
        });
    },
    detection: function(){
        anchor.analyseCookies();
        anchor.listen();
    }
};

// When DOM is ready
$(function () {

    // load image depending on the screen size
    responsiveBackgroundImage.process();

    // load gmap when scroll on it
    $(window).scroll(gmap.scrollTreatment);

    // we launch the anchor treatment
    anchor.detection();

    // hide bootstrap menu on click
    $(document).on('click', function () {
        if ($('.collapse').hasClass('in')) {
            $('.collapse').collapse('hide');
        }
    });

    // open new window when clicking on the link
    $(".new_window").on("click", function (event) {
        event.preventDefault();
        window.open(this.href);
    });

    // Cookie acceptation verification
    cookie.submit();
});