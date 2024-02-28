<?php
session_start();

if(isset($_SESSION["song_value_search"])){
    
}else{
$_SESSION["song_value_search"] = "";
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

<!-- REQUEST SESSION GET ,SET -->
    



<div class="head">
    <a href="create.php"><button class="add_button">+ Add New Record</button></a>
    <form action="search.php" method="post">
        Search
        <input type="text" name="song_search" placeholder="Search" value="<?php echo $_SESSION["song_value_search"] ?>" >
        <input type="submit" value="Search" class = "search_button">
    </form>
</div>

</body>
</html>


<?php
$xml = new DOMDocument();
$xml->load("songs.xml");

$songs = $xml->getElementsByTagName("song");

// Create an empty array to store songs
$songsArray = [];

// Loop through each song and store its details in the array
foreach ($songs as $song) {
    $songNo = $song->getAttribute("song_code");
    $songTitle = $song->getElementsByTagName("title")[0]->nodeValue;
    $songGenre = $song->getElementsByTagName("genre")[0]->nodeValue;
    $songAlbum = $song->getElementsByTagName("album")[0]->nodeValue;

    $singersTemp = $song->getElementsByTagName("singer");
    $songSingersArray = [];
    foreach ($singersTemp as $singer) {
        $songSingers = $singer->nodeValue;
        $songSingersArray[] = $songSingers;
    }

    // Store song details in an associative array
    $songsArray[] = [
        'song_code' => $songNo,
        'title' => $songTitle,
        'genre' => $songGenre,
        'album' => $songAlbum,
        'singers' => implode(", ", $songSingersArray)
    ];
}

// Function to compare song codes for sorting
function compareSongCodes($a, $b) {
    return strcmp($a['song_code'], $b['song_code']);
}

// Sort the songs array based on song_code
usort($songsArray, 'compareSongCodes');

// Generate HTML output for the sorted songs
// Generate HTML output for the sorted songs
$output = "";
// Counter for in-order numbers
$count = 1;
foreach ($songsArray as $song) {
    $output .= "<tr>
        <td>{$count}</td>
        <td>{$song['song_code']}</td>
        <td>{$song['title']}</td>
        <td>{$song['genre']}</td>
        <td>{$song['album']}</td>
        <td>{$song['singers']}</td>
        <td>
            <div class='action_button'>
                <form action='delete.php' method='post'>
                    <input type='hidden' name='song_value_code' value='{$song['song_code']}'>
                    <button class='delete_button'>Delete</button>
                </form>
                <form action='update.php' method='post'>
                    <input type='hidden' name='song_value_code' value='{$song['song_code']}'>
                    <button class='update_button'>Update</button>
                </form>
            </div>
        </td>
    </tr>";
    // Increment the counter
    $count++;
}

echo "<table class='song-table'>
    <tr>
        <th>#</th>
        <th>Song Code</th>
        <th>Title</th>
        <th>Genre</th>
        <th>Album</th>
        <th>Singers</th>
        <th>Action</th>
    </tr> $output </table>";

?>



