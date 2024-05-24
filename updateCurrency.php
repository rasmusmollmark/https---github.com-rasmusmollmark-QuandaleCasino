<?php
session_start();
if (!isset($_SESSION['USERID'])) {
    header("Location: ./logOut.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['USERID'];
    $newCurrency = $_POST['currency'];
    

    $db = new SQLite3("./db/database.db");
    $sql = "UPDATE User SET currency = :currency WHERE userID = :userID";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':currency', $newCurrency);
    $stmt->bindParam(':userID', $userID);

    if ($stmt->execute()) {
        $_SESSION['CURRENCY'] = $newCurrency;
        echo "Currency updated successfully";
    } else {
        echo "Error updating currency: " . $db->lastErrorMsg();
    }

    $stmt->close();
}
?>
