<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrering</title>
        <link rel="stylesheet" href="registrationStyle.css">

        <script>
            function test(){
                let form = document.forms['register'];
			let uname = form['username'].value;
			let pword = form['password'].value;
                return true;}
        </script>
    </head>
    <body>
    <div class="header"> 
    <h1>Registrera dig</h1>
</div>
        
        <div class="registration-input" id="container">
            <p>
                <form name="register" action="registrationDb.php" method="post" onsubmit="test()">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Användarnamn...">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Lösenord">
                    <input type="submit" value="Skicka"> 
                </form>
            </p>
        </div>
        <div class="topnav">
    <a href="./login.php" class="button">Logga in</a>
    </div>
    </body>

</html>

