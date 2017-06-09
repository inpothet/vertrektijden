$(document).ready(function () {
    var interval = 500;   //number of mili seconds between each call
    var refresh = function() {
        $.ajax({
            url: "assets/time.php",
            cache: false,
            success: function(html) {
                $('#vertrek').html(html);
                setTimeout(function() {
                    refresh();
                }, interval);
            }
        });
    };
    refresh();
});