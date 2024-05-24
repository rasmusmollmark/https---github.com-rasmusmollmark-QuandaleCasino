<?php
session_start();

if (!isset($_SESSION['USERID'])) {
    die("You are not logged in.");
}

$user_id = $_SESSION['USERID'];

$db = new SQLite3("./db/database.db");
$stmt = $db->prepare('SELECT username, currency FROM User WHERE userID = :userID');
$stmt->bindValue(':userID', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$profile = $result->fetchArray(SQLITE3_ASSOC);

if (!$profile) {
    die("User profile not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <script>
       function navigateTo(url) {
           window.location.href = url;
       }
   </script>
</head>
<body>

<aside id="topbar">
       <div class="topbar-container">
           <img src="NyaQuandale.gif" alt="Qandale Casino Logo" class="topbar-logo" href="./index.php">
           <h1> Profil </h1>
           <div class="button-container">
               <button onclick="navigateTo('weather.php')">Spela</button>
               <button onclick="navigateTo('profile.php')">Profil</button>
               <button onclick="navigateTo('index.php')">Hem</button>
           </div>
       </div>
   </aside>

    <article id="profileBox">
        <img src="profilepic.png" alt="Profile picture">
        <ul> 
            <li>
                <h3> Username: <?php echo htmlspecialchars($profile['username']); ?> </h3>
            </li>
            <li>
                <h3> Currency: <?php echo htmlspecialchars($profile['currency']); ?> </h3>
            </li>
        </ul>
    </article>
</body>
</html>
