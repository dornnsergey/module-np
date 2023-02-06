define([
    'uiComponent',
    'jquery',
    'mage/translate',
    'knockout',
    'mage/url',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Ui/js/model/messageList',
    'jquery-ui-modules/autocomplete',
    'validation'
], function (
    Component,
    $,
    $t,
    ko,
    urlBuilder,
    errorProcessor,
    messageList
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: "Dorn_Novaposhta/shipping/np-item-content",
        },

        containerElem: '.np-checkout-container',
        whElem: '.np-warehouse',
        whMethodName: 'У відділення',
        addressMethodName: 'Адресна доставка',
        cityRef: ko.observable(''),
        selectedMethod: ko.observable(''),
        isSettlementSelected: ko.observable(false),

        availableMethods: function () {
            return [this.whMethodName, this.addressMethodName];
        },

        setValidationToForm: function () {
            $('#co-shipping-method-form').validation();
        },

        setSearchSettlement: function (element) {
            $(element).autocomplete({
                appendTo: $('#resultSettlement'),
                source: (request, response) => {
                    $.ajax({
                        url: urlBuilder.build('novaposhta/shipping/settlement'),
                        data: {
                            name: request.term,
                            limit: 10
                        },
                        beforeSend: () => $(this.containerElem).trigger('processStart'),
                        success: rs => {
                            $(this.containerElem).trigger('processStop');

                            if (rs.success) {
                                response(rs.data);
                            } else {
                                messageList.addErrorMessage({
                                    message: $t(rs.errorMessage)
                                });
                            }
                        },
                        error: rs => {
                            $(this.containerElem).trigger('processStop');

                            errorProcessor.process(rs, messageList);
                        }
                    })
                },
                select: (event, ui) => {
                    this.isSettlementSelected(true);
                    this.loadCityRefByName(ui.item.value)
                    $(this.whElem).val('');
                },
                change: (event, ui) => {
                    if (!ui.item) {
                        this.isSettlementSelected(false);
                        $(event.target).val('');
                    }
                },
                minLength: 2,
                delay: 600
            });
        },

        loadCityRefByName: function (cityName) {
            $.ajax({
                url: urlBuilder.build('novaposhta/shipping/getCityRef'),
                data: {
                    name: cityName,
                    limit: 1
                },
                beforeSend: () => $(this.containerElem).trigger('processStart'),
                success: rs => {
                    $(this.containerElem).trigger('processStop');

                    this.cityRef(rs.data[0]);
                },
                error: rs => {
                    $(this.containerElem).trigger('processStop');

                    errorProcessor.process(rs, messageList);
                }
            });
        },

        setSearchWh: function (element) {
            $(element).autocomplete({
                appendTo: $('#resultWh'),
                source: (request, response) => {
                    $.ajax({
                        url: urlBuilder.build('novaposhta/shipping/warehouse'),
                        data: {
                            cityRef: this.cityRef(),
                            query: request.term,
                            limit: 20
                        },
                        beforeSend: () => $(this.containerElem).trigger('processStart'),
                        success: rs => {
                            $(this.containerElem).trigger('processStop');

                            if (rs.success) {
                                response(rs.data);
                            } else {
                                messageList.addErrorMessage({
                                    message: $t(rs.errorMessage)
                                });
                            }
                        },
                        error: rs => {
                            $(this.containerElem).trigger('processStop');

                            errorProcessor.process(rs, messageList);
                        }
                    })
                },
                select: (event, ui) => {

                },
                change: (event, ui) => {
                    if (!ui.item) {
                        $(event.target).val('');
                    }
                },
                minLength: 1,
                delay: 600
            });
        },


        initialize: function () {
            this._super();

            return this;
        },
    });
});