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
        
        <!-- Slide Push Menu component -->
        <link href="css/default.css" rel="stylesheet"/>
        <link href="css/component.css" rel="stylesheet"/>

        <!-- http://getbootstrap.com/ -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
        
        <!-- app's own CSS -->
        <link href="css/styles.css" rel="stylesheet"/>

        <title>Транспорт Краматорска</title>
        
    </head>
    
    <body class="cbp-spmenu-push">
    
        <!-- Modal -->
        <div id="myModalBox" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Закрыть">&times;</button>
                        <h4 class="modal-title">Информация о маршруте</h4>
                    </div>
                    <div class="modal-body">
                        <div id="routeInfoContent"></div>
                    </div>
                    <div class="modal-footer">
<!--                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>-->
                    </div>
                </div>
            </div>
        </div>
        
       <!-- Feedback  Modal-->
       <div id="feedback" class="modal fade feedback" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="ModalLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="gridSystemModalLabel">Форма обратной связи</h4>
                  </div>
                   <div class="modal-body">
                       <div class="row">
                           <div class="col-md-12">
                               <div class="hide" id="respons"></div>
                               <form method="POST" action="mail.php" id="mailForm">
                                   <div class="form-group">
                                       <label for="labelEmail">Email</label>
                                       <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                   </div>
                                   <div class="form-group">
                                       <label for="labelPhone">Телефон</label>
                                       <input type="text" class="form-control" name="mobile" id="mobile" placeholder="+38XXXXXXXXXX" required>
                                   </div>
                                   <div class="form-group">
                                       <label for="labelText">Текст</label>
                                       <textarea name="text" id="text" cols="30" rows="10" class="form-control"></textarea>
                                   </div>
                                   <button type="submit" class="btn btn-primary">Отправить</button>
                               </form>
                           </div>
                       </div>
                   </div><!-- End of Modal body -->
               </div><!-- End of Modal content -->
           </div><!-- End of Modal dialog -->
       </div><!-- End of Modal -->
        
        <header>       
        <nav class="navbar navbar-default">
             <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Переключение навигации</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><span>Транспорт Краматорска</span></a>
                </div>
                
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><button class="btn btn-default" id="showLeft">Маршруты</button></li>
<!--                        <li><a id="showRight">Link</a></li>-->
                    </ul>
                    <form class="navbar-form navbar-left" id="form" role="form">
                        <div class="form-group">
                            <input type="text" id="pac-input" class="form-control" placeholder="Поиск адреса">
                            <input type="text" id="q" class="form-control" placeholder="Поиск остановки">
                                <a href="javascript:n_stops_chn();" class="button btn btn-default btn-xs" id="buttonReset" type="button" onclick="resetMarshr()">Сброс</a>
                                <a href="javascript:infoGeoFind();" class="button btn btn-default btn-xs" id="button" type="button">GPS</a>
                                
                                
                                <script language="JavaScript">
                                    dayarray=new Array("воскресенье","понедельник","вторник","среда","четверг","пятница","суббота")
                                    montharray=new Array ("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря")
                                    var ndata=new Date();
                                    var day=dayarray[ndata.getDay()];
                                    var month=montharray[ndata.getMonth()];
                                    var date=ndata.getDate();
                                    var year=ndata.getYear()-100;
                                    datastr=("Сегодня "+ date +" "+ month +" 20"+ year +" года, "+day+"." )
                                </script>
                                <span>Сегодня: <script language="JavaScript">document.write(datastr);</script></span>
                                <span>Время: <div id="timedisplay"></div></span>
                                
