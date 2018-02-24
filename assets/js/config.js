jQuery('form#xubio-woo-credentials').submit(function (e) {
    e.preventDefault();
    jQuery.ajax({
        data : jQuery(this).serialize() + '&action=xubiowoo',
        type: 'post',
        url: ajaxurl,
        beforeSend : function(){
            jQuery('div.overlay-xubio-woo').show();
            jQuery('div.overlay-xubio-woo img').show();
            jQuery('div.overlay-xubio-woo div.message strong').html(xubiowoo.loadcredentials);
        },
        success: function(r) {
            var obj = JSON.parse(r);
            if (obj.status){
                jQuery('div.overlay-xubio-woo div.message strong').html(xubiowoo.loadSuccessCredentials);
            }else{
                jQuery('div.overlay-xubio-woo div.message strong').html(xubiowoo.loadFailCredentials);
            }
            jQuery('div.overlay-xubio-woo img').hide();
            jQuery('div.overlay-content-xubio-woo .close').show();
        },
        error: function(x, s, e) {
            console.log(x.responseText + s.status + e.error);
        }
    });
});
jQuery('div.overlay-content-xubio-woo .close').click(function (){
    jQuery('div.overlay-xubio-woo').hide();
});

jQuery('form#xublio-client select[name=xubio-clients]').on('change', function() {
    if (this.value == 'getclients'){
        jQuery.ajax({
            data: 'clients=get&action=xubiowoo',
            type: 'post',
            url: ajaxurl,
            beforeSend: function(){
                jQuery('div.overlay-xubio-woo').show();
                jQuery('div.overlay-xubio-woo img').show();
                jQuery('div.overlay-xubio-woo div.message strong').html(xubiowoo.loadGetClients);
            },
            success: function(r){
                if (r){
                    var obj = JSON.parse(r);
                    for (var item in obj) {
                        console.log(obj[item].nombre)
                    }
                    jQuery('div.overlay-xubio-woo').hide();
                }else{
                    jQuery('div.overlay-xubio-woo div.message strong').html(xubiowoo.loadFailCredentials);
                    jQuery('div.overlay-xubio-woo img').hide();
                    jQuery('div.overlay-content-xubio-woo .close').show();
                }
            },
            error: function(x, s, e) {
                console.log(x.responseText + s.status + e.error);
            }
        });
    }
});