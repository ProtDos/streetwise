<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Street Game</title>

    <link rel="stylesheet" href="style2.css">
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/latest/maptiler-sdk.umd.min.js"></script>
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/latest/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
          margin: 0;
            width: 100%;
        }
        #map {
          position: absolute;
          top: 0;
          /*bottom: 0;*/
          width: 100%;
        }
    </style>
</head>

<body>
    <div id="map" style="position:absolute; z-index: -1; height: 80%"></div>

    <style>
        .reload {
            position: absolute;
            bottom: 25%;
            left: 95%;
            width: 50px;
            height: 50px;
            font-size: 36px;
            text-align: center;
            line-height: 50px;
            background-color: transparent;
            border-radius: 50%;
            border: 2px solid #000;
            cursor: pointer;
          }
    </style>

    <span class="reload" onclick="newstreet()">&#x21bb;</span>
    
    <style>
        .time {
            position: absolute;
            top: 2%;
            left: 95%;
            font-size: 36px;
            text-align: center;
            line-height: 50px;
            background-color: transparent;
            border-radius: 15%;
            border: 2px solid #000;
            cursor: pointer;
            padding: 5px;
          }
    </style>

    <p class="time" id="time">20s</p>

    <div id="v1">
        <?php if (isset($user)): ?>
            <button disabled id="button3" style="position: absolute; bottom: 8%; left: 15%;" onclick="getsolution2()">Guess</button>
        <?php else: ?>
            <button disabled id="button3" style="position: absolute; bottom: 8%; left: 15%;" onclick="getsolution()">Guess</button>
        <?php endif; ?>

        <style>
            #button3 {
                background-color: #4CAF50;
                border-radius: 20px;
                border: none;
                color: white;
                font-size: 16px;
                height: 50px;
                width: 70%;
            }
        </style>
    </div>
    <div id="v2" style="visibility: hidden;">
        <div id="entfernung" style="position: absolute; bottom: 8%; left: 5%;">0 m</div>
        <button id="button3" style="position: absolute; bottom: 8%; left: 15%;" onclick="newstreet()">NEXT MISSION</button>
        <div id="score" style="position: absolute; bottom: 8%; right: 5%;">0 P.</div>

        <style>
            #button3 {
                background-color: #4CAF50;
                border-radius: 20px;
                border: none;
                color: white;
                font-size: 16px;
                height: 50px;
                width: 70%;
            }

            #entfernung {
                color: white;
                font-size: 36px;
            }

            #score {
                color: white;
                font-size: 36px;
            }
        </style>
    </div>

    <div style="position:relative; text-align: center; padding-top: 20px;">
        <div id="div1"></div>
    </div>


</body>
    
<script type="text/javascript">
    var my_var = <?php echo json_encode($user); ?>;
    console.log(my_var);
</script>

    
<script>
    let countdown = 20;
    
    function timer(){
        let countdown2 = 20;
        const countdownElement = document.getElementById('time');
        const buttonElement = document.getElementById("button3");

        let countdownInterval = setInterval(() => {
          countdown2--;
          countdownElement.innerHTML = `${countdown2}s`;
            
            countdown = countdown2;

          if (countdown2 === 0) {
            clearInterval(countdownInterval);
            console.log('You lose');
          }
        }, 1000);

        buttonElement.addEventListener('click', () => {
          clearInterval(countdownInterval);
          console.log('You win');
        });
    }

</script>

