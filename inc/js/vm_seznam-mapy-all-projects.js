function isEmpty(obj) {
  for (var prop in obj) {
    if (obj.hasOwnProperty(prop)) {
      return false
    }
  }

  return JSON.stringify(obj) === JSON.stringify({})
}

function createCardToMarker(title, slug) {
  var c = new SMap.Card();
c.setSize(240, 160); /* Šířka, výška */

c.getHeader().innerHTML = title;
//c.getFooter().innerHTML = "Toto je zápatí vizitky.";

c.getBody().style.margin = "5px 0px";
c.getBody().style.backgroundColor = "#ebebeb";
c.getBody().innerHTML = "<a href='/navrh/" + slug + "' target='_blank'>"+window.location.hostname+"/navrh/"+slug+"</a>"; 

return c;
}

var center = SMap.Coords.fromWGS84(14.4512, 50.168) //zdiby
var m = new SMap(JAK.gel("m"), center, 14)
m.addControl(new SMap.Control.Sync())
m.addDefaultLayer(SMap.DEF_BASE).enable()

var mouse = new SMap.Control.Mouse(
  SMap.MOUSE_PAN | SMap.MOUSE_WHEEL | SMap.MOUSE_ZOOM
) /* Ovládání myší */
m.addControl(mouse)

var znacky = []
var souradnice = []

var vrstva = new SMap.Layer.Marker() /* Vrstva se značkami */
m.addLayer(vrstva) /* Přidat ji do mapy */
vrstva.enable() /* A povolit */

if (typeof vm_projectList !== "undefined" && !isEmpty(vm_projectList)) {
  var markersToDisplay = vm_projectList
  Object.keys(markersToDisplay).forEach(function (singleProjectId) {
    var obrazek = JAK.mel("img", {
      src: SMap.CONFIG.img + "/marker/drop-red.png",
    })
    var options = {
      url: obrazek,
      title: markersToDisplay[singleProjectId].title,
      anchor: {
        left: 10,
        bottom: 1,
      } /* Ukotvení značky za bod uprostřed dole */,
    }

    if (!markersToDisplay[singleProjectId].coords.length) return
    var parsed_coords = markersToDisplay[singleProjectId].coords.split(";")

    var coords = new SMap.Coords(
      parseFloat(parsed_coords[0]),
      parseFloat(parsed_coords[1])
    ).clone()

    var znacka = new SMap.Marker(coords, singleProjectId, options)
    var karta = createCardToMarker(markersToDisplay[singleProjectId].title, markersToDisplay[singleProjectId].slug)

    znacka.decorate(SMap.Marker.Feature.Card, karta);

    souradnice.push(coords)
    //znacky.push(znacka)
    vrstva.addMarker(znacka)
  })

  // for (var i = 0; i < znacky.length; i++) {
  // }

  if (souradnice.length) {
    var cz = m.computeCenterZoom(
      souradnice
    ) /* Spočítat pozici mapy tak, aby značky byly vidět */
    m.setCenterZoom(cz[0], cz[1])
  }
  m.setZoom(14)
}

// window.addEventListener("load", function () {
//   vm_renderMarkers()
// })
