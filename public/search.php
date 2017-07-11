<?php

    require(__DIR__ . "/../includes/config.php");

    // numerically indexed array of places
    $places = [];
    $prov = $_GET["geo"];

    //разбиваем запрос на слова 
    $tok = strtok($prov, " ,");
    $mas = [];

    //создаём массив из слов запроса
    while ($tok !== false) 
    {
        $mas[]=$tok;
        $tok = strtok(" ,");
    }

    //в зависимости от количества слов в запросе изменяем запрос к базе данных
    if(count($mas)==1) $prov1 = "+".$mas["0"]."*";
    if(count($mas)==2) $prov1 = "+".$mas["0"]." +>".$mas["1"]."*";
    if(count($mas)==3) $prov1 = "+".$mas["0"]." +".$mas["1"]." +>".$mas["2"]."*";
    if(count($mas)==4) $prov1 = "+".$mas["0"]." +".$mas["1"]." +".$mas["3"]." +>".$mas["4"]."*";
    if(count($mas)==4) $prov1 = "+".$mas["0"]." +".$mas["1"]." +".$mas["3"]." +".$mas["4"]." +>".$mas["5"]."*";
    
    // TODO: search database for places matching $_GET["geo"]
    //$places = query("SELECT * FROM places WHERE MATCH (place_name, admin_name1,postal_code) AGAINST (? IN BOOLEAN MODE)", $prov1);
    $places = query("SELECT * FROM stops WHERE MATCH (stops_name) AGAINST (? IN BOOLEAN MODE)", $prov1);
    
    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));
?>