<!--                                <span>Сегодня: <? echo date('d.m.Y H:i'); ?></span>  -->
                        
                        
                        </div>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li><button class="btn btn-default" data-toggle="modal" data-target=".feedback">Обратная связь</button></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
       <div class="">
            <a class="off" id="showLeft1" title="Свернуть">
            <i class="glyphicon glyphicon-chevron-left" aria-hidden="true"></i>
            </a>
            <h3>Выберите вид транспорта</h3>
       </div>
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
                            <div id="ButtonsNmarshr">    
                                <ul class="list-inline">
                                <?php
                                        $taxies = query("SELECT `id`,`n_marshr`,`nach_kon` FROM `transport` WHERE type = 'Маршрутное такси'");
                                        foreach ($taxies as $taxi) {
                                            $id = $taxi["id"];
                                            $ntaxi = $taxi["n_marshr"];
                                            $t = $taxi["nach_kon"];
                                            echo "<li><a role=\"button\" data-toggle=\"tooltip\" title=\"$t\" class=\"btn btn-default\" href=\"javascript:draw_marshr(3,$id)\" >$ntaxi</a></li>";
                                        }
                                    ?>
                                </ul>
                            </div>
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
                    <div id="collapse-2" class="panel-collapse collapse">
                        <!-- Содержимое Автобусов -->
                        <div class="panel-body">
                            <div id="ButtonsNmarshr">
                                <ul class="list-inline">
                                <?php
                                        $buses = query("SELECT `id`,`n_marshr`,`nach_kon` FROM `transport` WHERE type = 'Автобус'");
                                        foreach ($buses as $bus) {
                                            $id = $bus["id"];
                                            $nbus = $bus["n_marshr"];
                                            $t = $taxi["nach_kon"];
                                            echo "<li><a role=\"button\" data-toggle=\"tooltip\" title=\"$t\" class=\"btn btn-default\" href=\"javascript:draw_marshr(3,$id)\" title=\"$nbus\">$nbus</a></li>";
                                        }
                                    ?>
                                </ul>
                            </div>
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
                    <div id="collapse-3" class="panel-collapse collapse">
                        <!-- Содержимое Троллейбусов -->
                        <div class="panel-body">
                            <div id="ButtonsNmarshr">
                                <ul class="list-inline">
                                <?php
                                        $tbuses = query("SELECT `id`,`n_marshr`,`nach_kon` FROM `transport` WHERE type = 'Троллейбус'");
                                        foreach ($tbuses as $tbus) {
                                            $id = $tbus["id"];
                                            $ntbus = $tbus["n_marshr"];
                                            $t = $taxi["nach_kon"];    
                                            echo "<li><a role=\"button\" data-toggle=\"tooltip\" title=\"$t\" class=\"btn btn-default\" href=\"javascript:draw_marshr(3,$id)\" title=\"$ntbus\">$ntbus</a></li>";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Трамваи -->
                <div class="panel panel-default">
                    <!-- Заголовок -->
                    <div class="panel-heading">
                        <h4 class="panel-title text-center">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-4">Трамваи</a>
                        </h4>
                    </div>
                    <div id="collapse-4" class="panel-collapse collapse">
                        <!-- Содержимое Трамваев -->
                        <div class="panel-body">
                            <div id="ButtonsNmarshr">
                                <ul class="list-inline">
                                <?php
                                        $trams = query("SELECT `id`,`n_marshr`,`nach_kon` FROM `transport` WHERE type = 'Трамвай'");
                                        foreach ($trams as $tram) {
                                            $id = $tram["id"];
                                            $ntram = $tram["n_marshr"];
                                            $t = $taxi["nach_kon"];    
                                            echo "<li><a role=\"button\" data-toggle=\"tooltip\" title=\"$t\" class=\"btn btn-default\" href=\"javascript:draw_marshr(3,$id)\" title=\"$ntram\">$ntram</a></li>";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </nav>
            <!-- fill viewport -->
        <div class="container-map">
            
             <!-- https://developers.google.com/maps/documentation/javascript/tutorial -->
            <div id="map-canvas" class="height: 90%"></div>               
        </div>
        
        <footer></footer>

        <!-- https://developers.google.com/maps/documentation/javascript/ -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDddUMqAgSPHOym9KhggEoONdiHPQwUxpE&libraries=places"></script>

        <!-- http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.9/ -->
        <script src="js/markerwithlabel_packed.js"></script>

        <!-- http://jquery.com/ -->
        <script src="js/jquery-1.11.1.min.js"></script>

        <!-- http://getbootstrap.com/ -->
        <script src="js/bootstrap.min.js"></script>
        
        <!-- Подключения скрипта control-modal.js к странице -->
<!--        <script src="js/control-modal.js"></script>-->

        <!-- http://underscorejs.org/ -->
        <script src="js/underscore-min.js"></script>

        <!-- https://github.com/twitter/typeahead.js/ -->
        <script src="js/typeahead.jquery.js"></script>
        
        <script src="/js/modernizr.custom.js"></script>
        
        <!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script src="js/classie.js"></script>
				
		<!-- Slide Push Menu JavaScript -->
		<script src="js/spmenu.js"></script>
		
        <!-- Feedback JavaScript -->
		<script src="js/feedback.js"></script>

        <!-- app's own JavaScript -->
        <script src="js/scripts.js"></script>

        <script language="JavaScript">
            function getDate()
            {
            var date = new Date();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var seconds = date.getSeconds();
            if(hours < 10){
            hours = '0' + hours;
            }
            if(minutes < 10){
            minutes = '0' + minutes;
            }
            if(seconds < 10){
            seconds = '0' + seconds;
            }
            document.getElementById('timedisplay').innerHTML = hours + ':' + minutes + ':' + seconds;
            }
            setInterval(getDate, 0);
        </script>
        
    </body>
</html>
