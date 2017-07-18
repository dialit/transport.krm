/**
 * scripts.js
 *
 * Computer Science 50
 * Problem Set 8
 *
 * Global JavaScript.
 */

// Google Map
var map;

// массив маркеров остановок
var markers = [];
// массив линий маршрутов
var line = [];
// массив преобразованных координат линий маршрута
var arr = [];
// ID номера маршрута для построения
var NN_marshr = 0;
// флаг отображения названий маркеров остановок
var n_stops = 0;
// флаг поиска 
var marker_find = 0;
// координаты инициализации карты
var latitude = 48.738795;
var longitude = 37.584883;
// 
var title_label = "Краматорск, Донецкая область";
// флаг типа запроса если 
//  1 запрос координат всех остановок
//  2 запрос координат остановок маршрута
//  3 запрос координат для построения линии маршрута
var n_qwery = 1;

// info window
var info = new google.maps.InfoWindow();

// execute when the DOM is fully loaded
$(function() 
    {
    
        // styles for map
        // https://developers.google.com/maps/documentation/javascript/styling
        var styles = [
    
            // hide Google's labels
            {
                //featureType: "all",
                //elementType: "labels",
                featureType: "administrative",
                stylers: [
                    {visibility: "off"}
                ]
            },
    
            // hide roads
            {
                featureType: "road",
                //elementType: "geometry",
                stylers: [
                    //{visibility: "off"}
                    {visibility: "on"}
                ]
            },
            {
            featureType: 'poi.business',
                stylers: [{visibility: 'off'}]
            },
            {
            featureType: 'poi.place_of_worship',
                stylers: [{visibility: 'off'}]
            },
            {
            featureType: 'poi.government',
                stylers: [{visibility: 'off'}]
            },
            {
            featureType: 'poi.medical',
                stylers: [{visibility: 'off'}]
            },
            {
            featureType: 'transit',
                stylers: [{visibility: 'on'}]
            },
            
            
            {
                featureType: 'transit',
                elementType: 'labels.icon',
                stylers: [{visibility: 'on'}]
            }
        ];
    
        // options for map
        // https://developers.google.com/maps/documentation/javascript/reference#MapOptions
        var originalMapCenter = new google.maps.LatLng( 48.738795, 37.584883);
        var options = {
            center: originalMapCenter, // Краматорск, Дон. обл.
            disableDefaultUI: true,
            //disableDefaultUI: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            maxZoom: 17,
            //panControl: true,
            styles: styles,
            zoom: 13,
            zoomControl: true
        };
    
        // get DOM node in which map will be instantiated
        var canvas = $("#map-canvas").get(0);
    
        // instantiate map
        map = new google.maps.Map(canvas, options);
    
        // configure UI once Google Map is idle (i.e., loaded)
        google.maps.event.addListenerOnce(map, "idle", configure);
        //update(n_qwery, NN_marshr);
        //map.addListener('click', addLatLng);
    });

// функция преобразования координат для построения линии маршрута
function ConvertCoordinates(data) {
            var arr = [];
            data.split(';').forEach(function(point, i, originArray) {
                var coordinates = point.substring(1, point.length-2).split(',');
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
function line_color()
    {
        var colors = ['#8dd3c7', '#ffffb3', '#bebada', '#fb8072', '#80b1d3', '#fdb462', '#b3de69', '#fccde5', '#d9d9d9', '#bc80bd', '#ccebc5', '#ffed6f',
                        '#fff7ec', '#fee8c8', '#fdd49e', '#fdbb84', '#fc8d59', '#ef6548', '#d7301f', '#b30000', '#7f0000','#67001f','#b2182b','#d6604d',
                        '#f4a582','#fddbc7','#f7f7f7','#d1e5f0','#92c5de','#4393c3','#2166ac','#053061'];
        color = colors[Math.floor(Math.random() * 32)];
        return color;
    }


// Функция отрисовки маршрута
function draw_marshr(n_qwery,NN_marshr)
    {
        //removeMarkers();
        if (NN_marshr == 0) update(n_qwery, NN_marshr);;
        // ID маршрута для построения
        //------------------------------------------------------------------------------------------------------------------------
        //var NN_marshr = 19; // ID маршрута для построения   *******************************************************************
        // ----------------------------------------------------------------------------------------------------------------------
        // скрываем все маркеры
        removeMarkers();
        //for (var i = 0, n = markers.length; i < n; i++) 
        //    {
        //        markers[i].setMap(null);
        //    }
        // флаг запроса координат линии маршрута
        n_qwery = 3;
        
        //var NN_line = line.length;
        // get map's bounds
        var bounds = map.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();
        // подготовка данных для передачи запроса в "update.php"
        var parameters = {
            ne: ne.lat() + "," + ne.lng(),
            q: $("#q").val(),
            sw: sw.lat() + "," + sw.lng(),
            n_qwery:n_qwery,
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
                        strokeWeight: 8
                    });
                routesPath.setMap(map);
                // установить карту на такие координаты
                map.setCenter(new google.maps.LatLng(48.732644,37.583284), 13);
                // после отрисовки увеличить карту
                map.setZoom(13);
                line.push(routesPath);    
            });
    
        // флаг запроса координат остановок маршрута
        n_qwery = 2;       
             
    	// запрос на получение id остановок маршрута от "update.php"
    	update(n_qwery, NN_marshr);
	
    }

