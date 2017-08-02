/**
 * scripts.js
 * Global JavaScript.
 */

// Google Map
var map;
var geocoder = new google.maps.Geocoder;
var info = new google.maps.InfoWindow();
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
// массив инфо окон маркеров остановок 
var info_stops = [];
// массив окружностей
var circles = [];
var info_cords = [];
var tooltip;
// цвет линии
var col = 0;
// ID номера маршрута для построения
var NN_marshr = 0;
// флаг отображения названий маркеров остановок
var n_stops = 0;
// флаг поиска остановки
var marker_find = 0;
// массив линий маршрута
var array_marshr = [];
// координаты линии маршрута
var routesPath;
// координаты инициализации карты
//var latitude = 48.738795;
//var longitude = 37.584883;
// 
var title_label = "Краматорск, Донецкая область";
// флаг удаления линии маршрута
var del_line;
// флаг типа запроса к UPDATE если 
//  1 запрос координат всех остановок
//  2 запрос координат остановок маршрута
//  3 запрос координат для построения линии маршрута
//  5 запрос координат остановки из мод окна
var n_qwery = 1;
// флаг типа запроса к ARTICLES если 
// - 1 запрос списка маршрутов через остановку
// - 2 запрос информации о маршруте
var n_qwery1 = 1;


$(function() {

    // styles for map
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
    var originalMapCenter = new google.maps.LatLng(48.738795, 37.584883);
    var options = {
        center: originalMapCenter, // Краматорск, Дон. обл.
        disableDefaultUI: true,
        //disableDefaultUI: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        fullscreenControl: true,
        minZoom: 12,
        maxZoom: 18,
        //panControl: true,
        styles: styles,
        zoom: 15,
        zoomControl: true
    };

    var canvas = $("#map-canvas").get(0);
    map = new google.maps.Map(canvas, options);
    
    var icons = {
          train: {
            icon: "img/train.png"
          },
          bus: {
            icon: "img/bus.png"
          },
          central: {
            icon: "img/gerb.png"
          }
        };
    
    var features = [
          {
            position: new google.maps.LatLng(48.726006,37.543142),
            type: 'train'
          }, {
            position: new google.maps.LatLng(48.735834,37.576244),
            type: 'bus'
          }, {
            position: new google.maps.LatLng(48.73875,37.584969),
            type: 'central'
          }
        ];
    
    var label = {
            train: {
            labelContent: "Железнодорожный вокзал",
            },
            bus: {
            labelContent: "Автовокзал",
            },
            central: {
            labelContent: "Центральная площадь",
            }
        };
        // labelContent: label_stops,
        //     labelAnchor: new google.maps.Point(0, 0),
        //     labelClass: "label",
        //     title: place.stops_name

        // Create markers.
        features.forEach(function(feature) {
          var markerPRIME = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            labelContent: label[feature.type].labelContent,
            labelAnchor: new google.maps.Point(0, 0),
            labelClass: "label",
            title: label[feature.type].labelContent,
            map: map
          });
        });





    
    
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
            var infoGeo = new google.maps.InfoWindow({ map: map });
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
    return arr;
}

// генератор псевдослучайного цвета линии маршрута
function line_color() {
    var colors = ['#000000', '#0000ee', '#ee3b3b', '#458b00', '990099', '#b23aee', '#1874cd', '#ee2c2c', '#ee3a8c', '#7a67ee'];
    col = col + 1;
    if (col == 11) col = 1;
    color = colors[col];
    return color;
}

