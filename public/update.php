<?php

    require(__DIR__ . "/../includes/config.php");
    // ensure proper usage
    //if (!isset($_GET["sw"], $_GET["ne"]))
        //{
       //     http_response_code(400);
       //     exit;
      //  }

    // ensure each parameter is in lat,lng format
   // if (!preg_match("/^-?\d+(?:\.\d+)?,-?\d+(?:\.\d+)?$/", $_GET["sw"]) ||
   //     !preg_match("/^-?\d+(?:\.\d+)?,-?\d+(?:\.\d+)?$/", $_GET["ne"]))
   //     {
   //     http_response_code(400);
   //     exit;
   //     }

    // explode southwest corner into two variables
    //list($sw_lat, $sw_lng) = explode(",", $_GET["sw"]);

    // explode northeast corner into two variables
    //list($ne_lat, $ne_lng) = explode(",", $_GET["ne"]);

    
    // $_GET["NN_marshr"] ID маршрута, который надо отобразить
    
    // отображение всех остановок
    // поиск остановок, которые входят в область экрана
    if ($_GET["n_qwery"] == 1)
    {
        $rows = query("SELECT * FROM stops");
        
        // if ($sw_lng <= $ne_lng)
        //     {
        //         // doesn't cross the antimeridian
        //         {
        //             $rows = query("SELECT * FROM stops WHERE ? <= latitude AND latitude <= ? AND (? <= longitude AND longitude <= ?) GROUP BY id", $sw_lat, $ne_lat, $sw_lng, $ne_lng);
        //         }
        //     }
        // else
        //     {
        //         // crosses the antimeridian
        //         {
        //             $rows = query("SELECT * FROM stops WHERE ? <= latitude AND latitude <= ? AND (? <= longitude OR longitude <= ?) GROUP_BY id", $sw_lat, $ne_lat, $sw_lng, $ne_lng);
        //         }
        //     }
    }
// если запрос остановок маршрута
    if ($_GET["n_qwery"] == 2)
        {
            // запрос на получение id остановок через которые проходит маршрут
            //$rows = query("SELECT `front`,`back` FROM `transport` WHERE `n_marshr` = ?",$_GET["NN_marshr"]);
            $rows = query("SELECT `front`,`back` FROM `transport` WHERE `id` = ?",$_GET["NN_marshr"]);
            foreach ($rows as $row)
            {
                // соединение списков id остановок маршрута в прямом и обратном направлении
                $pr = ",";
                $sum = $row["front"].$pr.$row["back"];
            }
            unset($row);
            $sum1 = str_replace('\"','',$sum );
            // запрос на получение названий и координат остановок через которые проходит маршрут
            $rows = query("SELECT * FROM `stops` WHERE `id` IN($sum1)");
        }
        
// если запрос координат линии маршрута    
    if ($_GET["n_qwery"] == 3)
        {
            // запрос на получение координат линии маршрута
            //$rows = query("SELECT `coord` FROM `transport` WHERE `n_marshr` = ?",$_GET["NN_marshr"]);
            $rows = query("SELECT `coord` FROM `transport` WHERE `id` = ?",$_GET["NN_marshr"]);
            $sum = $rows[0]["coord"];
            //$sum = "48.723647,37.538457;48.723662,37.538145;48.725541,37.53784;48.727857,37.539012;48.727942,37.54046;48.728076,37.540643;48.728098,37.540975;48.7276,37.541595;48.72685,37.542292;48.726716,37.542281;48.725941,37.541793;48.724811,37.542032;48.724287,37.541882;48.723551,37.5398;48.72341,37.538577;48.723649,37.538451;48.723796,37.539905;48.723739,37.540855;48.720928,37.552723;48.720886,37.553431;48.720963,37.558892;48.720415,37.561015;48.727383,37.564471;48.730674,37.567647;48.733501,37.569133;48.736584,37.571222;48.729718,37.590174;48.735259,37.594727;48.729889,37.609899;48.732887,37.612362;48.73554,37.615913;48.736633,37.617174;48.739785,37.620031;48.742908,37.622622;48.74312,37.622761;48.746574,37.612394;48.746839,37.611906;48.747165,37.611563;48.747866,37.611096;48.748732,37.613864;48.749994,37.618338;48.751017,37.619111";
            //var_dump(sum);
            $rows = $sum;
        }
// 
  // если запрос круга    
    if ($_GET["n_qwery"] == 4)
        {
            $sum = $_GET["NN_marshr"];
            $sum1 = explode(";",$sum);
            $lat_c = $sum1[0];
            $lng_c = $sum1[1];
            //$rows = query("SELECT *, ( 6371 * acos( cos( radians($lat_c) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng_c) ) + sin( radians($lat_c) ) * sin( radians(`latitude`) ) ) ) AS distance FROM stops HAVING distance < 0.5 ORDER BY distance");
            $rows = query("SELECT `id`, ( 6371 * acos( cos( radians($lat_c) ) * cos( radians(`latitude`) ) * cos( radians(`longitude`) - radians($lng_c) ) + sin( radians($lat_c) ) * sin( radians(`latitude`) ) ) ) AS distance FROM stops HAVING distance < 0.5 ORDER BY distance");
            foreach ($rows as $row)
            {
                
                $geo = $row["id"];
            }
            unset($row);
            
            if (isset($geo))
            {
            $geo = $rows[0]["id"];
            //var_dump($geo);
            $rows = query("SELECT `id`,`type`,`n_marshr`,`nach_kon` FROM `transport` WHERE FIND_IN_SET($geo,`front`)>0 OR FIND_IN_SET($geo,`back`)>0");
            }
            else $rows = "";
        //var_dump($rows);
        //print(json_encode($rows));    
            
        //exit;
        }
    // запрос координат остановки из модального окна
    if ($_GET["n_qwery"] == 5)
    {
        $rows = query("SELECT * FROM stops WHERE `id` = ?",$_GET["NN_marshr"]);
    }        
    
    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($rows, JSON_PRETTY_PRINT));





?>
