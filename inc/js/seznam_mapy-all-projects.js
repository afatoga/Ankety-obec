//var znacka = JAK.mel("div")
var obrazek = JAK.mel("img", { src: SMap.CONFIG.img + "/marker/drop-red.png" })
//znacka.appendChild(obrazek)

var popisek = JAK.mel(
  "div",
  {},
  {
    position: "absolute",
    left: "0px",
    top: "2px",
    textAlign: "center",
    width: "22px",
    color: "white",
    fontWeight: "bold",
  }
)

//popisek.innerHTML = "B"
//popisek.innerHTML = '<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke="currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\"/></svg>';
//znacka.appendChild(popisek)
var center = SMap.Coords.fromWGS84(14.4512, 50.168) //zdiby
var m = new SMap(JAK.gel("m"), center, 14)
m.addControl(new SMap.Control.Sync())
m.addDefaultLayer(SMap.DEF_BASE).enable()

var mouse = new SMap.Control.Mouse(
  SMap.MOUSE_PAN | SMap.MOUSE_WHEEL | SMap.MOUSE_ZOOM
) /* Ovládání myší */
m.addControl(mouse)

var vrstva = new SMap.Layer.Marker()
m.addLayer(vrstva)
vrstva.enable()

vm_renderMarkers()

var card = new SMap.Card()
card.getHeader().innerHTML = "<strong>Nadpis</strong>"
card.getBody().innerHTML = "Ahoj, já jsem <em>obsah vizitky</em>!"

var options = {
  title: "Dobré ráno",
}

//var marker = new SMap.Marker(m.getCenter(), "marker-01", {url:znacka});
//marker.decorate(SMap.Marker.Feature.Card, card);

//vrstva.addMarker(marker);

// function odpoved(geocoder) { /* Odpověď */
//     if (!geocoder.getResults()[0].results.length) {
//         alert("Tohle neznáme.");
//         return;
//     }

//     var vysledky = geocoder.getResults()[0].results;
//     var data = [];
//     while (vysledky.length) { /* Zobrazit všechny výsledky hledání */
//         var item = vysledky.shift()
//         data.push(item.label + " (" + item.coords.toWGS84(2).reverse().join(", ") + ")");
//     }
//     alert(data.join("\n"));
// }

// pokrocile pouziti
// document.querySelector("input.search-adv").addEventListener("click", function(e) {
// 	var geocode = new SMap.Geocoder(document.querySelector("#queryAdv").value, odpoved, {
//     // parametry pro omezeni mista - bounding box ceske republiky dle https://wiki.openstreetmap.org/wiki/WikiProject_Czech_Republic
//   	bbox: [SMap.Coords.fromWGS84(12.09, 51.06), SMap.Coords.fromWGS84(18.87, 48.55)]
//   });
// });

// markers

function vm_renderMarkers() {
  var vrstva = new SMap.Layer.Marker(
    "vm_markers-layer"
  ) /* Vrstva se značkami */
  //console.log(vrstva)
  var markersToDisplay = vm_projectList

  m.addLayer(vrstva) /* Přidat ji do mapy */
  vrstva.enable()

  //for (var i = 0, markersLength = markersToDisplay.length; i < markersLength; i++) {
  Object.keys(markersToDisplay).forEach(function (singleProjectId) {
    //console.log(markersToDisplay[singleProjectId].title);

    var options = {
      url: obrazek,
      title: markersToDisplay[singleProjectId].title,
      anchor: {
        left: 10,
        bottom: 1,
      } /* Ukotvení značky za bod uprostřed dole */,
    }

    if (!markersToDisplay[singleProjectId].coords.length) return;
    var parsed_coords = markersToDisplay[singleProjectId].coords.split(";")

    var coords = new SMap.Coords(
      parseFloat(parsed_coords[0]),
      parseFloat(parsed_coords[1])
    ).clone()

    var znacka = new SMap.Marker(
      coords,
      singleProjectId,
      options
    )
   
    //souradnice.push(c);
    //znacky.push(znacka);

    vrstva.addMarker(znacka) /* A povolit */
    console.log(vrstva)
  })
}

// window.addEventListener("load", function () {
//   vm_renderMarkers()
//})
