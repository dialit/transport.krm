/**
 * scripts.js
 * Global JavaScript.
 */

// Google Map
var map;
var geocoder = new google.maps.Geocoder;
// массив маркеров остановок
var markers = [];
// массив маркеров найденных остановок
var markersFind = [];
// массив маркетов определения координат
var markersCords = [];
// массив линий маршрутов
var line = [];
// массив преобразованных координат линий маршрута
var arr = [];
// массив окружностей
var circles = [];
var info_cords = [];
var tooltip;
// ID номера маршрута для построения
var NN_marshr = 0;
// флаг отображения названий маркеров остановок
var n_stops = 0;
// флаг поиска 
var marker_find = 0;
var array_marshr = [];
var routesPath;
// координаты инициализации карты
//var latitude = 48.738795;
//var longitude = 37.584883;
// 
var title_label = "Краматорск, Донецкая область";
// флаг типа запроса к UPDATE если 
//  1 запрос координат всех остановок
//  2 запрос координат остановок маршрута
//  3 запрос координат для построения линии маршрута
//  5 скрыть линию маршрута
var n_qwery = 1;
// флаг типа запроса к ARTICLES если 
// - 1 запрос списка маршрутов через остановку
// - 2 запрос информации о маршруте
var n_qwery1 = 1;
// info window
var info = new google.maps.InfoWindow();

