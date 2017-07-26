<?php
    // configuration
    require("../includes/config.php");
?>

<!DOCTYPE html>
<html lang="ru" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        
        <link href="css/drawer.css" rel="stylesheet">

        <!-- http://getbootstrap.com/ -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
        
        <!-- app's own CSS -->
        <link href="css/styles.css" rel="stylesheet"/>
        <link href="css/styles2.css" rel="stylesheet"/>

        <title>Транспорт Краматорска</title>
    </head>
    
    <body class="drawer drawer--left">
    <header>
      <button type="button" class="drawer-toggle drawer-hamburger">
          <span class="sr-only">toggle</span>
          <span class="drawer-hamburger-icon"></span>
      </button>
       
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Переключение навигации</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span>Транспорт Краматорска</span></a>
                </div>
                
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a class="drawer-toggle">Маршруты</a></li>
                    </ul>
                    <form class="navbar-form navbar-left" id="form" role="form">
                        <div class="form-group">
                            <input type="text" id="q" class="form-control" placeholder="Название остановки">
                            <a href="javascript:n_stops_chn();" class="button btn btn-default" id="button" type="button">Отображение маршрутов</a>
                        </div>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Обратная связь</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <nav class="drawer-nav" role="navigation">
        <h3>Выберите вид транспорта</h3>
            <div class="panel-group" id="accordion">
                
                <!-- Маршрутки -->
                <div class="panel panel-default">
                    <!-- Заголовок -->
                    <div class="panel-heading">
                        <h4 class="panel-title text-center">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-1">Маршрутные такси</a>
                        </h4>
                    </div>
                    <div id="collapse-1" class="panel-collapse collapse in">
                        <!-- Содержимое Маршруток -->
                        <div class="panel-body">
                            <ul class="list-inline">
                               <?php
                                    $taxies = query("SELECT id, n_marshr FROM `transport` WHERE type = 'Маршрутное такси'");
                                    foreach ($taxies as $taxi) {
                                        $id = $taxi["id"];
                                        $ntaxi = $taxi["n_marshr"];    
                                        echo "<li><a href=\"javascript:draw_marshr(3,$id)\" title=\"$ntaxi\">$ntaxi</a></li>";
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Автобусы -->
                <div class="panel panel-default">
                    <!-- Заголовок -->
                    <div class="panel-heading">
                        <h4 class="panel-title text-center">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-2">Автобусы</a>
                        </h4>
                    </div>
                    <div id="collapse-2" class="panel-collapse collapse in">
                        <!-- Содержимое Автобусов -->
                        <div class="panel-body">
                            <ul class="list-inline">
                               <?php
                                    $buses = query("SELECT id, n_marshr FROM `transport` WHERE type = 'Автобус'");
                                    foreach ($buses as $bus) {
                                        $id = $bus["id"];
                                        $nbus = $bus["n_marshr"];    
                                        echo "<li><a href=\"javascript:draw_marshr(3,$id)\" title=\"$nbus\">$nbus</a></li>"; 
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Троллейбусы -->
                <div class="panel panel-default">
                    <!-- Заголовок -->
                    <div class="panel-heading">
                        <h4 class="panel-title text-center">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-3">Троллейбусы</a>
                        </h4>
                    </div>
                    <div id="collapse-3" class="panel-collapse collapse in">
                        <!-- Содержимое Троллейбусов -->
                        <div class="panel-body">
                            <ul class="list-inline">
                               <?php
                                    $tbuses = query("SELECT id, n_marshr FROM `transport` WHERE type = 'Троллейбус'");
                                    foreach ($tbuses as $tbus) {
                                        $id = $tbus["id"];
                                        $ntbus = $tbus["n_marshr"];    
                                        echo "<li><a href=\"javascript:draw_marshr(3,$id)\" title=\"$ntbus\">$ntbus</a></li>";
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </nav>

        <!-- fill viewport -->
        <div class="container">
            
             <!-- https://developers.google.com/maps/documentation/javascript/tutorial -->
            <div id="map-canvas"></div>               

        </div>

        <!-- https://developers.google.com/maps/documentation/javascript/ -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDddUMqAgSPHOym9KhggEoONdiHPQwUxpE"></script>

        <!-- http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.9/ -->
<!--        <script src="js/markerwithlabel_packed.js"></script>-->

        <!-- http://jquery.com/ -->
        <script src="js/jquery-1.11.1.min.js"></script>

        <!-- http://getbootstrap.com/ -->
        <script src="js/bootstrap.min.js"></script>

        <!-- http://underscorejs.org/ -->
        <script src="js/underscore-min.js"></script>

        <!-- https://github.com/twitter/typeahead.js/ -->
        <script src="js/typeahead.jquery.js"></script>
        
        <script src="/js/modernizr.custom.js"></script>
        
        <!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script src="js/classie.js"></script>
				
        <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.2.0/iscroll.js"></script>
        
        <script src="js/drawer.js" charset="utf-8"></script>
        <script>
            $(document).ready(function() {
                $('.drawer').drawer();
            });
        </script>

        <!-- app's own JavaScript -->
        <script src="js/scripts.js"></script>
   
    </body>
</html>
