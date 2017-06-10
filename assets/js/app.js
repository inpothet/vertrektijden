$(document).ready(function(e) {
    function get_json() {
        $.ajax({
            url: 'assets/time.php',
            type: 'get',
            dataType: 'json',
            success: function(data) {
                $('#departure').empty();
                $.each(data, function(i,tijd){
                    content = '<tr>';
                    var str = tijd.VertrekTijd;
                    var res = str.slice(11, 16);
                    if (typeof(tijd.VertrekVertragingTekst) != "undefined"){
                        var vert = tijd.VertrekVertragingTekst;
                        var ver = vert.slice(0,2);
                        content += '<td class="tijd">' + res + ' <red>'+ ver + '</red></td>';
                    }else {
                        content += '<td class="tijd">' + res  + '</td>';
                    }
                    content += '<td><img class="logo" src="assets/img/' + tijd.Vervoerder.toLowerCase() + '.png"></td>';
                    content += '<td>' + tijd.TreinSoort + '</td>';
                    if (Object.values(tijd.VertrekSpoor)[0] === "false"){
                        content += '<td> </td>';
                    }else {
                        content += '<td class="spoor">' + Object.values(tijd.VertrekSpoor)[0]  + '</td>';
                    }
                    if (typeof (tijd.RouteTekst) != "undefined"){
                        $text = 'via: '+ tijd.RouteTekst;
                    }
                    else {
                        $text = "&nbsp";
                    }
                    content += '<td class="to_via">' + tijd.EindBestemming + '<br><small>'+ $text +'</small></td>';
                    if (typeof(tijd.Opmerkingen) != "undefined"){
                        content += '<td>' + tijd.Opmerkingen.Opmerking + '</td>';
                    }else {
                        content += '<td> </td>';
                    }
                    content += '</tr>';
                    $(content).appendTo("#departure");
                    return i<10;
                });
            }
        })
    }
    get_json();
    setInterval(get_json,10000);
    });