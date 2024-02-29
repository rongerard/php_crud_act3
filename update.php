<?php
//para makuha yung song value 
session_start(); 

if(isset($_REQUEST["song_value_code"])){
    $sv = $_REQUEST["song_value_code"];
    $_SESSION["song_value"] = $sv;
    
} else {
    $sv = "";
    $_SESSION["song_value"] = $sv;
}
?>





<?php
//Retrieve the value and put it in input
if(isset($_REQUEST["song_value_code"])) {
    $sv = $_REQUEST["song_value_code"];
    $_SESSION["song_value"] = $sv;

    // Retrieve the corresponding song details and set them in session
    $xml = new DOMDocument();
    $xml->load("songs.xml");
    $songs = $xml->getElementsByTagName("song");
    foreach ($songs as $song) {
        if ($sv === $song->getAttribute("song_code")) {
            $_SESSION["song_title"] = $song->getElementsByTagName("title")[0]->nodeValue;
            $_SESSION["song_genre"] = $song->getElementsByTagName("genre")[0]->nodeValue;
            $_SESSION["song_album"] = $song->getElementsByTagName("album")[0]->nodeValue;

            

             // Retrieve singers and add them to session
             $singersTemp = $song->getElementsByTagName("singer");
             $songSingersArray = [];
             foreach ($singersTemp as $singer) {
                 $songSingers = $singer->nodeValue;
                 // Trim any leading or trailing spaces from each singer
                 $songSingers = trim($songSingers);
                 $songSingersArray[] = $songSingers;
             }
             // Store the array of singers in session
             $_SESSION["song_singers"] = $songSingersArray;
        }
    }
} else {
    $sv = "";
    $_SESSION["song_value"] = $sv;
}


?>

<?php


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["song_code"])) {
    $songCode = $_POST["song_code"];

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load("songs.xml");

    
    $st = $_POST["title"];
    $sg = $_POST["genre"];
    $sa = $_POST["album"];
    $ss = $_POST["singers"];

    if (is_array($ss)) {
        // If $_POST["singers"] is already an array, convert it to a string
        $ss = implode(',', $ss);
    }
    
    $singers = explode(',', $ss);

    $songs = $xml->getElementsByTagName("song");
  
    foreach($songs as $song){

        if($songCode ===$song->getAttribute("song_code")){

        $newSong = $xml->createElement("song");
    $songTitle = $xml->createElement("title", $st);
    $songGenre = $xml->createElement("genre", $sg);
    $songAlbum = $xml->createElement("album", $sa);
   
    foreach ($singers as $singer) {
        // Trim any leading or trailing spaces from each singer
        $singer = trim($singer);
        $songSinger = $xml->createElement("singer", $singer);
        $newSong->appendChild($songSinger);
    }

    $newSong->appendChild($songTitle);
    $newSong->appendChild($songGenre);
    $newSong->appendChild($songAlbum);
    $newSong->setAttribute("song_code",$songCode);

  

    $xml->getElementsByTagName("songs")[0]->replaceChild($newSong,$song);
    $xml->save("songs.xml");

    header('Location: retrieve.php');
    exit();
if($_SESSION["song_value"] === ""){
    echo "Updated!";

}
   
    
    break;
        }
    }
  
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
$songCodeShortcut = $_SESSION["song_value"];
echo "<p>Song Code: $songCodeShortcut </p>"
?>



<?php

if(isset($_SESSION["song_value"]) && $_SESSION["song_value"] != "") {
    ?>
 <form action="update.php" method="post">
    <input type="hidden" name="song_value_code" value="<?php echo $_SESSION['song_value']; ?>"><br>
    <input type="submit" name="edit_all" value="Edit All" class="edit-button">
</form>
    <?php
}
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_all'])) {
    
    echo '<div class = "update_container">';
    echo '<form action="update.php" method="post" class = "edit_form">';
    echo '<input type="hidden" name="song_code" value="' . $_SESSION['song_value'] . '"><br>';
    echo 'Song Title: <input type="text" name="title" value="' . $_SESSION['song_title'] . '"><br>'; 
    echo 'Song Genre: <input type="text" name="genre" value="' . $_SESSION['song_genre'] . '"><br>'; 
    echo 'Song Album: <input type="text" name="album" value="' . $_SESSION['song_album'] . '"><br>'; 
   

     // Convert array to string before inserting into input field
     $singers = implode(", ", $_SESSION['song_singers']);

    echo 'Song Singers: <input type="text" name="singers" value="' . htmlspecialchars($singers) . '"><br>'; 
    echo '<input type="submit" value="Update Record">';
    echo '</form>';
    echo '</div>'; 
}
?>

<?php

