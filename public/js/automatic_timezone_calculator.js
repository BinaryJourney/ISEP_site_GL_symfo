$(document).ready(function () {

    $('.message-timestamp').each(function(){
        let dateformat = "DD/MM/YYYY HH:mm";
        let m = moment.utc($(this).text(), dateformat);

        $(this).text(m.local().format(dateformat));
    });

});
