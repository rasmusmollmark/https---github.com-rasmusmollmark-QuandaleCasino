<?php
$namn = $_POST['username'];
$lösenord = $_POST['password'];

if (loginCorrect($namn, $lösenord) && !empty($namn)) {
    session_set_cookie_params(0);
    session_start();
    $_SESSION['USERID'] = getUserID($namn, $lösenord); // Assuming you uncomment getUserID function
    $_SESSION['CURRENCY'] = getUserCurrency($_SESSION['USERID']);
    header("Location: ./index.php");
    exit();
} else {
    echo "Inlogg misslyckades!";
}

function loginCorrect($namn, $lösenord) {
    $db = new SQLite3("./db/database.db");
    $stmt = $db->prepare('SELECT username, password FROM User WHERE username = :username');
    $stmt->bindValue(':username', $namn, SQLITE3_TEXT);
    $result = $stmt->execute();
    $person = $result->fetchArray();
    return $person && password_verify($lösenord, $person['password']);
}

function getUserID($namn, $lösenord) {
    $db = new SQLite3("./db/database.db");
    $stmt = $db->prepare('SELECT userID, password FROM User WHERE username = :username');
    $stmt->bindValue(':username', $namn, SQLITE3_TEXT);
    $result = $stmt->execute();
    $person = $result->fetchArray();
    if ($person && password_verify($lösenord, $person['password'])) {
        return $person['userID'];
    } else {
        return null;
    }
}

function getUserCurrency($userID) {
    $db = new SQLite3("./db/database.db");
    $stmt = $db->prepare('SELECT currency FROM User WHERE userID = :userID');
    $stmt->bindValue(':userID', $userID, SQLITE3_TEXT);
    $result = $stmt->execute();
    $person = $result->fetchArray();
    if ($person) {
        return $person['currency'];
    } else {
        return null;
    }
}
?>
