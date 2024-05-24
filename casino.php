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
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="blackjack.css">
    <title>Hemsida</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
    let playerCurrency = Number('<?php echo $_SESSION['CURRENCY'] ?>');
    let playerBet = 0;
    let playerScore = 0;
    let houseScore = 0;
    let houseHiddenCard;
    let playerHasBet = false;
    let houseNoOfAce = 0;
    let playerNoOfAce = 0;

    function getPlayerCurrency() {
        return playerCurrency;
    }

    function changePlayerCurrency(change) {
        playerCurrency += change;
        console.log("Updated Player Currency: ", playerCurrency);
    }

    function shuffleDeck() {
        $.get("https://deckofcardsapi.com/api/deck/swzim7bxv6c7/shuffle/?deck_count=6", function(data) {});
    }

    function valueEncoder(value, playerPlays) {
        if (value === "JACK" || value === "QUEEN" || value === "KING") {
            return 10;
        } else if (value === "ACE") {
            if(playerPlays){
                if(playerScore + 11 > 21 ){
                    return 1;
                }
                else{
                    playerNoOfAce++;
                    return 11;
                }
            }
            else{
                if(houseScore + 11 > 21 ){
                    return 1;
                }
                else{
                    houseNoOfAce++;
                    return 11;
                }
            }
        } else {
            return Number(value);
        }
    }

    function createPlayerDiv(input) {
        let cards = input.cards[0];
        let cardval = valueEncoder(cards.value, true);
        playerScore += cardval;
        let img = "<img src=\"" + cards.image + "\">";
        let div = `<div>${img}<br></div>`;
        $('#bottom').append(div);
        updatePlayerScore();
    }

    

    function createHouseDiv(input) {
        let cards = input.cards[0];
        let cardval = valueEncoder(cards.value, false);
        houseScore += cardval;
        let img = "<img src=\"" + cards.image + "\">";
        let div = `<div>${img}<br></div>`;
        $('#top').append(div);
        updateHouseScore();
    }

    function updateVisualCurrency() {
        $('#money').html(`<p>Money: ${getPlayerCurrency()}</p><p>Bet: ${playerBet}</p>`);
    }

    function updatePlayerScore() {
        $('#score-bottom').html(`<div>Score: ${playerScore}</div>`);
    }

    function updateHouseScore() {
        $('#score-top').html(`<div>Score: ${houseScore}</div>`);
    }

    window.onload = function() {
        shuffleDeck();
        resetGame();
    };

    function addHidden(input) {
        houseHiddenCard = input;
        let img = "<img src=\"" + "https://deckofcardsapi.com/static/img/back.png" + "\">";
        let div = `<div>${img}<br></div>`;
        $('#top').append(div);
    }

    function playBlackjack() {
        if (playerHasBet) {
            resetCards();
            drawCard().then(data => createPlayerDiv(data));
            drawCard().then(data => createPlayerDiv(data));
            drawCard().then(data => createHouseDiv(data));
            drawCard().then(data => addHidden(data));
            toggleGameButtons(true);
            updatePlayerScore();
            updateHouseScore();
            if(playerScore === 21){
                notifyResult("BLACKJACK");
                checkPlayerScore();
            }
            
        }
        else{
            alert("You have to place a bet first!");
        }
    }


    function drawCard() {
        return $.get("https://deckofcardsapi.com/api/deck/swzim7bxv6c7/draw/?count=1");
    }

    function housePlay() {
        createHouseDiv(houseHiddenCard);
        checkHouseScore();
    }

    function stay() {
        $('#top').children().last().remove();
        housePlay();
    }

    function playerWin() {
        notifyResult("PLAYER WON");
        gameOver(true, false);
    }

    function notifyResult(result) {
        $('#result').show();
        $('#result').append(`<div>${result}</div>`);
    }

    function push() {
        notifyResult("PUSH");
        gameOver(false, true);
    }

    function checkHouseScore() {
        if (houseScore < 17) {
            drawCard().then(data => {
                createHouseDiv(data);
                checkHouseScore();
            });
        }
        else if(houseScore > 21 && houseNoOfAce > 0){
            houseScore -= 10;
            houseNoOfAce--;
            
        } else if (houseScore >= 17 && houseScore < 22) {
            if (houseScore > playerScore) {
                houseWin();
            } else if (houseScore < playerScore) {
                playerWin();
            } else {
                push();
            }
            return;
        } else {
            playerWin();
        }
        updateHouseScore();
    }

    function houseWin() {
        notifyResult("HOUSE WON");
        gameOver(false, false);
    }

    function checkPlayerScore(playerDoubled) {
        if (playerDoubled) {
            placeBet(playerBet);
        }
        if (playerScore > 21 && playerNoOfAce > 0) {
           playerScore -= 10;
           playerNoOfAce--;
           
        }
        else if(playerScore > 21){
            notifyResult("BUST");
            $('#top').children().last().remove();
            gameOver(false, false);
            return;
        }
        if (playerScore === 21) {
            stay();
            updatePlayerScore();
            return;
        }
        else if(playerDoubled){
            stay();
        }
        updatePlayerScore();
    }

    function playerHit(playerDoubled) {
        drawCard().then(data => {
            createPlayerDiv(data);
            checkPlayerScore(playerDoubled);
        });
    }

    function resetCards() {
        playerScore = 0;
        houseScore = 0;
        $('#top').children().remove();
        $('#bottom').children().remove();
    }

    function resetGame() {
        resetCards();
        updateVisualCurrency();
        $('#result').hide();
        $('#top').append(`<div><img src="https://deckofcardsapi.com/static/img/back.png"><br></div>`);
        $('#bottom').append(`<div><img src="https://deckofcardsapi.com/static/img/back.png"><br></div>`);
        $('#top').append(`<div><img src="https://deckofcardsapi.com/static/img/back.png"><br></div>`);
        $('#bottom').append(`<div><img src="https://deckofcardsapi.com/static/img/back.png"><br></div>`);
        $('#score-top').hide();
        $('#score-bottom').hide();
        toggleGameButtons(false);
    }

    function playAgain() {
        $('#button-container').children().last().remove();
        $('#result').children().last().remove();
        $('#button-container').append('<button id="blackjack_button" onclick="playBlackjack()" >Spela blackjack</button>');
        resetGame();
    }

    function getPlayerWinnings(playerWon, push) {
        if (playerWon) {
            return getPlayerCurrency() + playerBet * 2;
        } else if (push) {
            return getPlayerCurrency() + playerBet;
        }
        return getPlayerCurrency();
    }

    function gameOver(playerWon, push) {
        let winnings = getPlayerWinnings(playerWon, push);
        updateCurrency(winnings);
        toggleGameButtons(false);
        $('#button-container').append('<button id="playagain_button" onclick="playAgain()">Spela igen</button>');
        $('#playagain_button').show();
        playerBet = 0;
        playerScore = 0;
        houseScore = 0;
        playerHasBet = false;
        playerNoOfAce = 0;
        houseHasAce = 0;
    }

    function placeBet(bet) {
        if (getPlayerCurrency() - bet >= 0) {
            playerBet += bet;
            changePlayerCurrency(-bet);
            playerHasBet = true;
            updateVisualCurrency();
            updateCurrency(getPlayerCurrency());
        } else {
            alert("Not enough money");
        }
    }

    function updateCurrency(newCurrency) {
        $.ajax({
            url: 'updateCurrency.php',
            type: 'POST',
            data: { currency: newCurrency },
            success: function(response) {
                console.log("Currency updated:", response);
                playerCurrency = newCurrency;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error updating currency: ', textStatus, errorThrown);
            }
        });
    }

    function toggleGameButtons(inGame) {
        if (inGame) {
            $('#playagain_button').remove();
            $('#blackjack_button').remove();
            $('#bet-button-container').hide();
            $('#hit-button').show();
            $('#double-button').show();
            $('#stay-button').show();
            $('#score-top').show();
            $('#score-bottom').show();
        } else {
            $('#blackjack_button').show();
            $('#bet-button-container').show();
            $('#hit-button').hide();
            $('#double-button').hide();
            $('#stay-button').hide();
        }
    }
    </script>
