define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Kirill_Cash/payment/bonuses'
            },
            getMailingAddress:  ()=> {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            }
        });
    }
);
