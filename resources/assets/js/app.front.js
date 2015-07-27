// Cookie policy message
var cookie = {
    set: function(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            expires = '; expires='+date.toGMTString();
        }
        else expires = '';
        document.cookie = name+'='+value+expires+'; path=/';
    },
    get: function(name){
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },
    remove: function(name) {
        createCookie(name, '', -1);
    },
    showCookieBar: function(){
        var cookieBar = '<div id="cookie_bar" class="container">'+
            '<p>Le site <b>' + site_name + '</b> utilise des cookies afin d\'améliorer votre expérience utilisateur.</p>'+
            '<button id="cookie_acceptation" class="btn btn-success"><i class="fa fa-check-circle"></i> Je comprends</button>'+
            '<button id="cookie_information" class="btn btn-primary"><i class="fa fa-question-circle"></i> En savoir plus</button>'+
            '</div>';
        $('#footer_color_fill').append(cookieBar);
        $('#cookie_bar').hide();
        setTimeout(function(){
            $('#cookie_bar').slideToggle();
        }, 3000);
    },
    submit : function(){
        if(!cookie.get('cookie_acceptation')){
            cookie.showCookieBar();
        }
        $('#cookie_acceptation').click(function(){
            cookie.set('cookie_acceptation', true, 60);
            $('#cookie_bar').slideToggle();
        });
        $('#cookie_information').click(function(){
            $(location).attr('href', url_base+'politique-relative-aux-cookies');
        });
    }
};

// Google map
gmap ={
    active : false,
    resized : false,
    screenOnMap : false,
    url : 'https://maps.googleapis.com/maps/api/js?v=3.exp&',
    analyseScrollPos : function(){
        var scrollPos = $(document).scrollTop().valueOf();
        var pos = $('#map-canvas').offset().top - screen.height;
        if(scrollPos >= pos && gmap.screenOnMap === false || scrollPos < pos && gmap.screenOnMap === true){
            gmap.screenOnMap = !gmap.screenOnMap;
        }
    },
    callScript : function(){
        window.loadGmap = function(){
            gmap.load();
        };
        $.ajax({
            url: gmap.url+'callback=loadGmap',
            dataType: 'script'
        });
    },
    load : function() {
        var draggable = null;
        var latlng = new google.maps.LatLng(47.28000, -1.549000);
        var screenWidth = screen.width;

        // manage drag for large / mobile devices
        if(screenWidth > 767){
            draggable = true;
        }
        else{
            latlng = new google.maps.LatLng(47.28000, -1.549000);
            draggable = false;
        }

        // set map options
        var mapOptions = {
            zoom: 14,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggable: draggable,
            scrollwheel: false,
            styles : [
                {
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#CFCFCF"
                        }
                    ]
                },
                {
                    "elementType": "labels.text",
                    "stylers": [
                        {
                            "color": "#666666"
                        },
                        {
                            "weight": 0.1
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#BFBFBF"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#BFBFBF"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#D4D4D4"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "color": "#D0D0D0"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#AFAFAF"
                        }
                    ]
                },
                { }
            ]
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        //var image = {
            // icon image
            //url: url_img+'site/acid-solutions_contact-picto.png',
            // icon size
            //size: new google.maps.Size(119, 150),
            // image origin (often 0, 0)
            //origin: new google.maps.Point(0,0),
            // image anchor point
            //anchor: new google.maps.Point(20, 70)
        //};
        // marker configuration
        //var marker = new google.maps.Marker({
        //    map: map,
        //    draggable: false,
        //    position: new google.maps.LatLng(47.211807, -1.550832),
        //    title: 'ACID-Solutions',
        //    icon: image
        //});
    },
    scrollTreatment : function() {
        gmap.analyseScrollPos();
        if(gmap.screenOnMap === true && gmap.active === false && screen.width > 767){
            gmap.active = true;
//			console.log('ajax gmap request + load map');
            gmap.callScript();
        }
        if(gmap.screenOnMap === true && gmap.active === true && gmap.resized === true && screen.width > 767){
            gmap.active = true;
            gmap.resized = false;
//			console.log('load when screen resized off view then scroll down on it');
            gmap.load();
        }
    }
};

// When DOM is ready
$(function(){

    // load gmap when scroll on it
    $(window).scroll(gmap.scrollTreatment);

    // gestion du défilement lors du clic sur une ancre
    $('header a[href^="#"], footer a[href^="#"]').on( 'click', function( evt ) {
        evt.preventDefault();
        var id = $(this).attr("href");
        offset = $(id).offset().top;
        $('html, body').animate({
                scrollTop: offset
            },
            'slow',
            'easeInOutQuint'
        );
    });

    // hide bootstrap menu on click
    $(document).on('click',function(){
        if($('.collapse').hasClass('in')){
            $('.collapse').collapse('hide');
        }
    });

    // open new window on social footer link click
    $(".social a").on("click", function(event){
        event.preventDefault();
        window.open(this.href);
    });

    // animated scroll when clicking on an anchor
    $(window).scroll(function () {
        if ($(window).height() + $(window).scrollTop() >= $("#map-canvas").offset().top) {
            $(".menu_tab").removeClass("active");
            $("#contact").addClass("active");
        }
        else{
            $("#onglet_"+page_name).addClass("active");
            $("#onglet_contact").removeClass("active");
        }
    });

    // Cookie acceptation verification
    cookie.submit();
});