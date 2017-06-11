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
                        content = '<tr class="odd">';
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
                        content += '<td class="to_via">' + tijd.EindBestemming + '<br><small>'+ $text +'</small></td>';
                        if (Object.values(tijd.VertrekSpoor)[0] === "false"){
                            content += '<td> </td>';
                        }else {
                            content += '<td class="spoort"><div class="spoor">' + Object.values(tijd.VertrekSpoor)[0]  + '</div></td>';
                        }
                        if (typeof(ver) != "undefined"){
                            content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span><br><div class="delay"><small>'+ ver +' Minuten</small></div></td>';
                            content += '<td><div class="delay">&nbsp</div></td>';
                        }else{
                            content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span></td>';
                            content += '<td></td>';
                        }
                        if (typeof(tijd.Opmerkingen) != "undefined"){
                            var opmerking = tijd.Opmerkingen.Opmerking;
                        }else {
                            var opmerking = "";
                        }
                        content += '</tr>';
                        $(content).appendTo("#departure");
                    }else {
                        content = '<tr class="even">';
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
                        content += '<td class="to_via">' + tijd.EindBestemming + '<br><small>'+ $text +'</small></td>';
                        if (Object.values(tijd.VertrekSpoor)[0] === "false"){
                            content += '<td> </td>';
                        }else {
                            content += '<td class="spoort"><div class="spoor">' + Object.values(tijd.VertrekSpoor)[0]  + '</div></td>';
                        }
                        if (typeof(ver) != "undefined"){
                         content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span><br><div class="delay"><small>'+ ver +' Minuten</small></div></td>';
                            content += '<td><div class="delay">&nbsp</div></td>';
                        }else{
                            content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span></td>';
                            content += '<td></td>';
                        }
                        if (typeof(tijd.Opmerkingen) != "undefined"){
                            var opmerking = tijd.Opmerkingen.Opmerking;
                        }else {
                            var opmerking = "";
                        }


                        content += '</tr>';
                        $(content).appendTo("#departure");
                    }

                    return i<9;
                });
            }
        })
    }
    get_json();
    setInterval(get_json,10000);
    });