// Функция отрисовки маршрута
function draw_marshr(n_qwery, NN_marshr) {
    $('#myModalBox').hide();
    // ID маршрута для построения
    if (array_marshr[NN_marshr] == 2) del_line = 1;
    if (array_marshr[NN_marshr] == 1) {
        line[NN_marshr].setMap(map);
        array_marshr[NN_marshr] = 2;
        map.setCenter(new google.maps.LatLng(48.732644, 37.583284), 13);
        map.setZoom(13);
    }
    // добавление линии
    if (n_qwery == 3 && array_marshr[NN_marshr] == 0) {
        var parameters = {
            n_qwery: n_qwery,
            NN_marshr: NN_marshr
        };
        $.getJSON("update.php", parameters)
            .done(function(data, textStatus, jqXHR) {
                var routesPath = new google.maps.Polyline({
                    path: ConvertCoordinates(data),
                    strokeColor: line_color(), //"#FFF000",
                    strokeOpacity: 0.5,
                    strokeWeight: 5
                });
                //line.push(routesPath);
                line[NN_marshr] = routesPath;
                line[NN_marshr].setMap(null);
                //line[NN_marshr].setVisible(false);
                array_marshr[NN_marshr] = 1;
                // map.setCenter(new google.maps.LatLng(48.732644, 37.583284), 13);
                // map.setZoom(13);
                n_qwery1 = 2;
                $.getJSON("articles.php", {
                        geo: NN_marshr,
                        n_qwery1: n_qwery1
                    })
                    .done(function(data, textStatus, jqXHR) {
                        if (data.length === 0) {
                            showInfo(marker, "Нет информации.");
                        } else {
                            var tooltip = " "
                            var ul = "<ul>";
                            var template = _.template("<li><a route-id=<%- id %>' onclick='showModalRoute(this)'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");
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
    } else {
        // спрятать линию
        if (del_line == 1) {
            array_marshr[NN_marshr] = 1;
            line[NN_marshr].setMap(null);
            del_line = 0;
        }
    }
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
        }, 10000);
    });
}


// функция кнопки для скрытия всех линий
function n_stops_chn() {
    removeLine();
    removeMarkersFind();
    map.setCenter(new google.maps.LatLng(48.732644, 37.583284), 13);
    map.setZoom(14);
}

// // Функция удаления линий маршрута
function removeLine(NN_marshr) {
    if (NN_marshr == "ALL") {
        for (i = 1; i < line.length; i++) {
            line[i].setMap(null);
            array_marshr[i] = 2;
        }
        NN_marshr = 0;
        n_qwery = 1;
    } else {
        for (i = 1; i < line.length; i++) {
            line[i].setMap(null);
            array_marshr[i] = 1;
        }
        NN_marshr = 0;
        n_qwery = 1;
    }
}

// функция созданив маркеров остановок
function addMarker(place) {
    // пустой заголовок названия остановки
    var stops_name1 = "";
    var label_stops = stops_name1;
    // создание маркера
    // если маркер искомой остановки, то у него отличный от других маркеров значёк и анимация 
    if (marker_find == 1 || n_qwery == 5) {
        // removeMarkersFind();
        var markerFind = new google.maps.Marker({
            icon: "/img/marker.png",
            position: new google.maps.LatLng(place.latitude, place.longitude),
            animation: google.maps.Animation.BOUNCE,
            map: map,
            //labelContent: place.stops_name,
            labelContent: label_stops,
            labelAnchor: new google.maps.Point(0, 0),
            labelClass: "label",
            title: place.stops_name
        });
        markersFind.push(markerFind);
        markerFind.setMap(map);
        map.setCenter(new google.maps.LatLng(place.latitude, place.longitude), 17);
        map.setZoom(16);
        $('#myModalBox').hide();
        //marker_find == 0;
        n_qwery = 1;
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
            title: place.stops_name
        });
    };
    if (marker_find == 0){
    google.maps.event.addListener(marker, "click", function() {
        n_qwery1 = 1;
        $.getJSON("articles.php", {
                geo: place.id,
                n_qwery1: n_qwery1
            })
            .done(function(data, textStatus, jqXHR) {
                if (data.length === 0) {
                    showInfo(marker, "Нет информации.");
                } else {
                    var tooltip = "Через остановку проходят следующие маршруты"
                    var ul = "<ul>";
                    var template = _.template("<li><a route-id=<%- id %>' onclick='showModalRoute(this)'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");
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
    }
    markers.push(marker);
    if (map.getZoom() < 16) {
        for (var i = 0, n = markers.length; i < n; i++) {
            markers[i].setMap(null);
        }
    }
    marker_find = 0;
}
//=====================================================================================================================
// очистка модального окна после закрытия
$('body').on('hidden.bs.modal', '.modal', function() {
    $(this).removeData('bs.modal');
});
// два состояния у кнопок маршрутов
$(document).ready(function() {
    $("#ButtonsNmarshr .btn").click(function() {
        $(this).button('toggle');
    });

    $('a#buttonReset').click(function() {
        $("#ButtonsNmarshr .btn").removeClass('active');
    });
});
//==========================================================================================================================
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


    // при клике по карте рисуется окружность и запрос к базе о маршрутах в радиусе 500 метров
    google.maps.event.addListener(map, 'click', function(event) {
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
        n_qwery = 4;
        NN_marshr = cor45;
        var parameters = {
            n_qwery: n_qwery,
            NN_marshr: NN_marshr
        };
        $.getJSON("update.php", parameters)
            .done(function(data, textStatus, jqXHR) {
                if (data == "") {
                    var tooltip = "В радиусе 500 метров маршруты общественниго транспорта не проходят.";
                } else {
                    var tooltip = "В радиусе 500 метров проходят следующие маршруты"
                    var ul = "<ul>";
                    var template = _.template("<li><a route-id=<%- id %>' onclick='showModalRoute(this)'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");
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
                circle = new google.maps.Circle(circleOptions);
                circles.push(circle);
                circle.setMap(map);
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
        if (map.getZoom() >= 16); {
            for (var i = 0, n = markers.length; i < n; i++) {
                markers[i].setMap(map);
            }
        }
        if (map.getZoom() < 16) {
            for (var i = 0, n = markers.length; i < n; i++) {
                markers[i].setMap(null);
            }
        }
    });

    //=======================================================================================================================    
    // Поиск адреса
    var input = /** @type {!HTMLInputElement} */ (
        document.getElementById('pac-input'));

    var defaultBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(48.654686, 37.392998),
        new google.maps.LatLng(48.816359, 37.694092));

    var types = document.getElementById('type-selector');
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
    var options = {
        bounds: defaultBounds,
        types: ['address'],
        componentRestrictions: { country: 'ukr' }
    };

    autocomplete = new google.maps.places.Autocomplete(input, options);

    var infowindow = new google.maps.InfoWindow();
    var markerPlace = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        markerPlace.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Нет информации по запросу: '" + place.name + "'");
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(16);
        }
        markerPlace.setIcon( /** @type {google.maps.Icon} */ ({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        markerPlace.setPosition(place.geometry.location);
        markerPlace.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, markerPlace);
    });

}
//==========================================================================================================================    

$("#q").typeahead({
    autoselect: true,
    highlight: true,
    minLength: 2
}, {
    source: search,
    templates: {
        empty: "остановка не найдена",
        suggestion: _.template("<p><%- stops_name %><%- id %></p>")
    }
});
$("#q").on("typeahead:selected", function(eventObject, suggestion, name) {
    title_label = suggestion.stops_name + ", " + suggestion.id;
    marker_find = 1;
    n_qwery = 1;
    addMarker(suggestion)
});
$("#q").focus(function(eventData) {
    hideInfo();
});
document.addEventListener("contextmenu", function(event) {
    event.returnValue = true;
    event.stopPropagation && event.stopPropagation();
    event.cancelBubble && event.cancelBubble();
}, true);
$("#q").focus();

// Удаление маркеров остановок
function removeMarkers() {
    for (var i = 0, n = markers.length; i < n; i++) {
        markers[i].setMap(null);
        markers.length = 0;
        markers = [];
    }

}
// удаление маркеров найденых остановок
function removeMarkersFind() {
    infowindow.close();
    markerPlace.setVisible(false);
    if (markersFind.length != 0) {
        for (var i = 0, n = markersFind.length; i < n; i++) {
            markersFind[i].setMap(null);
        }
        markersFind.length = 0;
        markersFind = [];
    }

}
// поиск typeahead
function search(query, cb) {
    var parameters = {
        geo: query
    };
    $.getJSON("search.php", parameters)
        .done(function(data, textStatus, jqXHR) {
            cb(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
}

// функция скрытия инфо окон остановок
function hideInfo() {
    for (var i = 0, n = info_stops.length; i < n; i++) {
        info_stops[i].close();
        // обнуление указателя размера массива существующих маркеров
        info_stops.length = 0;
        info_stops = [];
    }
    //info.close();
}

// наполнение инфо окна остановки
function showInfo(marker, content) {
    hideInfo();
    var div = "<div id='info'>";
    if (typeof(content) === "undefined") {
        div += "<img alt='loading' src='img/ajax-loader.gif'/>";
    } else {
        div += content;
    }
    div += "</div>";
    var info = new google.maps.InfoWindow({
        content: (div),
        disableAutoPan: true
    });
    info.setContent(div);
    info_stops.push(info)
    info.open(map, marker);
}

// функция обновления остановок
function update(n_qwery, NN_marshr) {
    // get map's bounds
    //var bounds = map.getBounds();
    //var ne = bounds.getNorthEast();
    //var sw = bounds.getSouthWest();
    if (markers.length < 480 || n_qwery == 5) {
        var parameters = {
            //ne: ne.lat() + "," + ne.lng(),
            //q: $("#q").val(),
            //sw: sw.lat() + "," + sw.lng(),
            n_qwery: n_qwery,
            NN_marshr: NN_marshr
        };
        $.getJSON("update.php", parameters)
            .done(function(data, textStatus, jqXHR) {
                if (n_qwery == 5) marker_find = 1;
                if (n_qwery != 5) removeMarkers();
                for (var i = 0; i < data.length; i++) {
                    addMarker(data[i]);
                }
            })
    }
    if (line.length < 41) {
        for (i = 1; i < 42; i++) {
            NN_marshr = i;
            array_marshr[NN_marshr] = 0;
            n_qwery = 3;
            n_stops = 0;
            draw_marshr(n_qwery, NN_marshr)
        }
    }

}

function showModalRoute(element) {

    //вытаскиваем данные об ID маршрута
    var id = element.getAttribute('route-id');

    //находим элемент, который будет содержать информацию
    var divContent = $("#routeInfoContent");

    //формируем ссылку на страницу с информацией
    var url = "routes.php?id=" + id;

    //инициируем загрузку страницы в элемент
    divContent.load(url);

    //вызываем модальное окно
    $("#myModalBox").modal("toggle");
}

//}
//     .fail(function(jqXHR, textStatus, errorThrown) {
//
//         // log error to browser's console
//         console.log(errorThrown.toString());
//     });