if(isset($_SESSION["song_value"]) && $_SESSION["song_value"] != "") {
    ?>
    <form action="update.php" method="post">
        <input type="hidden" name="song_value_code" value="<?php echo $_SESSION['song_value']; ?>"><br>
        <input type="submit" name="edit_title" value="Edit Title" class="edit-button">
    </form>
    <?php
}
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_title'])) {
    
    echo '<form action="update.php" method="post">';
    echo '<input type="hidden" name="song_code" value="' . $_SESSION['song_value'] . '"><br>';
    
    echo 'Song Title: <input type="text" name="title" value="' . $_SESSION['song_title'] . '"><br>'; 
    echo ' <input type="hidden" name="genre" value="' . $_SESSION['song_genre'] . '"><br>'; 
    echo ' <input type="hidden" name="album" value="' . $_SESSION['song_album'] . '"><br>'; 
    $singers = implode(", ", $_SESSION['song_singers']);

    echo '<input type="hidden" name="singers" value="' . htmlspecialchars($singers) . '"><br>'; 
    echo '<input type="submit" value="Update Record">';
    echo '</form>';
}
?>

<?php

if(isset($_SESSION["song_value"]) && $_SESSION["song_value"] != "") {
    ?>
<form action="update.php" method="post">
    <input type="hidden" name="song_value_code" value="<?php echo $_SESSION['song_value']; ?>"><br>
    <input type="submit" name="edit_genre" value="Edit Genre" class="edit-button">
</form>
    <?php
}
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_genre'])) {

    echo '<form action="update.php" method="post">';
    echo '<input type="hidden" name="song_code" value="' . $_SESSION['song_value'] . '"><br>';
    echo ' <input type="hidden" name="title" value="' . $_SESSION['song_title'] . '"><br>'; 
    echo 'Song Genre: <input type="text" name="genre" value="' . $_SESSION['song_genre'] . '"><br>'; 
    echo ' <input type="hidden" name="album" value="' . $_SESSION['song_album'] . '"><br>'; 
    $singers = implode(", ", $_SESSION['song_singers']);

    echo '<input type="hidden" name="singers" value="' . htmlspecialchars($singers) . '"><br>'; 
    echo '<input type="submit" value="Update Record">';
    echo '</form>';
}
?>

<?php

if(isset($_SESSION["song_value"]) && $_SESSION["song_value"] != "") {
    ?>
<form action="update.php" method="post">
    <input type="hidden" name="song_value_code" value="<?php echo $_SESSION['song_value']; ?>"><br>
    <input type="submit" name="edit_album" value="Edit Album" class="edit-button">
</form>
    <?php
}
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_album'])) {

    echo '<form action="update.php" method="post">';
    echo '<input type="hidden" name="song_code" value="' . $_SESSION['song_value'] . '"><br>';
    echo ' <input type="hidden" name="title" value="' . $_SESSION['song_title'] . '"><br>'; 
    echo ' <input type="hidden" name="genre" value="' . $_SESSION['song_genre'] . '"><br>'; 
    echo 'Song Album: <input type="text" name="album" value="' . $_SESSION['song_album'] . '"><br>';
    $singers = implode(", ", $_SESSION['song_singers']);

    echo '<input type="hidden" name="singers" value="' . htmlspecialchars($singers) . '"><br>'; 
    echo '<input type="submit" value="Update Record">';
    echo '</form>';
}
?>

<?php

if(isset($_SESSION["song_value"]) && $_SESSION["song_value"] != "") {
    ?>
<form action="update.php" method="post">
    <input type="hidden" name="song_value_code" value="<?php echo $_SESSION['song_value']; ?>"><br>
    <input type="submit" name="edit_singers" value="Edit Singers" class="edit-button">
</form>
    <?php
}
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_singers'])) {

    echo '<form action="update.php" method="post">';
    echo '<input type="hidden" name="song_code" value="' . $_SESSION['song_value'] . '"><br>';
    echo ' <input type="hidden" name="title" value="' . $_SESSION['song_title'] . '"><br>'; 
    echo ' <input type="hidden" name="genre" value="' . $_SESSION['song_genre'] . '"><br>'; 
    echo '<input type="hidden" name="album" value="' . $_SESSION['song_album'] . '"><br>';
    $singers = implode(", ", $_SESSION['song_singers']);

    echo 'Song Singers: <input type="text" name="singers" value="' . htmlspecialchars($singers) . '"><br>'; 
    echo '<input type="submit" value="Update Record">';
    echo '</form>';
}
?>



</body>
</html>
<a href = "retrieve.php" class = "back-link">Go back to main page</a>
<style>
<?php include 'style.css'; ?>
</style>



