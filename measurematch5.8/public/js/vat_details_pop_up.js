input_data = $.map(
        JSON.parse($('#country_search_source').val()),
        function (
                value, key) {
            return {
                'label': value.country_name,
                'value': value.country_name,
                'vat_registered_status': value.vat,
                'country_code': value.country_code,
                'is_eu': value.eu,
            };
        });

$('#vat_company_country').autocomplete(
                {
                    source: input_data,
                    minLength: 0
                }).bind('focus', function () {
                    $(this).autocomplete("search");
                });

$('#vat_company_country').autocomplete(
{
    select: function (event, ui) {
        var country_vat_status = ui.item.vat_registered_status;
        var country_code = ui.item.country_code;
        $('#vat_country').val(country_code);
        if (country_vat_status == '1') {
            $('#vat_block').removeClass('hide');
            $('#vat_registered').attr('checked', 'checked');
        } else {
            $('#vat_block').addClass('hide');
        }
        $('#submit_business_information_on_start_conversation').addClass('standard-btn').removeClass('disable-btn');
    },
    change: function (event, ui) {
        if (ui.item == null) {
            setTimeout(
                function () {$(this).val("");
                    $(this).focus();
                    $('#vat_block').addClass('hide');
                    $('#submit_business_information_on_start_conversation').addClass('disable-btn').removeClass('standard-btn');
                },
                300);
        }
    }
});