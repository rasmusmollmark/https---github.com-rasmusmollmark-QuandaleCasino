<?php
session_start();
if (!isset($_SESSION['USERID'])) {
    header("Location: ./login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['comment'])) {
    $userID = $_SESSION['USERID'];
    $comment = $_POST['comment'];

    $db = new SQLite3("./db/database.db");
    $stmt = $db->prepare("INSERT INTO Comments (userID, comment) VALUES (:userID, :comment)");
    $stmt->bindParam(':userID', $userID, SQLITE3_INTEGER);
    $stmt->bindParam(':comment', $comment, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $db->close();
        header("Location: ./displaycomments.php");
        exit();
    } else {
        $db->close();
        echo "Error: Unable to add comment.";
    }
} else {
    echo "Error: Invalid comment.";
}
?>
