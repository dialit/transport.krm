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
    
    // numerically indexed array of articles
    $articles = [];
    
    // headers for proxy servers
    //$headers = [
    //    "Accept" => "*/*",
    //    "Connection" => "Keep-Alive",
    //    "User-Agent" => sprintf("curl/%s", curl_version()["version"])
    //];

    // download RSS from Google News
    //$context = stream_context_create([
    //    "https" => [
    //        "header" => implode(array_map(function($value, $key) { return sprintf("%s: %s\r\n", $key, $value); }, $headers, array_keys($headers))),
    //        "method" => "GET"
    //    ]
    //]);
    
    //$rows = query("SELECT `id`, `n_marshr` FROM `transport` WHERE FIND_IN_SET($geo,`front`)>0 OR FIND_IN_SET($geo,`back`)>0");
    $rows = query("SELECT * FROM `transport` WHERE FIND_IN_SET($geo,`front`)>0 OR FIND_IN_SET($geo,`back`)>0");
    
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
    // если указатель языка новостей "en"
    //if ($_GET["lang"] == "en") $contents = @file_get_contents("http://news.google.com/news?cf=all&hl=en&ned=us&geo={$geo}&output=rss", false, $context);
    
    // если указатель языка новостей "ru"
    //if ($_GET["lang"] == "ru") $contents = @file_get_contents("http://news.google.com/news?cf=all&hl=ru&ned=ru&geo={$geo}&output=rss", false, $context);
    
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
