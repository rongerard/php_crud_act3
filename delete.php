
<?php
session_start(); 
if(isset($_REQUEST["song_value_code"])){
    $sv = $_REQUEST["song_value_code"];
    $_POST["song_value"] = $sv;
} else {
    $sv = "";
    $_POST["song_value"] = $sv;
}

?>
<style>
<?php include 'style.css'; ?>
</style>


<?php


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["song_code"])) {
    $songCodeToDelete = $_POST["song_code"];

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load("songs.xml");

    // Find the song node with the specified song code
    //DOMXPATH is for queries
    $xpath = new DOMXPath($xml);
//nag query kung may node na kamuka tas lalagay sa $nodes
//Select all <song> elements with a specific song_code attribute value:
    $nodes = $xpath->query("//song[@song_code='$songCodeToDelete']");

    // If a matching song node is found, remove it
    if ($nodes->length > 0) {
        foreach ($nodes as $node) {
            // Access content inside the node
            $songTitle = $node->getElementsByTagName("title")->item(0)->nodeValue;
            $genre = $node->getElementsByTagName("genre")->item(0)->nodeValue;
            $album = $node->getElementsByTagName("album")->item(0)->nodeValue;
            $singers = $node->getElementsByTagName("singer")->item(0)->nodeValue;
             // Display content
             echo "List of Song(s) to be deleted<br><br>";
             echo "Song Code: $songCodeToDelete<br>";
             echo "Song Title: $songTitle<br>";
             echo "Genre: $genre<br>";
             echo "Album: $album<br>";
             echo "Singers: $singers<br><br>";
            // Remove the node
            $node->parentNode->removeChild($node);
        }

        // Save the changes back to the XML file
        $xml->save("songs.xml");
        
        echo "Record(s) with Song Code <b>$songCodeToDelete</b> has been deleted successfully.";
    } else {
        echo "Record with Song Code <b>$songCodeToDelete </b>not found.";
    }
} else {
    $songCodeToDelete = $_POST["song_value_code"];
     // Load the XML file
     $xml = new DOMDocument();
     $xml->load("songs.xml");
 
     // Find the song node with the specified song code
     //DOMXPATH is for queries
     $xpath = new DOMXPath($xml);
 //nag query kung may node na kamuka tas lalagay sa $nodes
 //Select all <song> elements with a specific song_code attribute value:
     $nodes = $xpath->query("//song[@song_code='$songCodeToDelete']");
 
     // If a matching song node is found, remove it
     if ($nodes->length > 0) {
         foreach ($nodes as $node) {
             // Access content inside the node
             $songTitle = $node->getElementsByTagName("title")->item(0)->nodeValue;
             $genre = $node->getElementsByTagName("genre")->item(0)->nodeValue;
             $album = $node->getElementsByTagName("album")->item(0)->nodeValue;
             $singers = $node->getElementsByTagName("singer")->item(0)->nodeValue;
             // Display content
             echo "List of Song(s) to be deleted<br><br>";
             echo "Song Code: $songCodeToDelete<br>";
             echo "Song Title: $songTitle<br>";
             echo "Genre: $genre<br>";
             echo "Album: $album<br>";
             echo "Singers: $singers<br><br>";
         }
        }
        
         
        
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>

<form action="delete.php" method="post">
    <input type="hidden" name="song_code" value="' . $_POST["song_value"] . '">
    <button type="submit" class="delete_button">Confirm Delete</button>
</form>

</body>
</html>
';


}

?>
<br><br>


<a href = "retrieve.php">Go back to main page</a>
