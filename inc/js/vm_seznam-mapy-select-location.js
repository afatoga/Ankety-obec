var obrazek = "https://api.mapy.cz/img/api/marker/drop-red.png"
var center = SMap.Coords.fromWGS84(14.4512, 50.168) //zdiby
var m = new SMap(JAK.gel("m"), center, 14)
m.addDefaultLayer(SMap.DEF_BASE).enable()
m.addDefaultControls()

function vm_renderMarkerLayer(coords) {
  var options = {
    url: obrazek,
    title: "bod",
    anchor: { left: 10, bottom: 1 } /* Ukotvení značky za bod uprostřed dole */,
  }

  coords = new SMap.Coords(coords["x"], coords["y"]).clone()

  var znacka = new SMap.Marker(coords, "vm_marker-badge", options)
  //souradnice.push(c);
  //znacky.push(znacka);

  var vrstva = new SMap.Layer.Marker("vm_marker-layer") /* Vrstva se značkami */
  //console.log(vrstva)
  var previousMarkerLayer = m.getLayer("vm_marker-layer")
  if (previousMarkerLayer) m.removeLayer(previousMarkerLayer)

  m.addLayer(vrstva) /* Přidat ji do mapy */
  vrstva.enable()
  vrstva.addMarker(znacka) /* A povolit */
}

// var sync = new SMap.Control.Sync(); //{bottomSpace:30}
// m.addControl(sync);
window.addEventListener("load", function () {
  var saved_coords = document.getElementById("vm_location_coords").value
  if (saved_coords.length) {
    var parsed_coords = saved_coords.split(";")
    parsed_coords = {
      x: parseFloat(parsed_coords[0]),
      y: parseFloat(parsed_coords[1]),
    }
    //console.log(parsed_coords)
    vm_renderMarkerLayer(parsed_coords)
  }
})

var kliknuto = function (signal) {
  var event = signal.data.event
  var coords = SMap.Coords.fromEvent(event, m)

  //var c = SMap.Coords.fromWGS84(data[name]); /* Souřadnice značky, z textového formátu souřadnic */

  vm_renderMarkerLayer(coords)

  //var cz = m.computeCenterZoom(souradnice); /* Spočítat pozici mapy tak, aby značky byly vidět */
  //m.setCenterZoom(cz[0], cz[1]);

  new SMap.Geocoder.Reverse(coords, odpoved)
}

var odpoved = function (geocoder) {
  var results = geocoder.getResults()
  //alert(results.label);

  document.getElementById("vm_location_humantext").value = results.label
  document.getElementById("vm_location_coords").value =
    results.coords.x + ";" + results.coords.y
}

var signals = m.getSignals()
signals.addListener(window, "map-click", kliknuto)
