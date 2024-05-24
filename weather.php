<!DOCTYPE html>
<html lang="en">
<head>
<?php 
    session_start(); 
    if(!isset($_SESSION['USERID'])){
        header("Location: ./logOut.php");
        exit();
    }
    ?>
    <script>
         function navigateTo(url) {
           window.location.href = url;
       }
        </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="weather.css">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .nice-weather {
            background: url('./nice-weather.jpg') no-repeat center center fixed;
        }

        .bad-weather {
            background: url('./bad-weather.jpg') no-repeat center center fixed;
        }
    </style>
</head>
<body>
    <script>
        window.onload = function(){
            getTemp().then(data => {
                displayWeather(data);
                displayQuestiondiv(data);
                updateBackground(data);
            });
        }

        function displayQuestiondiv(input){
            let text = "";
            if(weatherIsGood(input)){
                text = "Vädret ser bra ut, säker på att du ska sitta inne och spela blackjack?";
            } else {
                text = "Vädret är inget vidare, vill du verkligen sitta och deppa med blackjack?";
            }
            let para = `
            <p id="question_p">
            ${text}
            </p>
            `;
            $('#question-div').append(para);
        }

        function weatherIsGood(input){
            return input.hourly.temperature_2m[getHours()] >= 18;
            }

        function getHours(){
            return new Date().getHours();
        }

        function displayWeather(input){
            let temp = input.hourly.temperature_2m[getHours()];
            let div = ` <div id="temperature">
            <p>
            ${temp} °C
            </p>
            </div>
            `;
            $('#container').append(div);
        }

        function updateBackground(input){
            if(weatherIsGood(input)){
                document.body.classList.add('nice-weather');
                document.body.classList.remove('bad-weather');
            } else {
                document.body.classList.add('bad-weather');
                document.body.classList.remove('nice-weather');
            }
        }

        function getTemp(){
         return $.get("https://api.open-meteo.com/v1/forecast?latitude=59.857024&longitude=17.644137&hourly=temperature_2m,apparent_temperature&wind_speed_unit=ms&timezone=Europe%2FStockholm&forecast_days=1"); 
        }

       

    </script>

<aside id="topbar">
       <div class="topbar-container">
           <img src="NyaQuandale.gif" alt="Qandale Casino Logo" class="topbar-logo" href="./index.php">
           <h1>Tankeställare</h1>
           <div class="button-container">
               <button onclick="navigateTo('weather.php')">Spela</button>
               <button onclick="navigateTo('profile.php')">Profil</button>
               <button onclick="navigateTo('index.php')">Hem</button>
           </div>
       </div>
   </aside>

    <div class="center-flex" id="container">
        <div id="question-div">
            <div id="button-container">
                <a href="casino.php">
                <button id="yes-button">JA</button>
                </a>
                <a href="homepage.php">
                <button id="no-button">NEJ</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
