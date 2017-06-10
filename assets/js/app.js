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
                            content += '<td class="tijd">' + res + ' <small><red>'+ ver + '</red></small></td>';
                        }else {
                            content += '<td class="tijd">' + res  + '</td>';
                        }
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
                        content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span></td>';
                        if (typeof(tijd.Opmerkingen) != "undefined"){
                            content += '<td>' + tijd.Opmerkingen.Opmerking + '</td>';
                        }else {
                            content += '<td> </td>';
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
                            content += '<td class="tijd">' + res + ' <small><red>'+ ver + '</red></small></td>';
                        }else {
                            content += '<td class="tijd">' + res  + '</td>';
                        }
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
                        content += '<td class="vervoer"><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"> <span>' + tijd.TreinSoort + '</span></td>';
                        if (typeof(tijd.Opmerkingen) != "undefined"){
                            content += '<td>' + tijd.Opmerkingen.Opmerking + '</td>';
                        }else {
                            content += '<td> </td>';
                        }
                        content += '</tr>';
                        $(content).appendTo("#departure");
                    }

                    return i<10;
                });
            }
        })
    }
    get_json();
    setInterval(get_json,10000);
    });