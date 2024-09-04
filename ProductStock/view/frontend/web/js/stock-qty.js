define([
    'jquery',
    'Magento_Customer/js/section-config',
    'Magento_Customer/js/customer-data'
], function ($, sectionConfig, customerData) {
    'use strict';

    return function (config, element) {
        $.ajax({
            url: config.url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $(element).find('#product-stock-qty').text(data.qty);
            }
        });
    };
});