// execute when the DOM is fully loaded
$(function() {

    // styles for map
    // https://developers.google.com/maps/documentation/javascript/styling

    var styles = [{
            "stylers": [{
                "visibility": "simplified"
            }]
        },
        {
            "featureType": "poi.business",
            "stylers": [{
                "visibility": "simplified"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text",
            "stylers": [{
                "visibility": "simplified"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.icon",
            "stylers": [{
                "visibility": "off"
            }]
        },
        {
            "featureType": "road.local",
            "stylers": [{
                "visibility": "simplified"
            }]
        },
        {
            "featureType": "transit.line",
            "stylers": [{
                "visibility": "on"
            }]
        },
        {
            "featureType": "transit.station",
            "stylers": [{
                "visibility": "on"
            }]
        },
        {
            "featureType": "transit.station.airport",
            "stylers": [{
                "visibility": "on"
            }]
        },
        {
            "featureType": "transit.station.bus",
            "stylers": [{
                "visibility": "on"
            }]
        },
        {
            "featureType": "transit.station.rail",
            "stylers": [{
                "visibility": "on"
            }]
        }
    ];

    // options for map
    // https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var originalMapCenter = new google.maps.LatLng(48.738795, 37.584883);
    var options = {
        center: originalMapCenter, // Краматорск, Дон. обл.
        disableDefaultUI: true,
        //disableDefaultUI: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        fullscreenControl: true,
        minZoom: 12,
        maxZoom: 17,
        //panControl: true,
        styles: styles,
        zoom: 12,
        zoomControl: true
    };

    // get DOM node in which map will be instantiated
    var canvas = $("#map-canvas").get(0);

    // instantiate map
    map = new google.maps.Map(canvas, options);

    //mMap.setMyLocationEnabled(true);
    //UiSettings.setMyLocationButtonEnabled(true);

    // configure UI once Google Map is idle (i.e., loaded)
    google.maps.event.addListenerOnce(map, "idle", configure);

    //infoGeoFind();
    update(n_qwery, NN_marshr);
});

//=================================================================================================================
// определение местоположения
function infoGeoFind() {
    if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            var infoGeo = new google.maps.InfoWindow({map: map});
            infoGeo.setPosition(pos);
            infoGeo.setContent('Вы находитесь здесь.');
            map.setCenter(pos);
            map.setZoom(16);
          }, function() {
            handleLocationError(true, infoGeo, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoGeo, map.getCenter());
        }
      

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }
}

//====================================================================================================================
            // обратное геокодирование
         //  geocoder.geocode({'location': latlngsum}, function(results, status) {
        //   if (status === 'OK') {
        //      if (results[1]) {
        //       //map.setZoom(11);
        //       //var marker = new google.maps.Marker({
        //          //position: latlng,
        //          //map: map
        //       //});
        //       var info_geocode = new google.maps.InfoWindow({
        //               content: results[1].formatted_address,
        //               position: new google.maps.LatLng(lat_cor+0.0008, lng_cor),
        //           });
        //       //infowindow.setContent(results[1].formatted_address);
        //       info_geocode.open(map);
        //       setTimeout(function () { info_geocode.close(); }, 5000);

        //      } else {
        //       window.alert('No results found');
        //      }
        //   } else {
        //      window.alert('Geocoder failed due to: ' + status);
        //   }
        //  });



// функция преобразования координат для построения линии маршрута
function ConvertCoordinates(data) {
    var arr = [];
    data.split(';').forEach(function(point, i, originArray) {
        var coordinates = point.substring(1, point.length - 2).split(',');
        var coordinatesObject = {
            lat: parseFloat(coordinates[0].substring(4)),
            lng: parseFloat(coordinates[1].substring(4))
        };
        arr.push(coordinatesObject);
    });
    //console.log(arr);
    return arr;
}
// генератор псевдослучайного цвета линии маршрута
function line_color() {
    var colors = ['#FF0000', '#000066', '#990099', '#CC00FF', '#00FF00', '#0000CC', '#FF33FF', '#00CCFF', '#FFCCCC', '#FFFF00'];
    color = colors[Math.floor(Math.random() * 10)];
    return color;
}


// Функция отрисовки маршрута
function draw_marshr(n_qwery, NN_marshr) {
    // ID маршрута для построения
    //------------------------------------------------------------------------------------------------------------------------
    //var NN_marshr = 19; // ID маршрута для построения   *******************************************************************
    // ----------------------------------------------------------------------------------------------------------------------
    
    // если флаг, что линия есть, и видима
    if (array_marshr[NN_marshr] == 2) n_qwery = 5;
    // если флаг, что линия есть, но не показана
    if (array_marshr[NN_marshr] == 1)
        {
            line[NN_marshr].setMap(map);
            // флаг, что линия есть и видима
            array_marshr[NN_marshr] = 2;
            // установить карту на такие координаты
            map.setCenter(new google.maps.LatLng(48.732644, 37.583284), 13);
            // после отрисовки увеличить карту
            map.setZoom(13);
        }
    
    // добавление линии
    if (n_qwery == 3 && array_marshr[NN_marshr] == 0)
    {
            // подготовка данных для передачи запроса в "update.php"
            var parameters = {
                n_qwery: n_qwery,
                NN_marshr: NN_marshr
            };
            $.getJSON("update.php", parameters)
                .done(function(data, textStatus, jqXHR) {
                    // построение линии маршрута
                    var routesPath = new google.maps.Polyline({
                        path: ConvertCoordinates(data),
                        // цвет линии
                        strokeColor: line_color(), //"#FFF000",
                        // прозрачность линии
                        strokeOpacity: 0.5,
                        // толщина линии
                        strokeWeight: 5
                    });
                    //line.push(routesPath);
                    
                    line[NN_marshr] = routesPath;
                    
                    line[NN_marshr].setMap(null);
                    //line[NN_marshr].setVisible(false);
                    
                    // флаг, что линия есть, но не показана
                    array_marshr[NN_marshr] = 1;

                    // установить карту на такие координаты
                    map.setCenter(new google.maps.LatLng(48.732644, 37.583284), 13);
                    // после отрисовки увеличить карту
                    map.setZoom(13);
                   
                    n_qwery1 = 2;
                    // запрос на получение информации о маршруте от "articles.php"
                    $.getJSON("articles.php", {
                            geo: NN_marshr,
                            n_qwery1: n_qwery1
                        })
                        .done(function(data, textStatus, jqXHR) {

                            // если информации нет
                            if (data.length === 0) {
                                showInfo(marker, "Нет информации.");
                            }
                            // иначе создание списка информации о маршруте
                            else {
                                var tooltip = "    8-)   "
                                var ul = "<ul>";
                                // шаблон списка информации о маршруте
                                var template = _.template("<li><a href = 'routes.php?id=<%- id %>' target = '_blank'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");

                                // создание списка с использованием шаблона
                                for (var i = 0, n = data.length; i < n; i++) {
                                    ul += template({
                                        n_marshr: data[i].n_marshr,
                                        id: data[i].id,
                                        type: data[i].type,
                                        nach_kon: data[i].nach_kon
                                    });
                                }

                                ul += "</ul>";

                                tooltip += ul;

                                attachInfoWindow(line[NN_marshr], tooltip); 

                            }
                        });


                });
    }
    else{
        // спрятать линию
        if (n_qwery == 5) 
        {
            array_marshr[NN_marshr] = 1;
            line[NN_marshr].setMap(null);
            n_qwery = 1;
        }
    }        
    // флаг запроса координат остановок маршрута
    //n_qwery = 2;       

    // запрос на получение id остановок маршрута от "update.php"
    //update(n_qwery, NN_marshr);
}

// функция информационного окна маршрута
function attachInfoWindow(routesPath, text_info) {
    routesPath.infoWindow = new google.maps.InfoWindow({
        content: text_info,
    });
    google.maps.event.addListener(routesPath, 'click', function(e) {
        var latLng = e.latLng;
        routesPath.setOptions({ strokeWeight: 15 });
        routesPath.infoWindow.setPosition(latLng);
        routesPath.infoWindow.open(map);
        setTimeout(function() {
            routesPath.infoWindow.close();
            routesPath.setOptions({ strokeWeight: 5 });
        }, 4500);

    });
}


// функция кнопки для проверки переключения различных параметров, можно убрать
function n_stops_chn() {
        for (i = 1; i < 42; i++) {
            n_qwery = 5;
            NN_marshr = i; 
            n_stops = 0;
            draw_marshr(n_qwery, NN_marshr)
        } 
}

// // Функция удаления линий маршрута
// function removeLine(NN_marshr) {
//     if (NN_marshr == "ALL") {
//         for (i = 1; i < line.length; i++) {
//             line[i].setMap(null);
//             array_marshr[i] = 2;
//             }
//         NN_marshr = 0;
//         n_qwery = 1;   
//     } else {
//         if (array_marshr[NN_marshr] == 3) {
//             line[NN_marshr].setMap(null);
//             //line[NN_marshr].setVisible(false);
//             array_marshr[NN_marshr] = 2;
//             NN_marshr = 0;
//             n_qwery = 1;
//             }
//     }
// }


// функция созданив маркеров остановок
function addMarker(place) {
    // пустой заголовок названия остановки
    var stops_name1 = "";
    // если флаг скрывать названия маркеров остановок
    if (n_stops == 0) {
        // пустое название
        var label_stops = place.stops_name1;
    };
    // если флаг отображать названия маркеров остановок
    if (n_stops == 1) {
        // получение названия маркера остановки
        var label_stops = place.stops_name1;
    };
    // если флаг поиска остановки
    if (marker_find == 1) {
        // получение названия маркера остановки
        var label_stops = place.stops_name1;
    };
    // создание маркера
    // если маркер искомой остановки, то у него отличный от других маркеров значёк и анимация 
    if (marker_find == 1) {
        var image = {
            url: '"img/index_1.png"',
            // This marker is 20 pixels wide by 32 pixels high.
            //size: new google.maps.Size(20, 32),
            // The origin for this image is (0, 0).
            //origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            //anchor: new google.maps.Point(0, 0)
        };



        var markerFind = new google.maps.Marker({
            icon: "/img/marker.png",
            position: new google.maps.LatLng(latitude + 0.0001, longitude),
            animation: google.maps.Animation.BOUNCE,
            map: map,
            labelContent: place.stops_name,
            //labelContent: label_stops,
            labelAnchor: new google.maps.Point(0, 0),
            labelClass: "label",
            title: place.stops_name + ",  ID-" + place.id
                //title: place.id + ", " + place.latitude + ", " + place.longitude
        });

        markersFind.push(markerFind);
        // сброс указателя маркера искомого остановки
        marker_find == 0;
    } else
    //остальные маркеры остановок
    {
        var marker = new MarkerWithLabel({
            icon: "/img/index_1.png",
            position: new google.maps.LatLng(place.latitude, place.longitude),
            map: map,
            animation: false,
            labelContent: label_stops,
            //labelContent: place.stops_name,
            labelAnchor: new google.maps.Point(0, 0),
            labelClass: "label",
            title: place.stops_name + ",  ID-" + place.id
                //title: place.id + ", " + place.latitude + ", " + place.longitude
        });
    }

    // создание списка маршрутов проходящих через остановку
    google.maps.event.addListener(marker, "click", function() {
        showInfo(marker);
        n_qwery1 = 1;
        // запрос на получение списка маршрутов от "articles.php"
        $.getJSON("articles.php", {
                geo: place.id,
                //geo: place.place_name,
                n_qwery1: n_qwery1
            })
            .done(function(data, textStatus, jqXHR) {

                // если информации нет
                if (data.length === 0) {
                    showInfo(marker, "Нет информации.");
                }
                // иначе создание списка маршрутов
                else {
                    var tooltip = "Через остановку проходят следующие маршруты"
                    var ul = "<ul>";
                    // шаблон списка маршрутов через эту остановку
                    //var template = _.template("<li><a href = '<%- id %>' target = '_blank'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");
                    var template = _.template("<li><a href = 'routes.php?id=<%- id %>' target = '_blank'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");

                    // создание списка с использованием шаблона
                    for (var i = 0, n = data.length; i < n; i++) {
                        ul += template({
                            n_marshr: data[i].n_marshr,
                            id: data[i].id,
                            type: data[i].type,
                            nach_kon: data[i].nach_kon
                        });
                    }

                    ul += "</ul>";

                    tooltip += ul;

                    showInfo(marker, tooltip);
                }
            });
    });

    // добавление созданного маркера в массив существующих маркеров
    markers.push(marker);
    if (map.getZoom() < 15) {
        for (var i = 0, n = markers.length; i < n; i++) {
            markers[i].setMap(null);
        }
    }
    // сброс указателя маркера искомого остановки
    marker_find = 0;
}



// конфигурация программы
function configure() {
    //update(n_qwery, NN_marshr);

    // update UI after map has been dragged
    //  google.maps.event.addListener(map, "dragend", function() {
    //    update(n_qwery, NN_marshr);
    //   });

    // update UI after zoom level changes
    //  google.maps.event.addListener(map, "zoom_changed", function() {
    //  update(n_qwery, NN_marshr);  //
    //  });

    // remove markers whilst dragging
    //google.maps.event.addListener(map, "dragstart", function() {
    //update(n_qwery, NN_marshr);
    //removeMarkers();  //
    //});



    google.maps.event.addListener(map, 'click', function(event) {
        //alert(event.latLng);
        var info_cord;
        var circle;

        for (var i = 0, n = circles.length; i < n; i++) {
            if (typeof { circles: i } !== 'undefined') circles[i].setMap(null);
            circles.length = 0;
            circles = [];
        }
        for (var i = 0, n = info_cords.length; i < n; i++) {
            if (typeof { info_cords: i } !== 'undefined') info_cords[i].close();
            info_cords.length = 0;
            info_cords = [];
        }

        var latlngsum = event.latLng;
        var lat_cor = event.latLng.lat();
        var lng_cor = event.latLng.lng();
        var cor45 = lat_cor + ";" + lng_cor;


       
        // флаг запроса площади окружности
        n_qwery = 4;
        NN_marshr = cor45;
        //update(n_qwery, NN_marshr)

        var parameters = {
            n_qwery: n_qwery,
            NN_marshr: NN_marshr
        };
        $.getJSON("update.php", parameters)
            .done(function(data, textStatus, jqXHR) {

                // создание списка маршрутов в радиусе 500 метров
                if (data == "") {
                    var tooltip = "В радиусе 500 метров маршруты общественниго транспорта не проходят.";
                } else {
                    var tooltip = "В радиусе 500 метров проходят следующие маршруты"
                    var ul = "<ul>";
                    // шаблон списка маршрутов
                    var template = _.template("<li><a href = 'routes.php?id=<%- id %>' target = '_blank'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");
                    // создание списка с использованием шаблона
                    for (var i = 0, n = data.length; i < n; i++) {
                        ul += template({
                            id: data[i].id,
                            type: data[i].type,
                            n_marshr: data[i].n_marshr,
                            nach_kon: data[i].nach_kon
                        });
                    }
                    ul += "</ul>";
                    tooltip += ul;

                }

                var info_cord = new google.maps.InfoWindow({
                    content: tooltip,
                    position: event.latLng
                });

                // задаём параметры окружности
                var circleOptions = {
                        center: event.latLng,
                        fillColor: "#00AAFF",
                        fillOpacity: 0.5,
                        strokeColor: "#FFAA00",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        clickable: false,
                        radius: 0.5 * 1000
                    }
                    //рисуем окружность
                circle = new google.maps.Circle(circleOptions);
                //circle.setMap(map);
                circles.push(circle);
                circle.setMap(map);
                //marker_cord.setMap(map);
                info_cords.push(info_cord);
                info_cord.open(map);

                setTimeout(function() {
                    circle.setMap(null);
                    circles.length = 0;
                    circles = [];
                    info_cord.close();
                    info_cords.length = 0;
                    info_cords = [];
                }, 5000);
                n_qwery = 1;
                NN_marshr = 0;
            })
    });


    // в зависимости от масштаба карты отображать маркеры остановок или нет
    map.addListener('zoom_changed', function() {
        if (map.getZoom() >= 15); {
            for (var i = 0, n = markers.length; i < n; i++) {
                markers[i].setMap(map);
            }
        }
        if (map.getZoom() < 15) {
            for (var i = 0, n = markers.length; i < n; i++) {
                markers[i].setMap(null);
            }
        }
    });

    //=======================================================================================================================    
    // Поиск адреса

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    //map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markersText = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markersText.forEach(function(marker) {
            marker.setMap(null);
        });
        markersText = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markersText.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}

