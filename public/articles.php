<?php

    require(__DIR__ . "/../includes/config.php");

    // ensure proper usage
    if (empty($_GET["geo"]))
    {
        http_response_code(400);
        exit;
    }

    // escape user's input
    $geo = urlencode($_GET["geo"]);
    //$geo = $_GET["geo"];
    
    
    //$rows = query("SELECT `id`, `n_marshr` FROM `transport` WHERE FIND_IN_SET($geo,`front`)>0 OR FIND_IN_SET($geo,`back`)>0");
    
    if ($_GET["n_qwery1"] == "1")
    {
    $rows = query("SELECT `id`,`type`,`n_marshr`,`nach_kon` FROM `transport` WHERE FIND_IN_SET($geo,`front`)>0 OR FIND_IN_SET($geo,`back`)>0");
    }
    if ($_GET["n_qwery1"] == "2")
    {
    $rows = query("SELECT `id`,`type`,`n_marshr`,`nach_kon` FROM `transport` WHERE id = $geo");
    }
    
    //$positions = [];
    //foreach ($rows as $row)
    //{
    //        $positions[] = [
    //        "n_marshr" => $row["n_marshr"]
    //        ];
    //}
    // print_r(JSON_encode($positions));
    //exit;
    //$contents=$positions;
    $contents=$rows;
       
    if ($contents === false)
    {
        http_response_code(503);
        exit;
    }
    
    
    
    //print_r(JSON_encode($rows));
    //exit;
    // parse RSS
    ///$rss = @simplexml_load_string($contents);
    //@fclose($handle);
    //if ($rss === false)
    
    
    
    //if ($content === false)
    //{
    //    http_response_code(500);
    //    exit;
    //}

    // iterate over items in channel
    //foreach ($rss->channel->item as $item)
    //{
        // add article to array
    //    $articles[] = [
    //        "link" => (string) $item->link,
    //        "title" => (string) $item->title
    //    ];
    //}

   



    // output articles as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($contents, JSON_PRETTY_PRINT));

?>
