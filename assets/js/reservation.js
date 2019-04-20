;(function ($) {

    $(document).ready(function () {
        console.log(reservation.ajax_url);

        $("#reservenow").on('click', function () {
            $.post(reservation.ajax_url, {
                action: 'reservation',
                name: $("#name").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                persons: $("#persons").val(),
                date: $("#date").val(),
                time: $("#time").val(),
                rn: $("#rn").val()
            }, function (data) {
                console.log(data);
                if ('duplicate' == data) {
                    alert('You have already placed a request for this reservation. No need to submit again');
                } else {
                    $("#paynow").attr('href', data);
                    $("#reservenow").hide();
                    $("#paynow").show();
                }

            });
            return false;
        });

    });
})(jQuery);