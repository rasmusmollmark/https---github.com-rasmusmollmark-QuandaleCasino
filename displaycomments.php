<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="displaycomments.css">
    <title>Comments</title>
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
            <h1> Kommentarer</h1>
            <div class="button-container">
                <button onclick="navigateTo('weather.php')">Spela</button>
                <button onclick="navigateTo('profile.php')">Profil</button>
                <button onclick="navigateTo('index.php')">Hem</button>
            </div>
        </div>
    </aside>

    <main class="container">
        <section class="commentbox">
            <h2>Skriv din kommentar:</h2>
            <form action="addcomment.php" method="post">
                <textarea name="comment" placeholder="Skriv din kommentar här.." required></textarea>
                <button type="Skicka">Submit</button>
            </form>
        </section>

        <section class="commentsection" id="comments-section">
            <h2>Kommentarsfält</h2>
            <?php
            $db = new SQLite3("./db/database.db");
            $query = "SELECT Comments.comment, User.username 
                      FROM Comments 
                      JOIN User ON Comments.userID = User.userID 
                      ORDER BY Comments.commentID DESC";
            $result = $db->query($query);

            while ($row = $result->fetchArray()) {
                echo "<p><b>{$row['username']}:</b> {$row['comment']}</p>";
            }

            $db->close();
            ?>
            <a href="./index.php" class="home-button">Home</a>
        </section>
    </main>
</body>
</html>
