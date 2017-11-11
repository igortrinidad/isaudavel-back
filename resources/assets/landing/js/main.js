

$(document).ready(function(){

    wow = new WOW({
        mobile:       false,       // default
      }
    )
    wow.init();


    $('.init-slider').owlCarousel({
        items:1,
        merge:true,
        loop:true,
        video:true,
        smartSpeed: 600
    });

    //Mask watcher
    $.jMaskGlobals.watchDataMask = true;


    accounting.settings = {
        currency: {
            symbol : "R$ ",   // default currency symbol is '$'
            format: "%s%v", // controls output: %s = symbol, %v = value/number (can be object: see below)
            decimal : ",",  // decimal point separator
            thousand: ".",  // thousands separator
            precision : 2   // decimal places
        },
        number: {
            precision: 2,  // default precision on numbers is 0
            thousand: ".",
            decimal : ","
        }
    }

    window.copyToClipboard = function(value) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(value).select();
        document.execCommand("copy");
        $temp.remove();
    }


    window.errorNotify = function(title, message, position) {

        iziToast.show({
            position:  position? position : 'topRight',
            title: title,
            message: message,
            color: '#E14A45',
            titleColor: '#fff',
            messageColor: '#fff',
            iconColor: '#fff',
            progressBarColor: '#fff',
        });
    }

    window.successNotify = function(title, message, position) {

        iziToast.show({
            position: position ? position : 'topRight',
            title: title,
            message: message,
            color: '#00A369',
            titleColor: '#fff',
            messageColor: '#fff',
            iconColor: '#fff',
            progressBarColor: '#fff',
        });
    }

    window.warningNotify = function(title, message, position) {

        iziToast.show({
            position: position? position : 'topRight',
            title: title,
            message: message,
            color: '#FFCC5F',
            titleColor: '#383938',
            messageColor: '#383938',
            iconColor: '#383938',
            progressBarColor: '#383938',
        });
    }

    window.infoNotify = function(title, message, position) {

        iziToast.show({
            position:  position? position : 'topRight',
            title: title,
            message: message,
            color: '#488FEE',
            titleColor: '#fff',
            messageColor: '#fff',
            iconColor: '#fff',
            progressBarColor: '#fff',
        });
    }

});
