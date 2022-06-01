$(document).ready(function () {

    $('.accept-booking').on('click', function () {
        let id = $(this).attr('id').split('-')[1];

        $.ajax({
            type: "POST",
            data: {id},
            url: "http://localhost:8000/booking/accept"
        }).done(function () {
            location.reload();
        })
    })

})