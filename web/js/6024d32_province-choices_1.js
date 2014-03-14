(function ( $ ) {
    'use strict';

    $(document).ready(function() {
        $('select[name$="[country]"]').on('change', function() {
            var provinceContainer = $(this).parents('fieldset').find('div.province-container');
            var provinceName = $(this).attr('name').replace('country', 'province');

            if (null === $(this).val()) {
                return;
            }

            $.get(provinceContainer.attr('data-url'), {countryId: $(this).val()}, function (response) {
                if (!response.content) {
                    provinceContainer.fadeOut('slow', function () {
                        provinceContainer.html('');
                    });
                } else {
                    provinceContainer.fadeOut('slow', function () {
                        provinceContainer.html(response.content.replace(
                            'name="sylius_address_province"',
                            'name="' + provinceName + '"'
                        ));

                        provinceContainer.fadeIn();
                    });
                }
            });
        });

        if('' === $.trim($('div.province-container').text())) {
            $('select.country-select').trigger('change');
        }

        var billingAddressCheckbox = $('input[type="checkbox"][name$="[differentBillingAddress]"]');
        var toggleBillingAddress = function() {
        	if(billingAddressCheckbox.is(':checked'))
        	{
        		$('.addressing form .disabled input, .addressing form .disabled select').removeAttr('disabled');
        		$('#sylius-billing-address-container fieldset').removeClass('disabled');
        	}else
        	{
        		$('#sylius-billing-address-container fieldset').addClass('disabled'); 
        		$('.addressing form .disabled input, .addressing form .disabled select').attr('disabled', 'disabled');
        	}
        };
        toggleBillingAddress();
        billingAddressCheckbox.on('change', toggleBillingAddress);
    });
})( jQuery );
