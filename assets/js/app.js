$(document).ready(function(e) {
    function isEven(n) {
        return n == parseFloat(n) && !(n % 2);
    }
    function get_json() {
        $.ajax({
            url: 'assets/time.php',
            type: 'get',
            dataType: 'json',
            success: function(data) {
                $('#departure').empty();
                $.each(data, function(i,tijd){
                    if(isEven(i)){
                        content = '<tr class="odd">';}
                        else {
                        content = '<tr class="even">';}
                        var str = tijd.VertrekTijd;
                        var res = str.slice(11, 16);
                        if (typeof(tijd.VertrekVertragingTekst) != "undefined"){
                            var vert = tijd.VertrekVertragingTekst;
                            var ver = vert.slice(0,2);
                        }
                        content += '<td class="tijd">' + res  + '</td>';
                        if (typeof (tijd.RouteTekst) != "undefined"){
                            $text = 'via: '+ tijd.RouteTekst;
                        }
                        else {
                            $text = "&nbsp";
                        }
                        if (typeof(tijd.Opmerkingen) != "undefined"){
                            var opmerking = tijd.Opmerkingen.Opmerking;
                            console.log(tijd.Opmerkingen.Opmerking);
                            $opmerking = opmerking;
                        }
                        else{
                           $opmerking = ""; 
                        }
                        if($opmerking){
                            content += '<td class="to_via">' + tijd.EindBestemming + '<br><div class="opmerking"><small>'+ $opmerking +'</small></div></td>';
                        }else{
                        content += '<td class="to_via">' + tijd.EindBestemming + '<br><small>'+ $text +'</small></td>';
                        }
                        if (Object.values(tijd.VertrekSpoor)[0] === "false"){
                            content += '<td> </td>';
                        }else {
                            content += '<td class="spoort"><div class="spoor">' + Object.values(tijd.VertrekSpoor)[0]  + '</div></td>';
                        }
                        if (typeof(ver) != "undefined"){
                            content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span><br><div class="delay"><small>'+ ver +' Minuten</small></div></td>';
                            content += '<td class="vervoer"><img class="logo" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAH0lEQVR42mNkoBAwjhowasCoAaMGjBowasCoAcPNAACOMAAhOO/A7wAAAABJRU5ErkJggg=="><span>&nbsp</span><br><div class="delay"><small>&nbsp</small></div></td>';
                        }else{
                            content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span></td>';
                            content += '<td></td>';
                        }                        content += '</tr>';
                        $(content).appendTo("#departure");
                    

                });
            }
        })
    }
    get_json();

    // Update tables every 10 Seconds
    setInterval(get_json,10000);

    // Code for Clock
    var toggle = true;
    setInterval(function() {
        var d = new Date().toLocaleTimeString('en-US', { hour12: false, hour: 'numeric', minute: 'numeric' });
        var parts = d.split(":");
        $('#hours').text(parts[0]);
        $('#minutes').text(parts[1]);
        $("#colon").css({ visibility: toggle?"visible":"hidden"});
        toggle=!toggle;
    },1000);

    // Flash the delay message every 3 seconds
    var delay_toggle = true;
    setInterval(function() {
        $(".delay").css({ visibility: delay_toggle?"visible":"hidden"});
        delay_toggle=!delay_toggle;
    },2500);
    });