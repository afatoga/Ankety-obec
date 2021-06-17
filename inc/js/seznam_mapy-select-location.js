var obrazek = "https://api.mapy.cz/img/api/marker/drop-red.png";
var center = SMap.Coords.fromWGS84(14.4512, 50.1680); //zdiby
var m = new SMap(JAK.gel("m"), center, 14);
m.addDefaultLayer(SMap.DEF_BASE).enable();
m.addDefaultControls();

// var sync = new SMap.Control.Sync(); //{bottomSpace:30}
// m.addControl(sync);

var kliknuto = function(signal) {
    var event = signal.data.event;
    var coords = SMap.Coords.fromEvent(event, m);

    //var c = SMap.Coords.fromWGS84(data[name]); /* Souřadnice značky, z textového formátu souřadnic */
    
    var options = {
        url:obrazek,
        title: 'bod',
        anchor: {left:10, bottom: 1}  /* Ukotvení značky za bod uprostřed dole */
    }
    
    var znacka = new SMap.Marker(coords, null, options);
    //souradnice.push(c);
    //znacky.push(znacka);

    var vrstva = new SMap.Layer.Marker('vm_single-point');     /* Vrstva se značkami */
    
    var previousMarkerLayer = m.getLayer('vm_single-point');
    if (previousMarkerLayer) m.removeLayer(previousMarkerLayer);

    
    m.addLayer(vrstva);                          /* Přidat ji do mapy */
    vrstva.enable();
    vrstva.addMarker(znacka);                         /* A povolit */


    //var cz = m.computeCenterZoom(souradnice); /* Spočítat pozici mapy tak, aby značky byly vidět */
    //m.setCenterZoom(cz[0], cz[1]); 

    new SMap.Geocoder.Reverse(coords, odpoved);
}

var odpoved = function(geocoder) {
    var results = geocoder.getResults();
    //alert(results.label);
    console.log(results);
    document.getElementById('vm_location_humantext').value = results.label;
    document.getElementById('vm_location_coords').value = results.coords.x+";"+results.coords.y;
}

var signals = m.getSignals();
signals.addListener(window, "map-click", kliknuto);