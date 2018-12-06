<?php

   function sjis($str)  //utf-8からShift-jis
   {
   $sstr =  mb_convert_encoding($str, "SJIS", "UTF-8");
   return $sstr;
   }

   function gps_city($lat,$lng) //gps→区、市の情報へ変換　google ap i使用
   {
   $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&language=ja&sensor=false';
      $json = file_get_contents($url);
      $rg = json_decode($json);
      $results = $rg->{'results'}[0]->{'address_components'} ;
      foreach($results as $key=>$array)
       {
       $value = $array->{'long_name'};
       $name = $array->{'types'}['0'];
       if($name == 'locality' ){
       $reverse_geocode['locality'] = $value;
       }
       }
      return  $reverse_geocode['locality'];
    }

    function weather($lat,$lng) // 天気情報取得　open weather map 使用
    {
      $url = 'http://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lng.'&appid=81f6a75b6ded7b9bf1de7296e81d9c49';
      //$url = 'http://api.openweathermap.org/data/2.5/weather?q=Hachioji,jp&units=metric&appid=81f6a75b6ded7b9bf1de7296e81d9c49';
      $json = file_get_contents($url);
      $arr = json_decode($json,true);
      $id = $arr['weather'][0]['id'];
      $main =  $arr['weather'][0]['main'];
      if($id==800 || $id==801 || $id==802 ){
        $weather = 0;
      }else if($id==803 || $id ==804){
        $weather = 1;
      }else if($main==200 || $main==300 || $main==500){
        $weather = 2;
      }else if($id=600){
        $weather = 3;
      }else{
        $weather = 4;
	}
      return $weather;
    }

   function holiday($day){
    $year = date('Y');
    $file = fopen("calendar/{$year}.txt", "r"); //年に注意
    if($file){ 
    while ($line = fgets($file)) {
     if($day==rtrim($line)){
      return 1;
      }
      }
      }
      return 0;
      }

   function format($input,$value,$color,$type,$key,$zinkou,$nensyu,$crime,$month,$hour,$weekno,$hday,$pir,$humid,$tempareture,$lux,$weather)
   {

   $color = format2($color,8);
   $type = format2($type,6);
   $month = format2($month,11);
   $hour = format2($hour,3);
   $weekno = format2($weekno,6);
   $weather = format2($weekno,4);

   $data = $input.','.$value.','.$color.','.$type.','.$key.','.$zinkou.','.$nensyu.','.$crime.','.$month.','.$hour.','.$weekno.','.$hday.','.$pir.','.$lux.','.$tempareture.','.$humid .','.$weather; 

  
   file_put_contents("predict.csv", $data);
   
   }

   function format2($data,$num){
   $str = '';
   for($i=0;$i<$num;$i++){
    $d[$i] = 0;
    if($i == $data){
     $d[$i] = 1;
    }
    if($i != $num-1){
    $str = $str.$d[$i].',';
    }else{
    $str = $str.$d[$i];
    }
   }
   return $str;
   }

   function danger($input,$pir,$humid,$tempareture,$lux,$lat,$lng){

      $city =  gps_city($lat,$lng); //リバースジオコーディングで区、市情報取得


      $weather = weather($lat,$lng); //天気情報取得　晴れ０　曇り１　雨２　雪３　その他４


      //DB接続
      $dsn = 'odbc:test';
      $user = '';
      $password = '';
      try{
      $pdo = new PDO($dsn, $user, $password);
       if ($pdo == null){

       }else{

    }

    //Accessがshift-jisを使用しているため文字コード変換
    $str[0] = "地区情報";
    $str[1]= "地区";
    $str[2] = $city;
    $str[3] = "人口密度";
    $str[4] = "平均年収";
    $str[5] = "犯罪件数";
    $str[6] = "自転車";
    $str[7] = "価格";
    $str[8] = "色";
    $str[9] = "車種";
    $str[10] = "鍵の個数";



    for ($i = 0; $i < 11; $i++) {
         $str[$i] = sjis($str[$i]);
    }

    $sql = "SELECT * FROM $str[0] WHERE $str[1] = '$str[2]'";
    $stmt = $pdo->query($sql);

      foreach ($stmt as $row) {
      $zinkou = $row[$str[3]];
      $nensyu = $row[$str[4]];
      $crime = $row[$str[5]];
     }
      $sql = "SELECT * FROM $str[6]";
      $stmt = $pdo->query($sql);
      foreach ($stmt as $row) {
      $value = $row[$str[7]];
      $color = $row[$str[8]];
      $type = $row[$str[9]];
      $key = $row[$str[10]];
    }
      $sql = null;
      $pdo = null;
      }catch (PDOException $e){
      print('Error:'.$e->getMessage());
      }
      
    $day = date('n').date('j');
    $hday = holiday($day);
    $hour = date('G');

    if($hour>=0 && $hour<6){
     $hour = 0;
    }else if($hour>=6 && $hour<12){
     $hour = 1;
    }else if($hour>=12 && $hour<18){
     $hour = 2;
    }else{
     $hour = 3;
    }

    $weekno = date('w');
    $month = date('n') - 1;

    format($input,$value,$color,$type,$key,$zinkou,$nensyu,$crime,$month,$hour,$weekno,$hday,$pir,$humid,$tempareture,$lux,$weather);
    $fullPath ='python C:\cygwin\home\al13109\svm.py';
      exec($fullPath, $outpara);
      return $outpara[0];
   
   }
   
 ?>