$(function () {
    populateBillingCountries("billing_address_countryId");
    function populateBillingCountries(country_element_id, state_element_id) {
        var country_list = getCountries();
        var country_element = document.getElementById(country_element_id);
        country_element.length = 0;
        country_element.options[0] = new Option('Select Country', '-1');
        country_element.selectedIndex = 0;
        for (var i = 0; i < country_list.length; i++) {
            country_element.options[country_element.length] = new Option(country_list[i], country_list[i]);
        }

        if (state_element_id) {
            country_element.onchange = function () {
                populateStates(country_element_id, state_element_id);
            };
        }

        var buyer_country = $('#billingCountry').val().trim();
        $('#billing_address_basic_country .bootstrap-select button').removeClass('bs-placeholder');
        $('#billing_address_basic_country .dropdown-menu li').removeClass("selected");
        $("#billing_address_basic_country .dropdown-menu li").each(function (index) {
            if ($(this).text() == buyer_country)
            {
                $(this).addClass("selected");
                $('#billing_address_basic_country .dropdown-toggle .filter-option').text(buyer_country);
            }
        });
        $('#billing_address_countryId option:selected').prop("selected", false);
        $("#billing_address_countryId option").each(function (index) {
            var value_selected = $(this).text().trim();
            if (value_selected == buyer_country)
            {
                $(this).prop('selected', 'selected');
            } else
            {
                $(this).prop("selected", false);
            }
        });
    }
    populateCountries("countryId");
    function populateCountries(country_element_id, state_element_id) {
        var country_list = getCountries();
        var country_element = document.getElementById(country_element_id);
        if (country_element) {
            country_element.length = 0;
            country_element.options[0] = new Option('Select Country', '-1');
            country_element.selectedIndex = 0;
            for (var i = 0; i < country_list.length; i++) {
                country_element.options[country_element.length] = new Option(country_list[i], country_list[i]);
            }
            if (state_element_id) {
                country_element.onchange = function () {
                    populateStates(country_element_id, state_element_id);
                };
            }
            var buyer_country = $('#buyerCountry').val().trim();
            $('#basic_country .bootstrap-select button').removeClass('bs-placeholder');
            $('#basic_country .dropdown-menu li').removeClass("selected");
            $("#basic_country .dropdown-menu li").each(function (index) {
                if ($(this).text() == buyer_country)
                {
                    $(this).addClass("selected");
                    $('#basic_country .dropdown-toggle .filter-option').text(buyer_country);
                }
            });
            $('#countryId option:selected').prop("selected", false);
            $("#countryId option").each(function (index) {
                var value_selected = $(this).text().trim();
                if (value_selected == buyer_country)
                {
                    $(this).prop('selected', 'selected');
                } else
                {
                    $(this).prop("selected", false);
                }
            });
        }


    }

   $('.selectpicker').selectpicker();

});