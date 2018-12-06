echo("\uFEFF<!DOCTYPE html>\r\n<html>\r\n  <body>\r\n    ");

require("function.php");

var i = 0;
echo("    <style>\r\n        html, body {\r\n            height: 100%;\r\n            margin: 0;\r\n            padding: 0;\r\n        }\r\n        #map {\r\n            height: 100%;\r\n            width: 100%;\r\n\t    margin: 0 auto; \r\n        }\r\n    </style>\r\n\r\n    \r\n    <div id=\"map\"></div>\r\n    <script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDaNqjJsCI1cCVMX12NXPRnsjKMP5VntAE\"></script>\r\n    <script>\r\n\r\n     var map;\r\n     var step; \r\n     \r\n     function map_canvas() {\r\n     var data = new Array();\r\n\r\n  \r\n     ");

while (i < 5) {
    var input = +_POST.time;
    var lines = file(`id/${i}.csv`, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    var ll = lines[lines.length - 1];
    var arr = ll.split(",");
    var pir = arr[0];
    var humid = arr[1];
    var tempareture = arr[2];
    var lux = arr[3];
    lat[i] = arr[4];
    lng[i] = arr[5];
    outpara[i] = danger(input, pir, humid, tempareture, lux, lat[i], lng[i]);

    if (outpara[i] == "[ 1.]") {
        outpara[i] = "Parking:<FONT COLOR=\"BLUE\">Low Risk</FONT>";

        if (i == 0) {
            outpara[i] = "Current Point:<FONT COLOR=\"BLUE\">Low Risk</FONT>";
        }
    } else if (outpara[i] == "[ 0.]") {
        outpara[i] = "Parking:<FONT COLOR=\"RED\">High Risk</FONT>";

        if (i == 0) {
            outpara[i] = "Current Point:<FONT COLOR=\"RED\">High Risk</FONT>";
        }
    }

    i = i + 1;
}

var jsonlat = JSON.stringify(lat);
var jsonlng = JSON.stringify(lng);
var jsonoutpara = JSON.stringify(outpara);
echo("    \r\n    var jsonlat = ");
echo(jsonlat);
echo(";\r\n    var jsonlng = ");
echo(jsonlng);
echo(";\r\n    var jsonoutpara = ");
echo(jsonoutpara);
echo(";\r\n    var i;\r\n       \r\n    for (i = 0; i < 5; i++) {   \r\n   data.push({\r\n        lat: jsonlat[i],\r\n        lng: jsonlng[i],\r\n        content: jsonoutpara[i]\r\n    });\r\n\r\n\t\t    }\r\n     \r\n   var latlng = new google.maps.LatLng(jsonlat[0], jsonlng[0]);\r\n\t\t    //new google.maps.LatLng(data[0].lat, data[0].lng);\r\n\r\n    var opts = {\r\n        zoom: 15,\r\n        center: latlng,\r\n        scaleControl: true,\r\n        mapTypeId: google.maps.MapTypeId.ROADMAP\r\n    };\r\n\r\n    var map = new google.maps.Map(document.getElementById(\"map\"), opts);\r\n    var markers = new Array();\r\n\r\n    for (i = 0; i < data.length; i++) {\r\n\t\t    \r\n        markers[i] = new google.maps.Marker({\r\n            position: new google.maps.LatLng(data[i].lat, data[i].lng),\r\n            map: map\r\n\t\t    });\r\n\r\n       markerInfo(markers[i], data[i].content);\r\n         }\r\n        }\r\n\r\n    function markerInfo(marker, name) {\r\n      new google.maps.InfoWindow({\r\n          content: name\r\n      }).open(marker.getMap(), marker);\r\n     }\r\n    google.maps.event.addDomListener(window, 'load', map_canvas);\r\n   </script>\r\n\r\n\r\n\r\n  </body>\r\n</html>\r\n");
