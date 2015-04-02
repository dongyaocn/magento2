/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        '../../../model/quote',
        'Magento_Catalog/js/price-utils'
    ],
    function (Component, quote, priceUtils) {
        "use strict";
        var ownClass = '';
        var columnTitle = '';
        return Component.extend({
            defaults: {
                ownClass: ownClass,
                columnTitle: columnTitle,
                template: 'Magento_Checkout/review/item/column'
            },
            getClass: function() {
                return 'col ' + this.ownClass;
            },
            getColName: function() {
                return this.columnTitle;
            },
            getValue: function(quoteItem) {
                return quoteItem.name;
            },
            getFormattedPrice: function (price) {
                //todo add format data further
                return quote.getCurrencySymbol() + priceUtils.formatPrice(price);
            }
        });
    }
);
