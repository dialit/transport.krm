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
        
        <script src='https://www.google.com/recaptcha/api.js'></script>
        
    </head>
    
    <body class="cbp-spmenu-push">
    
        <!-- Info Modal -->
        <div id="myModalBox" class="modal fade">
            <div class="modal-dialog modal-lg" role="document">
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
        </div><!-- End Info Modal -->
        
        <!-- Feedback Modal-->
        <div id="feedback" class="modal fade" tabindex="-1" aria-hidden="true" aria-labelledby="ModalLabel">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="gridSystemModalLabel">Связаться с разработчиками сайта</h4>
                    </div>
                    <div class="modal-body">
                        <div class="hide" id="respons"></div>
                        <form method="POST" action="mail.php" id="mailForm">
                            <div class="row">
                                <div class="col-sm-6">
                                   <div class="form-group">
                                       <label for="labelName">Введите Ваше имя:</label>
                                       <input type="text" class="form-control" name="name" id="name" placeholder="Имя" required minlength="3" maxlength="30">
                                   </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                       <label for="labelEmail">Ваш Email:</label>
                                       <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">                               
                                    <div class="form-group">
                                        <label for="labelPhone">Телефон:</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="+38(___)___-__-__" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="labelText">Текст сообщения:</label>
                                        <textarea name="text" id="text" cols="30" rows="10" class="form-control" placeholder="Максимум 500 символов" maxlength="500"></textarea>
                                    </div>
                               </div>
                            </div>
                            <div class="g-recaptcha" data-sitekey="6LciQCsUAAAAAMXAYzfYUEBS9oiH0StRVln2IE1e"></div><br />
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </form>
                    </div>
               </div>
            </div>
        </div> <!-- End of Feedback Modal -->
       
       <!-- About Modal-->
       <div id="about" class="modal fade feedback" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="ModalLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title">О проекте</h4>
                   </div>
                   <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-body">
                               <table class="table table-striped table-bordered table-hover">
                                   <tbody>
                                       <tr>
                                           <td class="text-justify">Сайт предназначен для жителей и гостей города Краматорск, Донецкой области. При помощи данного сайта пользователь получает возможность легко и быстро ориентироваться в выборе транспорта для поездок по городу, находить нужный маршрут движения и ближайшие остановки.</td>
                                       </tr>
                                       <tr>
                                           <td class="text-justify"><a href="https://brainbasket.org/ru/homepage/" target="_blank"><img class="img-thumbnail pull-right" src="https://brainbasket.org/wp-content/uploads/logo-2.png" alt=""></a>Разработано в рамках программы фонда <a href="https://brainbasket.org/ru/homepage/" target="_blank">BrainBasket</a> как финальное задание при прохождении образовательного курса <a href="https://brainbasket.org/ru/technology-nation-3/">Technology Nation</a>.
                                           </td>
                                       </tr>
                                    </tbody>
                               </table>

                               <table class="table table-bordered table-hover">
                                   <caption>Разработчики:</caption>
                                   <tbody>
                                       <tr class="text-danger">
                                           <th title="">Максим Пономарёв</th>
                                           <td>Mentor</td>
                                       </tr>
                                       <tr class="text-warning">
                                           <th title="">Сергей Литвиненко</th>
                                           <td>Team-lead / Front-end / Back-end</td>
                                       </tr>
                                       <tr>
                                           <th title="">Алексей Сальников</th>
                                           <td>Front-End / Back-End</td>
                                       </tr>
                                       <tr>
                                           <th title="">Дмитрий Комаринский</th>
                                           <td>Front-End</td>
                                       </tr>
                                   </tbody>
                               </table>

                               <table class="table table-striped table-bordered table-hover">
                                  <caption>При разработке сайта использовались web-технологии:</caption>
                                   <tbody>
                                       <tr class="text-center">
                                           <td>HTML5</td>
                                           <td>CSS3</td>
                                           <td>JavaScript</td>
                                           <td>PHP</td>
                                           <td>MySQL</td>
                                           <td>Bootstrap</td>
                                       </tr>
                                   </tbody>
                               </table>
                                      
                               <div>Надеемся, что этот сайт будет Вам полезен!
                                   <button class="btn btn-default feedback pull-right" data-toggle="modal" data-target="#feedback">Написать разработчикам</button>
                               </div>
                            </div>
                        </div>
                   </div>
               </div>
           </div>
       </div><!-- End of About Modal -->
        
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
                    <ul class="nav navbar-nav nav-pills">
                        <li><button class="btn btn-default" id="showLeft">Маршруты</button></li>
<!--                        <li><button id="showRight">Link</button></li>-->
                    </ul>
                    <form class="navbar-form navbar-left" id="form" role="form">
                        <div class="form-group">
                            <input type="text" id="pac-input" class="form-control" placeholder="Поиск адреса">
                            <input type="text" id="q" class="form-control" placeholder="Поиск остановки">
                                <a href="javascript:n_stops_chn();" class="button btn btn-default btn-xs" id="buttonReset" type="button">Сброс</a>
                                <a href="javascript:infoGeoFind();" class="button btn btn-default btn-xs" id="button" type="button">GPS</a>
                        </div>
                    </form>
                    <span class="time"><? echo date('d.m.Y H:i'); ?></span>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#about">
                            <i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> О проекте</button>
                        </li>
<!--                        <li><button class="btn btn-default" data-toggle="modal" data-target=".feedback">Обратная связь</button></li>-->
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
       <div class="">
            <a class="marquee" id="showLeft1" title="Свернуть">
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
                                        $taxies = query("SELECT id, n_marshr, nach_kon FROM `transport` WHERE type = 'Маршрутное такси'");
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
                                        $buses = query("SELECT id, n_marshr, nach_kon FROM `transport` WHERE type = 'Автобус'");
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
                                        $tbuses = query("SELECT id, n_marshr, nach_kon FROM `transport` WHERE type = 'Троллейбус'");
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
                                        $trams = query("SELECT id, n_marshr, nach_kon FROM `transport` WHERE type = 'Трамвай'");
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
        
        <!-- http://digitalbush.com/projects/masked-input-plugin/ -->
        <script src="js/jquery.maskedinput.min.js"></script>

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

<!--
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
-->
        <script>
        $(document).ready(function(){
            //при нажатию на любую кнопку, имеющую класс .feedback
            $(".feedback").click(function() {
            //скрыть модальное окно с id="about"
            $("#about").modal('hide');
            });
        });
        </script>
        
        <script>
        $(document).ready(function(){
            $("#mobile").mask("+38(999)999-99-99",{placeholder:"+38(___)___-__-__"});
        })
        </script>
        
    </body>
</html>
