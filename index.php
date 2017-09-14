<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>FuelPrices</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 80%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <script>
      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };

        function getLocation() {
                navigator.geolocation.getCurrentPosition(showPosition);
        }
        

        function initMap() {
                  navigator.geolocation.getCurrentPosition(
                          function(position){ 
                              var geolocact = true;
                                        
         if(geolocact != true ) {
             var longitude = 2.346558;
             var latitude = 48.854170; }
         else {
             var longitude = position.coords.longitude;
             var latitude = position.coords.latitude; 
        }
         
        var map = new google.maps.Map(document.getElementById('map'), {
         // center: new google.maps.LatLng(47.441814, -0.500884),
          center: new google.maps.LatLng(latitude, longitude),
          zoom: 14
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('http://localhost/FuelPrices/scripts/test23.xml', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              
              //Ajout des informations personnalisée
              //Horaires :
              var ouverture = markerElem.getAttribute('ouverture');
              var fermeture = markerElem.getAttribute('fermeture');
              var saufjour = markerElem.getAttribute('saufjour');
              //Prix : 
              var gazole = markerElem.getAttribute('gazole');
              var SP95 = markerElem.getAttribute('SP95');
              var SP98 = markerElem.getAttribute('SP98');
              var GPLc = markerElem.getAttribute('GPLc');
              var E10 = markerElem.getAttribute('E10');
              var E85 = markerElem.getAttribute('E85');
              //Service à ajouter avec un foreach ? Car service1, service2, etc
              
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));
              
              //Création du titre de l'infowindow
              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = address
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              //Création du text de l'infowindow
              //Si l'horaire d'ouverture est la même que la fermeture on marque ouvert 24/24
              if(ouverture != fermeture)   
              {
                var txtheureouverture = document.createElement('text');
                txtheureouverture.textContent = 'Heure d\'ouverture : ' + ouverture 
                infowincontent.appendChild(txtheureouverture);
                infowincontent.appendChild(document.createElement('br'));

                var txtheurefermeture = document.createElement('text');
                txtheurefermeture.textContent = 'Heure de fermeture : ' + fermeture 
                infowincontent.appendChild(txtheurefermeture);
                infowincontent.appendChild(document.createElement('br'));
              }
              else
              {
                var txth24 = document.createElement('text');
                txth24.textContent = 'Ouvert 24/24h'
                infowincontent.appendChild(txth24);
                infowincontent.appendChild(document.createElement('br'));    
              }
              
              if(saufjour != "")
              {
                var txtsaufjour = document.createElement('text');
                txtsaufjour.textContent = 'Sauf jour : ' + saufjour 
                infowincontent.appendChild(txtsaufjour);
                infowincontent.appendChild(document.createElement('br'));
              }
              
              //Saut de ligne 
              infowincontent.appendChild(document.createElement('br'));
              
              //Prix : 
              if(gazole != null)
              {
                var txtgazole = document.createElement('text');
                txtgazole.textContent = 'Gazole : ' + gazole + " €" 
                infowincontent.appendChild(txtgazole);
                infowincontent.appendChild(document.createElement('br'));
               }
              
              if(SP95 != null)
              {
                var txtSP95 = document.createElement('text');
                txtSP95.textContent = 'SP95 : ' + SP95 + " €" 
                infowincontent.appendChild(txtSP95);
                infowincontent.appendChild(document.createElement('br'));
              }

              if(SP98 != null)
              {
                var txtSP98 = document.createElement('text');
                txtSP98.textContent = 'SP98 : ' + SP98 + " €" 
                infowincontent.appendChild(txtSP98);
                infowincontent.appendChild(document.createElement('br'));
              }
          
              if(GPLc != null)
              {
                var txtGPLc = document.createElement('text');
                txtGPLc.textContent = 'GPLc : ' + GPLc + " €" 
                infowincontent.appendChild(txtGPLc);
                infowincontent.appendChild(document.createElement('br'));     
              }
              
              if(E10 != null)
              {
                var txtE10 = document.createElement('text');
                txtE10.textContent = 'E10 : ' + E10 + " €" 
                infowincontent.appendChild(txtE10);
                infowincontent.appendChild(document.createElement('br'));  
              }
              
              if(E85 != null)
              {
                var txtE85 = document.createElement('text');
                txtE85.textContent = 'E85 : ' + E85 + " €" 
                infowincontent.appendChild(txtE85);
                infowincontent.appendChild(document.createElement('br'));  
              }
 
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
    });  

        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    
    
    
    
    <!--Ma clé : AIzaSyCgs8yy5q0YWauUmHH11t21tBdbIQOoDSA-->
    

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgs8yy5q0YWauUmHH11t21tBdbIQOoDSA&callback=initMap">
    </script>
  </body>
</html>