</head>
<body>
    <?php if(isset($_SESSION['USERID'])): ?>
        <div class="center-flex" id="container">
            <div id="website-buttons">
            <a href="./displaycomments.php">
                <button style="font-size:25px;background-color: aquamarine; border-radius: 10px;">Kommentarer</button>
            </a>
            <a href="./index.php">
                <button style="font-size:25px;background-color: aquamarine; border-radius: 10px;">Hemsida</button>
            </a>
            <a href="./logOut.php">
                <button style="font-size:25px;background-color: aquamarine; border-radius: 10px;">Logga ut</button>
            </a>
            </div>
            
            <div id="top">
            
            </div>
            <div id="bottom"></div>
            <div id="result"></div>
            <div id="score-top"></div>
            <div id="score-bottom"></div>
            <div id="betting">
                
                <div id="bet-button-container">
                    <button id="bet-10" onclick="placeBet(10)" style="font-size:10;background-color:red;color:white;">10</button>
                    <button id="bet-50" onclick="placeBet(50)" style="font-size:10;background-color:red;color:white;">50</button>
                    <button id="bet-100" onclick="placeBet(100)" style="font-size:10;background-color:red;color:white;">100</button>
                </div>
                <div id="money"></div>
            </div>
            <div id="button-container">
                <button id="blackjack_button" onclick="playBlackjack()" >Spela blackjack</button>
                <button id="hit-button" onclick="playerHit(false)" style="display: none;">Hit</button>
                <button id="double-button" onclick="playerHit(true)" style="display: none;">Double</button>
                <button id="stay-button" onclick="stay()" style="display: none;">Stay</button>
                <button id="playagain_button" onclick="playAgain()" style="display:none;">Spela igen</button>
            </div>
            <img src="NyaQuandale.gif" alt="Top Right Image" id="top-right-image">
        </div>
    <?php else: ?>
        <section>
            <h2>DU ÄR INTE INLOGGAD</h2>
            <button>Gå tillbaka</button>
        </section>
    <?php endif ?>
</body>
</html>
