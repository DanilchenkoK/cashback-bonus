define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'cashbackbonus',
                component: 'Kirill_Cash/js/view/payment/method-renderer/cashback-method'
            },
            {
                type: 'bonus',
                component: 'Kirill_Cash/js/view/payment/method-renderer/bonus-method'
            }
        );
        return Component.extend({});
    }
);
