function ville_autocomplete()
{
    $('.js-ville-autocomplete').each(function () {
        let autocompleteUrl = $(this).data('autocomplete-url');

        $(this).autocomplete({
            minLength: 3,
            source: function (request, response) {
                $.ajax({
                    url: autocompleteUrl,
                    data: {query: request},
                    success: function (data) {
                        let myArray = [];
                        $.each(data.listeVilles, function () {
                            $.each(this, function (k, v) {
                                myArray.push(v);
                            });
                        });
                        response(myArray);
                    },
                    error: function () {
                        response([]);
                    }
                });
            }
        });
    });
}

$(document).ready(ville_autocomplete);