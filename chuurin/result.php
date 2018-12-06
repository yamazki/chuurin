<!DOCTYPE html>
<html>
  <body>
    <?php //関数呼び出し
     require "function.php";
     $i = 0;
    ?>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
            width: 100%;
	    margin: 0 auto; 
        }
    </style>

    
    <div id="map"></div>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDaNqjJsCI1cCVMX12NXPRnsjKMP5VntAE"></script>
    <script>

     var map;
     var step; 
     
     function map_canvas() {
     var data = new Array();

  
     <?php
       while($i<5){
      $input = (int)$_POST['time'];
      $lines = file("id/{$i}.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $ll = $lines[count($lines)-1];
      $arr = explode(',', $ll);
      $pir = $arr[0];
      $humid = $arr[1];
      $tempareture = $arr[2];
      $lux = $arr[3];
      $lat[$i] = $arr[4];
      $lng[$i] = $arr[5];
      $outpara[$i] = danger($input,$pir,$humid,$tempareture,$lux,$lat[$i],$lng[$i]);		 
	if($outpara[$i] == '[ 1.]'){
                 $outpara[$i] = 'Parking:<FONT COLOR="BLUE">Low Risk</FONT>';
		 if($i == 0){
		 $outpara[$i] = 'Current Point:<FONT COLOR="BLUE">Low Risk</FONT>';
                 }
         }else if($outpara[$i] == '[ 0.]'){
                 $outpara[$i] = 'Parking:<FONT COLOR="RED">High Risk</FONT>';
		  if($i == 0){
                 $outpara[$i] = 'Current Point:<FONT COLOR="RED">High Risk</FONT>';
                  }
        }
	 
      $i = $i+1;		  
     }
     $jsonlat = json_encode($lat);
     $jsonlng = json_encode($lng);
     $jsonoutpara = json_encode($outpara);		 
    ?>
    
    var jsonlat = <?php echo $jsonlat; ?>;
    var jsonlng = <?php echo $jsonlng; ?>;
    var jsonoutpara = <?php echo $jsonoutpara; ?>;
    var i;
       
    for (i = 0; i < 5; i++) {   
   data.push({
        lat: jsonlat[i],
        lng: jsonlng[i],
        content: jsonoutpara[i]
    });

		    }
     
   var latlng = new google.maps.LatLng(jsonlat[0], jsonlng[0]);
		    //new google.maps.LatLng(data[0].lat, data[0].lng);

    var opts = {
        zoom: 15,
        center: latlng,
        scaleControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map"), opts);
    var markers = new Array();

    for (i = 0; i < data.length; i++) {
		    
        markers[i] = new google.maps.Marker({
            position: new google.maps.LatLng(data[i].lat, data[i].lng),
            map: map
		    });

       markerInfo(markers[i], data[i].content);
         }
        }

    function markerInfo(marker, name) {
      new google.maps.InfoWindow({
          content: name
      }).open(marker.getMap(), marker);
     }
    google.maps.event.addDomListener(window, 'load', map_canvas);
   </script>



  </body>
</html>
