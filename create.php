<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form submitted, process the data
    
    $xml = new DOMDocument();
    $xml->load("songs.xml");

    $sc = $_POST["song_code"];
    $st = $_POST["title"];
    $sg = $_POST["genre"];
    $sa = $_POST["album"];
    $ss = $_POST["singers"];

    $singers = explode(',', $ss);


    $song = $xml->createElement("song");
    $songTitle = $xml->createElement("title", $st);
    $songGenre = $xml->createElement("genre", $sg);
    $songAlbum = $xml->createElement("album", $sa);

    foreach ($singers as $singer) {
        // Trim any leading or trailing spaces from each singer
        $singer = trim($singer);
        $songSinger = $xml->createElement("singer", $singer);
        $song->appendChild($songSinger);
    }
    $song->appendChild($songTitle);
    $song->appendChild($songGenre);
    $song->appendChild($songAlbum);
    $song->setAttribute("song_code", $sc);

    $xml->getElementsByTagName("songs")[0]->appendChild($song);
    $xml->save("songs.xml");

    header('Location: retrieve.php');
    exit();
}
?>

<style>
<?php include 'style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<form action="create.php" method="post">
Song Code: <input type="text" name="song_code"><br>
Song Title: <input type="text" name="title"><br>
Song Genre: <input type="text" name="genre"><br>
Song Album: <input type="text" name="album"><br>
Song Singers: <input type="text" name="singers"><br>

<input type="submit" value="Add Record"><br>
</form>
<a href = "retrieve.php">Go back to main page</a>
</body>
</html>


