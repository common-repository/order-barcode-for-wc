jQuery(document).ready(function ($) {
    'use strict';

    $.order = {
        init: function () {

            //Print FAQ
            $('.prints').on('click', '.print', this.printFAQ);
        },

        printFAQ: function (e) {
            e.preventDefault();
            $('.woocommerce-order').parent('.woocommerce-customer-details');
                /*var prtContent = document.querySelector(" .woocommerce-order");
                var WinPrint = window.open();
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.print();
                WinPrint.close();*/


            $(".woocommerce-order").print({
                globalStyles : true, 
                mediaPrint : true, 
                iframe : true, 
                noPrintSelector : ".avoid-this",
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 250,
                title: null, 
                });
        }

    }
    //call the class
    $.order.init();
});
const bwipjs = require('bwip-js');

bwipjs.toBuffer({
        bcid:        'code128',       // Barcode type
        text:        '0123456789',    // Text to encode
        scale:       3,               // 3x scaling factor
        height:      10,              // Bar height, in millimeters
        includetext: true,            // Show human-readable text
        textxalign:  'center',        // Always good to set this
    }, function (err, png) {
        if (err) {
            // `err` may be a string or Error object
        } else {
            // `png` is a Buffer
            // png.length           : PNG file length
            // png.readUInt32BE(16) : PNG image width
            // png.readUInt32BE(20) : PNG image height
        }
    });