define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
        'jquery'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'bonuses',
                component: 'Kirill_Cash/js/view/payment/method-renderer/bonuses-method'
            }
        );
        return Component.extend({});
    }
);
