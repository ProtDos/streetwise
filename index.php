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

    <title>StreetWise</title>

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
            filter: brightness(60%);
            height: 100%;
            pointer-events: none;
        }
        
        .leaflet-top,
          .leaflet-control {
            display: none;
          }
    </style>
</head>

<body>
    <div id="first_look">
        <div id="map" style="position:absolute; z-index: -1; height: 100%"></div>

        <div style="position:relative; text-align: center; padding-top: 20px;">
            <div id="abc">
                <h1>StreetWise</h1>

                <a href="play.php">
                    <button class="button-33" role="button">Play</button>
                </a>

                <br>
                
                <?php if (isset($user)): ?>
        
                    <a href="logout.php">
                        <button class="button-33" role="button">Logout</button>
                    </a>

                <?php else: ?>

                    <a href="login.php">
                    <button class="button-33" role="button">Sign In</button>
                    </a>

                    <br>

                    <a href="signup.html">
                        <button class="button-33" role="button">Sign Up</button>
                    </a>

                    <br>

                <?php endif; ?>
                   

                <br>
                <br>
                <br>
                <a href="leaderboard.php">
                    <button class="button-33" role="button" id="n">Leaderboard</button>
                </a>

                <style>
                    .button-33 {
                      background-color: #c2fbd7;
                      border-radius: 100px;
                      box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
                      color: green;
                      cursor: pointer;
                      display: inline-block;
                      font-family: CerebriSans-Regular,-apple-system,system-ui,Roboto,sans-serif;
                      padding: 7px 20px;
                        margin-top: 20px;
                      text-align: center;
                      text-decoration: none;
                      transition: all 250ms;
                      border: 0;
                      font-size: 20px;
                      user-select: none;
                      -webkit-user-select: none;
                      touch-action: manipulation;
                    }
                
                
                    .button-33:hover {
                      box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
                      transform: scale(1.05) rotate(-1deg);
                    }
                </style>
            </div>
        </div>

        <style>
            #abc {
                text-align: center;
                font-size: 30px;
                font-family: Arial;
                padding: 20px;
                color: #1a1a2e;
                background-color: transparent;
                display: inline-block;
                align-content: center;
    
            }
        </style>
    </div>

</body>

<script>
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
      container: 'map', // container's id or the HTML element in which the SDK will render the map
      style: style,
      center: [8.05, 52.283], // starting position [lng, lat]
      zoom: 13 // starting zoom
    });
        
    // define a function to update the map's center
  function updateMapCenter() {
    const currentCenter = map.getCenter();
    const lng = currentCenter.lng;
    const lat = currentCenter.lat;
      //console.log([lng + 0.00001, lat]);
    map.setCenter([lng + 0.00001, lat]);
  }
    map.on('load', function() {
      setInterval(updateMapCenter, 2);
    });
  // call the updateMapCenter function every 5 seconds
</script>

</html>