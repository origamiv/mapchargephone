//myMap=new Object;
//myCollection = new Object;

$( document ).ready(function() 
        {
           
                $("#popen").bind("click", function(){
                    var z=$("#page1").css('left');
                    //alert(z);
                    if (z=='250px')
                    {
                    $("#page1").css('left','0px');
                    }
                    else
                    {
                    $("#page1").css('left','250px');
                    }
                });               
                
        });

        function alertObj(obj) 
        { 
            var str = ""; 
                for(k in obj) { 
                    alert(k);
                //str += k+": "+ obj[k]+"\r\n"; 
            } 
            //alert(str); 
        }        

        
        function init() 
        {
            var ipoint; 
            var mypoint1, adr;
            
            
            var geolocation = ymaps.geolocation,
            myMap = new ymaps.Map('map', 
                {
                        center: [55.7, 37.5],
                        zoom: 9,
                        controls: ['zoomControl','searchControl', 'geolocationControl'],
                        // Отменяем автоцентрирование к найденным адресам.
                        //searchControlNoCentering: true,
                        // Разрешаем кнопкам нужную длину.
                        buttonMaxWidth: 150                        
                }),
            // Создаем коллекцию.
               //myCollection = new ymaps.GeoObjectCollection(); 
            // Создаем массив с данными.
//            myPoints = [
//            { coords: [55.77, 37.46], text: 'Трактир' }            
//            ];
            
              //alert(myPoints);

    // Заполняем коллекцию данными.

        hashpoint=location.hash;
        ipoint = hashpoint.replace(/\#/, '');     
        var point = myPoints[ipoint];
        var targetCoords =point.coords;  
        //alert(point.text);
//        myCollection.add(new ymaps.Placemark(
//            point.coords, {
//            balloonContentBody: 
//            '<b>'+point.text+'</b><br>'+
//            'Адрес: '+point.address+'<br/>',
//            balloonPanelMaxMapArea: 'Infinity',
//            balloonPane:'outerBalloon'
//            
//    },
//            {
//              preset: 'islands#circleIcon',
//              iconColor: 'violet',
//              iconContent: '%'
//            }                           
//        ));
        

            myPlacemark0 = new ymaps.Placemark(point.coords, { // Создаем метку с такими координатами и суем в переменную
              hintContent: point.text,              
              balloonContentBody: [ // Содержимое баллуна
              '<table width="100%"><tr>',
              '<td><b>'+point.text+'</b></td>',
              '<td bgcolor="#F5F6CE" rowspan=3>'+'<a href="second.html#p111">Подробнее<a>'+'</td>',
              '</tr>',
                            
              '<tr><td>'+'Кафе'+'</td></tr>',
              '<tr><td>'+point.address+'</td></tr>',
              '</table>',
              ].join('')              
             }, { //Конец содержимого балуна{
//              iconLayout: 'default#image', //Свое изображение метки
//              iconImageHref: 'http://icons.iconarchive.com/icons/icons-land/vista-map-markers/256/Map-Marker-Marker-Outside-Pink-icon.png', //URL файла с меткой
              iconImageSize: [32, 31], //Размер изображения
              iconImageOffset: [-11, -31],              
              balloonPanelMaxMapArea: 'Infinity',
              balloonPane:'outerBalloon',
              preset: 'islands#circleIcon',
              iconColor: 'violet',
              
             });
        
        
        
    // Добавляем коллекцию меток на карту.
    myMap.geoObjects.add(myPlacemark0);
    
    
    
    myPlacemark0.events        
        .add('click', function (e) {
            e.get('target').options.unset('preset');
            
            clearTargetPoint();
            targetPoint = new ymaps.Placemark(e.get('coords'), e.get('iconContent') , { preset: 'islands#greenCircleIcon' });
            myMap.geoObjects.add(targetPoint);
            createRoute();
            
            
        });
        
        
        

    // Создаем экземпляр класса ymaps.control.SearchControl
    var mySearchControl = new ymaps.control.SearchControl({
        options: {
            // Заменяем стандартный провайдер данных (геокодер) нашим собственным.
            provider: new CustomSearchProvider(myPoints),
            // Не будем показывать еще одну метку при выборе результата поиска,
            // т.к. метки коллекции myCollection уже добавлены на карту.
            noPlacemark: true,
            resultsPerPage: 5
        }});

    
    
    var targetPoint = new ymaps.Placemark(targetCoords, 
              { iconContent: 'Кремль' }, { preset: 'islands#redCircleIcon' }),
           searchControl = myMap.controls.get('searchControl'), 
           
           geolocationControl = myMap.controls.get('geolocationControl'),

    // Создаём выпадающий список для выбора типа маршрута.
        routeTypeSelector = new ymaps.control.ListBox({
            data: {
                content: 'Как добраться'
            },
            items: [
                new ymaps.control.ListBoxItem('На автомобиле'),
                new ymaps.control.ListBoxItem('Общественным транспортом'),
                new ymaps.control.ListBoxItem('Пешком')
            ],
            options: {
                itemSelectOnClick: false
            }
        }),
    // Получаем прямые ссылки на пункты списка.
        autoRouteItem = routeTypeSelector.get(0),
        masstransitRouteItem = routeTypeSelector.get(1),
        pedestrianRouteItem = routeTypeSelector.get(2),

    // Метка для начальной точки маршрута.
        sourcePoint,

    // Переменные, в которых будут храниться ссылки на текущий маршрут.
        currentRoute,
        currentRoutingMode;

    // Добавляем конечную точку на карту.
    myMap.geoObjects.add(targetPoint);

    // Добавляем на карту созданный выпадающий список.
    myMap.controls.add(routeTypeSelector);
           
    // Подписываемся на события нажатия на пункты выпадающего списка.
    autoRouteItem.events.add('click', function (e) { createRoute('auto', e.get('target')); });
    masstransitRouteItem.events.add('click', function (e) { createRoute('masstransit', e.get('target')); });
    pedestrianRouteItem.events.add('click', function (e) { createRoute('pedestrian', e.get('target')); });

    // Подписываемся на события, информирующие о трёх типах выбора начальной точки маршрута:
    // клик по карте, отображение результата поиска или геолокация.
    //myMap.events.add('click', onMapClick);
    searchControl.events.add('resultshow', onSearchShow);
    geolocationControl.events.add('locationchange', onGeolocate);
    currentRoutingMode = 'auto';
    geolocationControl.events.fire('press');
    
//    geolocation.get({
//        provider: 'auto',
//        mapStateAutoApply: true
//    })
//    .then(function (result) {
//        clearSourcePoint();
//        sourcePoint = result.get('geoObjects').get(0);
//        createRoute();
//    });
    
    
    /*
     * Следующие функции реагируют на нужные события, удаляют с карты предыдущие результаты,
     * переопределяют точку отправления и инициируют перестроение маршрута.
     */

    function onMapClick (e) 
    {
        //clearSourcePoint();
//        clearTargetPoint();
//        targetPoint = new ymaps.Placemark(e.get('coords'), { iconContent: 'Cюда' }, { preset: 'islands#greenStretchyIcon' });
//        myMap.geoObjects.add(targetPoint);
//        createRoute();
    }

    function onSearchShow (e) 
    {
        clearSourcePoint(true);
        sourcePoint = searchControl.getResultsArray()[e.get('index')];
        createRoute();
    }

    function onGeolocate (e) 
    {
        clearSourcePoint();
        sourcePoint = e.get('geoObjects').get(0);
        createRoute();
    }

    function clearSourcePoint (keepSearchResult) 
    {
        if (!keepSearchResult) {
            searchControl.hideResult();
        }

        if (sourcePoint) {
            myMap.geoObjects.remove(sourcePoint);
            sourcePoint = null;
        }
    }
    
    function clearTargetPoint (keepSearchResult) 
    {
        if (!keepSearchResult) {
            searchControl.hideResult();
        }

        if (targetPoint) {
            myMap.geoObjects.remove(targetPoint);
            targetPoint = null;
        }
    }

    /*
     * Функция, создающая маршрут.
     */
    function createRoute (routingMode, targetBtn) 
    {
        // Если `routingMode` был передан, значит вызов происходит по клику на пункте выбора типа маршрута,
        // следовательно снимаем выделение с другого пункта, отмечаем текущий пункт и закрываем список.
        // В противном случае — перестраиваем уже имеющийся маршрут или ничего не делаем.
        if (routingMode) {
            if (routingMode == 'auto') {
                masstransitRouteItem.deselect();
                pedestrianRouteItem.deselect();
            } else if (routingMode == 'masstransit') {
                autoRouteItem.deselect();
                pedestrianRouteItem.deselect();
            } else if (routingMode == 'pedestrian') {
                autoRouteItem.deselect();
                masstransitRouteItem.deselect();
            }

            targetBtn.select();
            routeTypeSelector.collapse();
        } else if (currentRoutingMode) {
            routingMode = currentRoutingMode;
        } else {
            return;
        }

        // Если начальная точка маршрута еще не выбрана, ничего не делаем.
        if (!sourcePoint) {
            currentRoutingMode = routingMode;
            geolocationControl.events.fire('press');
            return;
        }

        // Стираем предыдущий маршрут.
        clearRoute();

        currentRoutingMode = routingMode;

        // Создаём маршрут нужного типа из начальной в конечную точку.
        currentRoute = new ymaps.multiRouter.MultiRoute({
            referencePoints: [sourcePoint, targetPoint],
            params: { routingMode: routingMode }
        }, {
            boundsAutoApply: true
        });

        // Добавляем маршрут на карту.
        myMap.geoObjects.add(currentRoute);
    }

    function clearRoute () 
    {
        myMap.geoObjects.remove(currentRoute);
        currentRoute = currentRoutingMode = null;
    }          
}


// Провайдер данных для элемента управления ymaps.control.SearchControl.
// Осуществляет поиск геообъектов в по массиву points.
// Реализует интерфейс IGeocodeProvider.
function CustomSearchProvider(points) {
    this.points = points;
}

// Провайдер ищет по полю text стандартным методом String.ptototype.indexOf.
CustomSearchProvider.prototype.geocode = function (request, options) {
    var deferred = new ymaps.vow.defer(),
        geoObjects = new ymaps.GeoObjectCollection(),
    // Сколько результатов нужно пропустить.
        offset = options.skip || 0,
    // Количество возвращаемых результатов.
        limit = options.results || 20;
        
    var points = [];
    // Ищем в свойстве text каждого элемента массива.
    for (var i = 0, l = this.points.length; i < l; i++) {
        var point = this.points[i];
        if (point.text.toLowerCase().indexOf(request.toLowerCase()) != -1) {
            points.push(point);
        }
    }
    // При формировании ответа можно учитывать offset и limit.
    points = points.splice(offset, limit);
    // Добавляем точки в результирующую коллекцию.
    for (var i = 0, l = points.length; i < l; i++) {
        var point = points[i],
            coords = point.coords,
                    text = point.text;

        geoObjects.add(new ymaps.Placemark(coords, {
            name: text + ' name',
            description: text + ' description',
            balloonContentBody: '<p>' + text + '</p>',
            boundedBy: [coords, coords]
        }));
    }

    deferred.resolve({
        // Геообъекты поисковой выдачи.
        geoObjects: geoObjects,
        // Метаинформация ответа.
        metaData: {
            geocoder: {
                // Строка обработанного запроса.
                request: request,
                // Количество найденных результатов.
                found: geoObjects.getLength(),
                // Количество возвращенных результатов.
                results: limit,
                // Количество пропущенных результатов.
                skip: offset
            }
        }
    });

    // Возвращаем объект-обещание.
    return deferred.promise();
};



ymaps.ready(init);