<script>
    let streets = ["Abbioweg", "Abekenstr.", "Ackerstr.", "Adam-Stegerwald-Str.", "Adolf-Damaschke-Weg", "Adolf-Köhne-Str.", "Adolf-Reichwein-Platz", "Adolf-Staperfeld-Str.", "Adolfstr.", "Agnesstr.", "Ahornstr.", "Akazienstr.", "Akeleiweg", "Akyürekplatz", "Albert-Brickwedde-Str.", "Albert-Einstein-Str.", "Albert-Schweitzer-Str.", "Albertstr.", "Albrecht-Dürer-Str.", "Albrechtstr.", "Alfred-Delp-Str.", "Alfred-Döblin-Str.", "Alfred-Mithöfer-Str.", "Allensteiner Str.", "Alte Bauernschaft", "Alte Münze", "Alte Poststr.", "Alte Pyer Schule", "Alte Vogtei", "Alte-Synagogen-Str.", "Altehageweg", "Altenburger Str.", "Altes Depot", "Am Armenholz", "Am Belfastpark", "Am Boddenkamp", "Am Boekenhagen", "Am Bürgerpark", "Am Ehrenmal", "Am Eversburger Bahnhof", "Am Fernblick", "Am Finkenhügel", "Am Forellenteich", "Am Freibad", "Am Funkturm", "Am Galgesch", "Am Gertrudenberg", "Am Gesmoldsberg", "Am Gretescher Turm", "Am Grewenkamp", "Am Gut Sandfort", "Am Hallenbad", "Am Hang", "Am Hasenbrink", "Am Haseschacht", "Am Haster Berg", "Am Haunhorst", "Am Heger Holz", "Am Heger Turm", "Am Heidekotten", "Am Hirtenhaus", "Am Huxmühlenbach", "Am Hügel", "Am Kalkhügel", "Am Kalverkamp", "Am Kanal", "Am Kirchenkamp", "Am Klosterkotten", "Am Kniebusch", "Am Knochenhof", "Am Kronenpohl", "Am Krummen Kamp", "Am Krähenhügel", "Am Krümpel", "Am Königshügel", "Am Landgericht", "Am Ledenhof", "Am Limberg", "Am Lindlager Berg", "Am Lünsebrink", "Am Mahlstein", "Am Mühlengarten", "Am Mühlenholz", "Am Mühlenkamp", "Am Nahner Friedhof", "Am Nahner Holz", "Am Nahner Turm", "Am Natruper Holz", "Am Natruper Steinbruch", "Am Osteresch", "Am Pappelgraben", "Am Piesberg", "Am Pyer Ding", "Am Riedenbach", "Am Riegelbusch", "Am Roten Berg", "Am Röthebach", "Am Salzmarkt", "Am Schellenkamp", "Am Schwanenbach"];
    
    var q = (location.search || '').substr(1).split('&');
    var useRetina = window.devicePixelRatio > 1;
    var useVector = q.includes('vector') || !q.includes('raster');
    var style = useVector ? 'https://api.maptiler.com/maps/12e31861-9a3c-445d-ab81-4c421a1de8d1/style.json?key=FG7fQWc0fb55ZaWTUKKF' : {
        version: 8,
        sources: {
          'raster-tiles': {
            type: 'raster',
            tiles: [
              useRetina ? 'https://api.maptiler.com/maps/12e31861-9a3c-445d-ab81-4c421a1de8d1/{z}/{x}/{y}@2x.png?key=FG7fQWc0fb55ZaWTUKKF' : 'https://api.maptiler.com/maps/12e31861-9a3c-445d-ab81-4c421a1de8d1/{z}/{x}/{y}.png?key=FG7fQWc0fb55ZaWTUKKF'
            ],
            tileSize: 512,
            attribution: ''
          }
        },
        layers: [{
          id: 'raster-layer',
          type: 'raster',
          source: 'raster-tiles',
          minzoom: 0,
          maxzoom: 22,
        }],
        center: [52.283, 8.05],
        zoom: 13
      };
    
    maptilersdk.config.apiKey = 'FG7fQWc0fb55ZaWTUKKF';
    
    const map = new maptilersdk.Map({
      container: 'map', 
      style: style,
      center: [8.05, 52.283],
      zoom: 13
    });

    let specifiedMarker = null;

    var polyline;
    var distanceLabel;

    var lat2, lon2;
    var lat, lon;

    var e;

    let marker = null;
    
    let did = null;
    
    let solved = null;
    

    function getCoordinates(streetName) {
        
      $.ajax({
        url: "https://nominatim.openstreetmap.org/search",
        type: "GET",
        data: {
          q: streetName,
          format: "json",
        },
        success: function(data) {
          if (data.length > 0) {
            lat2 = data[0].lat;
            lon2 = data[0].lon;
          } else {
            lat2 = null;
            lon2 = null;
          }
        },
        error: function(error) {
          console.log("Error: " + error);
          lat2 = null;
          lon2 = null;
        }
      });
    }

    function newstreet(){
        let countdown = 20;
        timer();
        map.setStyle(style);
        
        solved = null;
        document.getElementById("v1").style.visibility = "visible"; 
        document.getElementById("v2").style.visibility = "hidden"; 
        

        if (marker !== null) {
            marker.remove();
          }
        
        
        
        if (specifiedMarker !== null) {
            specifiedMarker.remove();
            specifiedMarker = null;
          }
        
        if (did !== null) {
            map.removeLayer("route2");
            did = null;
        }
        
        let random = streets[Math.floor(Math.random() * streets.length)];
        console.log(random);

        getCoordinates(random + ", Osnabrück");

        setTimeout(function() {
          if (lat2 && lon2) {
          } else {
          }
        }, 1000);

        document.getElementById("div1").innerHTML = random;  
        console.log("Coordinates: " + lat2 + ", " + lon2);

        map.on('click', function(event) {
            console.log("Solved: " + solved);
            
            e = event;
          const { lng, lat } = event.lngLat;
            
            
            const myButton = document.getElementById("button3");
            myButton.disabled = false;
          
            
            if (solved == null) {
                if (marker !== null) {
                    marker.remove();
                    marker = null;
                  }
            }
            if (solved == null) {
              marker = new maptilersdk.Marker({
                draggable: false
              }).setLngLat([lng, lat])
              .addTo(map);
            }

        });


    }

    function getsolution(){  
        
        document.getElementById("v1").style.visibility = "hidden"; 
        document.getElementById("v2").style.visibility = "visible"; 
        
        solved = "etwas";
        
        if (specifiedMarker) {
            map.removeLayer(specifiedMarker);
        }
            if (polyline) {
            map.removeLayer(polyline);
        }
            if (distanceLabel){
                map.removeLayer(distanceLabel);
            }

        
        if (specifiedMarker !== null) {
            marker.remove();
            specifiedMarker = null;
          }
        
        console.log(did);
        
        if (did !== null) {
            map.removeLayer("route2");
            did = null;
        }
        
        if (map.getLayer('route2')) map.removeLayer('route2');

          specifiedMarker = new maptilersdk.Marker({
            draggable: false
          }).setLngLat([lon2, lat2])
          .addTo(map);

        
        
        const { lng, lat } = e.lngLat;
        console.log(lat, lng, lon2, lat2);
        
        var distance = e.lngLat.distanceTo(specifiedMarker.getLngLat()).toFixed(2);
        
        var geometry = {
            'type': 'LineString',
            'coordinates': [
                [lng, lat],
                [lon2, lat2]
            ]
        };
        
        did = "test";
        
        console.log((Number(lng)+Number(lon2))/2);
        console.log((Number(lat)+Number(lat2))/2);
        
        map.flyTo({
            center: [(Number(lng)+Number(lon2))/2, (Number(lat)+Number(lat2))/2],
            essential: true
        });
        
        
        map.fitBounds([
            [lng, lat],
            [lon2, lat2]
        ], {
            padding: {
                top: 50,
                bottom: 50,
                left: 50,
                right: 50
            }
        });
        
        const obj = document.getElementById("entfernung");
        animateValue(obj, 0, distance, 1000);
        
        const obj2 = document.getElementById("score");
        animateValue2(obj2, 0, getScore(distance), 1000);
        
        map.setStyle();
                
        load_it();
               


    }  
    
    function getsolution2(){  
        
        document.getElementById("v1").style.visibility = "hidden"; 
        document.getElementById("v2").style.visibility = "visible"; 
        
        solved = "etwas";
        
        if (specifiedMarker) {
            map.removeLayer(specifiedMarker);
        }
            if (polyline) {
            map.removeLayer(polyline);
        }
            if (distanceLabel){
                map.removeLayer(distanceLabel);
            }

        
        if (specifiedMarker !== null) {
            marker.remove();
            specifiedMarker = null;
          }
        
        console.log(did);
        
        if (did !== null) {
            map.removeLayer("route2");
            did = null;
        }
        
        if (map.getLayer('route2')) map.removeLayer('route2');

          specifiedMarker = new maptilersdk.Marker({
            draggable: false
          }).setLngLat([lon2, lat2])
          .addTo(map);

        
        
        const { lng, lat } = e.lngLat;
        console.log(lat, lng, lon2, lat2);
        
        var distance = e.lngLat.distanceTo(specifiedMarker.getLngLat()).toFixed(2);
        
        var geometry = {
            'type': 'LineString',
            'coordinates': [
                [lng, lat],
                [lon2, lat2]
            ]
        };
        
        did = "test";
        
        console.log((Number(lng)+Number(lon2))/2);
        console.log((Number(lat)+Number(lat2))/2);
        
        map.flyTo({
            center: [(Number(lng)+Number(lon2))/2, (Number(lat)+Number(lat2))/2],
            essential: true
        });
        
        
        map.fitBounds([
            [lng, lat],
            [lon2, lat2]
        ], {
            padding: {
                top: 50,
                bottom: 50,
                left: 50,
                right: 50
            }
        });
        
        var vv = getScore(distance);
        console.log(vv);
        
        const obj = document.getElementById("entfernung");
        animateValue(obj, 0, distance, 1000);
        
        const obj2 = document.getElementById("score");
        animateValue2(obj2, 0, vv, 1000);
        
        map.setStyle();
                
        load_it();

        
        var data = {
            name: my_var["name"],
            score: vv,
            time: countdown
        };
        
        $.ajax({
            type: "POST",
            url: "send_data.php",
            data: data,
            success: function(response) {
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
               


    }   
    
    
    function animateValue(obj, start, end, duration) {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start) + " m";
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    }
    
    function animateValue2(obj, start, end, duration) {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start) + " P.";
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    }
    
    function getScore(Entfernung) {
        const Faktor = 1;
        const maximalPunkte = 5000;
        const punkte = Math.max(0, maximalPunkte - (Entfernung * Faktor));
        console.log(punkte);
        return punkte;
    }
    
    function load_it(){
        setTimeout(() => {
            const { lng, lat } = e.lngLat;
            console.log(lat, lng, lon2, lat2);

            var distance = e.lngLat.distanceTo(specifiedMarker.getLngLat()).toFixed(2);

            var geometry = {
                'type': 'LineString',
                'coordinates': [
                    [lng, lat],
                    [lon2, lat2]
                ]
            };

            map.addLayer({
                'id': 'route2',
                'type': 'line',
                'source': {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'properties': {},
                        'geometry': geometry
                    }
                },
                'layout': { 'line-cap': 'round' },
                'paint': {
                    'line-color': '#007296',
                    'line-width': 4,
                    "line-opacity": 1
                }
            });
        }, 1000);
        
    }

    
    newstreet();
    
    

    
    
</script>

</html>