// функция кнопки для проверки переключения различных параметров, можно убрать
function n_stops_chn()
    {
    if (NN_marshr==0)
        {
           //n_qwery = 2;
            for (i=0; i<41; i++)
            {    
                NN_marshr =NN_marshr +1 ;  //!!!!!!!!!!!!!!!!!!!!!!!!
                n_stops=1;
                draw_marshr(n_qwery,NN_marshr)
            }
        }
    else 
        {
            n_qwery = 1;
            NN_marshr = 0;
            n_stops = 0;
            removeLine()
        }  
    }

// Функция удаления линий маршрута
function removeLine() 
    {
        for (i=0; i<line.length; i++) 
        {                           
            line[i].setMap(null); //or line[i].setVisible(false);
        }
	    line.length = 0;
        line = [];
        // флаг запроса координат всех остановок
        n_qwery = 1;
        update(n_qwery, NN_marshr);
    }
 
 
// функция созданив маркеров остановок
function addMarker(place)
    {
        // пустой заголовок названия остановки
        var stops_name1 = "";
        // если флаг скрывать названия маркеров остановок
        if (n_stops==0)
        {
            // пустое название
            var label_stops = place.stops_name1;
        };
        // если флаг отображать названия маркеров остановок
        if (n_stops==1)
        {
            // получение названия маркера остановки
            var label_stops = place.stops_name1;
        };
        // если флаг поиска остановки
        if (marker_find==1)
        {
            // получение названия маркера остановки
            var label_stops = place.stops_name1;
        };
        // создание маркера
        // если маркер искомой остановки, то у него отличный от других маркеров значёк и анимация 
        if (marker_find == 1)
        {
        var image = {
        url: '"img/index_1.png"',
        // This marker is 20 pixels wide by 32 pixels high.
        //size: new google.maps.Size(20, 32),
        // The origin for this image is (0, 0).
        //origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 32).
        //anchor: new google.maps.Point(0, 0)
      };
        
        
        
        var marker = new MarkerWithLabel({
    	icon: "/img/marker.png",	
    	position: new google.maps.LatLng(latitude, longitude),
    	animation: google.maps.Animation.BOUNCE,
    	map: map,
    	//labelContent: place.stops_name,
    	labelContent: label_stops,
    	labelAnchor: new google.maps.Point(0, 0),
    	labelClass: "label",
        title: place.stops_name + ",  ID-" + place.id
        //title: place.id + ", " + place.latitude + ", " + place.longitude
        });
        // сброс указателя маркера искомого остановки
           marker_find==0; 
        }
        else
        //остальные маркеры остановок
        {
        var marker = new MarkerWithLabel({
    	icon: "/img/index_1.png",	
    	position: new google.maps.LatLng(place.latitude, place.longitude),
    	map: map,
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
    	n_qwery = 1;
    	// запрос на получение списка маршрутов от "articles.php"
    	$.getJSON("articles.php", {
    	    geo: place.id,
    	    //geo: place.place_name,
    	    //n_qwery:n_qwery 
    	})
    	.done(function(data, textStatus, jqXHR) 
    	{
    	    
    	    // если информации нет
    	    if (data.length === 0)
    	    {
    		showInfo(marker, "Нет информации.");
    	    }
    	    // иначе создание списка маршрутов
    	    else
    	    {
    	        var tooltip = "Через остановку проходят следующие маршруты"
    		var ul = "<ul>";	
            // шаблон списка маршрутов через эту остановку
            var template = _.template("<li><a href = '<%- id %>' target = '_blank'><%- type %> №<%- n_marshr %> (<%- nach_kon %>)</a></li>");
    		
    		// создание списка с использованием шаблона
    		for (var i = 0, n = data.length; i < n; i++)
    		{
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
        
        // сброс указателя маркера искомого остановки
        marker_find = 0;
        
    }

// конфигурация программы
function configure()
{
    //update(n_qwery, NN_marshr);
    
     // update UI after map has been dragged
        google.maps.event.addListener(map, "dragend", function() {
        update(n_qwery, NN_marshr);
        });

    // update UI after zoom level changes
        google.maps.event.addListener(map, "zoom_changed", function() {
        update(n_qwery, NN_marshr);  //
        });

    // remove markers whilst dragging
        //google.maps.event.addListener(map, "dragstart", function() {
        //update(n_qwery, NN_marshr);
        //removeMarkers();  //
        //});
    
    // в зависимости от масштаба карты отображать маркеры остановок или нет
    map.addListener('zoom_changed', function() {
          if (map.getZoom()>15);
            {
                for (var i = 0, n = markers.length; i < n; i++) 
                    {
                        markers[i].setMap(map);
                    }
            }
          if (map.getZoom()<15) 
            {
                for (var i = 0, n = markers.length; i < n; i++) 
                    {
                        markers[i].setMap(null);
                    }
            }
        });
    
    
    // configure typeahead
    // https://github.com/twitter/typeahead.js/blob/master/doc/jquery_typeahead.md
    $("#q").typeahead({
        autoselect: true,
        highlight: true,
        minLength: 1
    },
    {
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
        map.setCenter({lat: latitude, lng: longitude});
        map.setZoom(17);
        
        
        // устанавливаем указатель маркера искомой остановки
        marker_find = 1;
        n_qwery = 1;
        // update UI
        //removeMarkers();
        addMarker(place)
        update(n_qwery, NN_marshr);
    });

    // hide info window when text box has focus
    $("#q").focus(function(eventData) {
        hideInfo();
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
}

/**
 * Hides info window.
 */
function hideInfo()
{
    info.close();
}

/**
 * Removes markers from map.
 */
function removeMarkers()
{
    // удаление всех маркеров
    for (var i = 0, n = markers.length; i < n; i++) 
        {
          markers[i].setMap(null);
        }
    
    // обнуление указателя размера массива существующих маркеров
    markers.length = 0;
    markers = [];
}

/**
 * Searches database for typeahead's suggestions.
 */
function search(query, cb)
{
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
function showInfo(marker, content)
{
    // start div
    var div = "<div id='info'>";
    if (typeof(content) === "undefined")
    {
        // http://www.ajaxload.info/
        div += "<img alt='loading' src='img/ajax-loader.gif'/>";
    }
    else
    {
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
function update(n_qwery, NN_marshr) 
{
    // get map's bounds
    var bounds = map.getBounds();
    var ne = bounds.getNorthEast();
    var sw = bounds.getSouthWest();

    //removeMarkers();
    
    // get places within bounds (asynchronously)
    var parameters = {
        ne: ne.lat() + "," + ne.lng(),
        q: $("#q").val(),
        sw: sw.lat() + "," + sw.lng(),
        n_qwery:n_qwery,
        NN_marshr: NN_marshr
    };
    $.getJSON("update.php", parameters)
    .done(function(data, textStatus, jqXHR) {
        // remove old markers from map
        removeMarkers();
        
        // add new markers to map
        for (var i = 0; i < data.length; i++)
        {
            addMarker(data[i]);
        }
        
        if (map.getZoom()>15);
            {
                for (var i = 0, n = markers.length; i < n; i++) 
                    {
                        markers[i].setMap(map);
                    }
            }
          if (map.getZoom()<15) 
            {
                for (var i = 0, n = markers.length; i < n; i++) 
                    {
                        markers[i].setMap(null);
                    }
            }
     })
     .fail(function(jqXHR, textStatus, errorThrown) {

         // log error to browser's console
         console.log(errorThrown.toString());
     });
};