//==========================================================================================================================    

// configure typeahead
// https://github.com/twitter/typeahead.js/blob/master/doc/jquery_typeahead.md
$("#q").typeahead({
    autoselect: true,
    highlight: true,
    minLength: 1
}, {
    source: search,
    templates: {
        empty: "остановка не найдена",
        suggestion: _.template("<p><%- stops_name %><%- id %></p>")
    }
});

// re-center map after place is selected from drop-down
$("#q").on("typeahead:selected", function(eventObject, suggestion, name) {
    title_label = suggestion.stops_name + ", " + suggestion.id;

    // ensure coordinates are numbers
    latitude = (_.isNumber(suggestion.latitude)) ? suggestion.latitude : parseFloat(suggestion.latitude);
    longitude = (_.isNumber(suggestion.longitude)) ? suggestion.longitude : parseFloat(suggestion.longitude);

    // set map's center
    map.setCenter({ lat: latitude, lng: longitude });
    map.setZoom(17);

    // удаление всех маркеров
    if (markersFind.length != 0) {
        for (var i = 0, n = markersFind.length; i < n; i++) {
            markersFind[i].setMap(null);
        }

        // обнуление указателя размера массива существующих найденных маркеров
        markersFind.length = 0;
        markersFind = [];
    }

    // устанавливаем указатель маркера искомой остановки
    marker_find = 1;
    n_qwery = 1;
    // update UI
    //removeMarkers();
    addMarker(suggestion)
        //update(n_qwery, NN_marshr);
});

