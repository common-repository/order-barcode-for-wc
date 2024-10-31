( function ( $ ) {
    $('#mainforms').submit( function(e) {
        e.preventDefault();
        //alert("sss")
        var data = $(this).serialize();
        jQuery.ajax({
            url: ORDERBARCODEADMIN.ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function(resp) {
                jQuery(".successMessage").show('slow');
            },
            error: function(error) {
                console.log(error)
            }
        })
    } );
} )( jQuery );