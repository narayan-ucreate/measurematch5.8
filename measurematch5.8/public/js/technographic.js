$('body').on('click', '#show_more_popular_skill', function(e) {
    $(this).addClass('hide')
    e.preventDefault();
    var hidden_skills = $('#hidden_popular_skills').val();
    $.ajax({
        url : 'technographic-results?more_popular_skill=1&hidden_skills=' + hidden_skills,
        success: function(result) {
            $('#popular_skill_section').append(result);
        }

    })
});
$(window).load(function() {
    $('#loading_section').show();
    $('#search_results').hide()
    var domain = $('#domain_name').val();
    var company_name = $('#company_name').val();
    var logo = $('#logo').val();
    $.ajax({
        type: 'get',
        url: base_url + '/technographic-results',
        data: {domain : domain, company_name:company_name, logo:logo},
        success: function (response) {
            $('body').removeClass('webflow-bg');
            $('#loading_section').hide();
            $('#search_results').html(response);
            $('#search_results').show();
            $('#default_nav').show();
            $('#default_footer').show();
            searchCompnayDropdown();
        }
    });
    if ($( ".company-search" ).length > 0) {
        $(".company-search").autocomplete({
            source: function (request, response) {
                responseData(request.term, response);

            },
            select: function (event, ui) {
                $(".company-search").val(ui.item.domain)
                redirectToPage(ui);
                return false;
            }
        })
            .data("ui-autocomplete")._renderItem = function (ul, item) {
            return appendList(item, ul);
        };
    }
    function searchCompnayDropdown() {
        if ($(document).find(".search-company").length > 0) {
            $(document).find(".search-company").autocomplete({
                source: function (request, response) {
                    responseData(request.term, response);
                },
                select: function (event, ui) {
                    redirectToPage(ui);
                }
            })
                    .data("ui-autocomplete")._renderItem = function (ul, item) {
                return appendList(item, ul);
            };
        }
    }

    function responseData(term, response) {
        $.get(base_url+'/get-technographic-logo', { query: term}, function(data) {
            response(data)
        });
    }

    function redirectToPage(ui) {
        window.location.href =base_url+"/technographic-info?name=" + ui.item.name+'&logo='+ui.item.logo+'&domain='+ui.item.domain;
    }

    function appendList(item, ul) {
        return $( "<li>" )
            .append('<a href="javascript:void(0)"><img src='+item.logo+' width=50 height="50"><span class="company-name"> '+item.truncate_name+'</span><span class="company-domain">'+item.domain+'</span> </a>')
            .appendTo( ul );
    }

    $(document).on('keyup', '.company-search', function() {
        var value = $(this).val();
        if (value != '') {
            $('.clear-search').removeClass('hide');
        } else {
                $('.clear-search').addClass('hide');
        }
    });
    $(document).on('click', '.clear-search', function(e) {
        e.preventDefault();
        $('.company-search').val('')
        $('.search-company').val('')
        $(this).addClass('hide')
        $('.company-search').focus()
        $('.search-company').focus()
    });
    $('body').on('click', '.display-expert-overlay' , function(e) {
        e.preventDefault();
        var buyer_logged_in = $(this).attr('buyer-logged-in');
        if (buyer_logged_in == 1) {
            var skill_name = $(this).find('.skill-name').text();
            window.location.href = base_url + '/buyer/experts/search?search=' + skill_name;
        } else {
            overlay(this);
        }
    })
    $('body').on('click', '.display-expert-overlay-child', function(e) {
        overlay($(this).parents('.display-expert-overlay').eq(0))
    })
    function overlay(object) {
        $('#expert-view-overlay').html($(object).find('.expert-view').html());
        $('.overlay-heading').text($(object).find('.skill-name').text());
        $('.overlay-skill-logo').attr('src', $(object).find('.icon-logo').attr('src'))
        $("#expert_detail_overlay").modal('show')
    }
});