// hide info window when text box has focus
$("#q").focus(function(eventData) {
    info.close();
});

// re-enable ctrl- and right-clicking (and thus Inspect Element) on Google Map
// https://chrome.google.com/webstore/detail/allow-right-click/hompjdfbfmmmgflfjdlnkohcplmboaeo?hl=en
document.addEventListener("contextmenu", function(event) {
    event.returnValue = true;
    event.stopPropagation && event.stopPropagation();
    event.cancelBubble && event.cancelBubble();
}, true);

// update UI
//update(n_qwery, NN_marshr);

// устанавливаем указатель маркера искомого города
//marker_find = 1;

// give focus to text box
$("#q").focus();
//}

/**
 * Removes markers from map.
 */
function removeMarkers() {
    // удаление всех маркеров
    if (markers.length < 480) {
        for (var i = 0, n = markers.length; i < n; i++) {
            markers[i].setMap(null);
        }

        // обнуление указателя размера массива существующих маркеров
        markers.length = 0;
        markers = [];
    }

}

/**
 * Searches database for typeahead's suggestions.
 */
function search(query, cb) {
    // get places matching query (asynchronously)
    var parameters = {
        geo: query
    };
    $.getJSON("search.php", parameters)
        .done(function(data, textStatus, jqXHR) {

            // call typeahead's callback with search results (i.e., places)
            cb(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {

            // log error to browser's console
            console.log(errorThrown.toString());
        });
}

/**
 * Shows info window at marker with content.
 */
function showInfo(marker, content) {
    // start div
    var div = "<div id='info'>";
    if (typeof(content) === "undefined") {
        // http://www.ajaxload.info/
        div += "<img alt='loading' src='img/ajax-loader.gif'/>";
    } else {
        div += content;
    }

    // end div
    div += "</div>";

    // set info window's content
    info.setContent(div);

    // open info window (if not already open)
    info.open(map, marker);
}

/**
 * Updates UI's markers.
 */
function update(n_qwery, NN_marshr) {
    // get map's bounds
    //var bounds = map.getBounds();
    //var ne = bounds.getNorthEast();
    //var sw = bounds.getSouthWest();

    //removeMarkers();
    if (markers.length < 480) {
        // get places within bounds (asynchronously)
        var parameters = {
            //ne: ne.lat() + "," + ne.lng(),
            //q: $("#q").val(),
            //sw: sw.lat() + "," + sw.lng(),
            n_qwery: n_qwery,
            NN_marshr: NN_marshr
        };
        $.getJSON("update.php", parameters)
            .done(function(data, textStatus, jqXHR) {
                // remove old markers from map
                removeMarkers();
                // add new markers to map
                for (var i = 0; i < data.length; i++) {
                    addMarker(data[i]);
                }
            })
    }
    if (line.length < 41) {
        //n_qwery = 3;
        for (i = 1; i < 42; i++) {
            NN_marshr = i; //!!!!!!!!!!!!!!!!!!!!!!!!
            array_marshr[NN_marshr] = 0;
            n_qwery = 3;
            n_stops = 0;
            draw_marshr(n_qwery, NN_marshr)
        }
        //NN_marshr == "ALL";
        //removeLine(NN_marshr);
    }

}
//}
//     .fail(function(jqXHR, textStatus, errorThrown) {
//
//         // log error to browser's console
//         console.log(errorThrown.toString());
//     });