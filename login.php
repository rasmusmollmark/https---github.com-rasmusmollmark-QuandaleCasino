<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Inloggad</title>
</head>
<body>
<div class="header"> 
    <h1>Logga in</h1>
</div>

<div class="loginInput" id="container">
    <p>
        <form name="register" action="loginDb.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Användarnamn...">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Lösenord">
            <input type="submit" value="Skicka"> 
        </form>
    </p>
</div>

<div class="topnav">
    <a href="./registration.php" class="button">Registrera dig</a>
</div> 
</body>
</html>
