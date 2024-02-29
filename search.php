<?php
session_start();

if (isset($_POST["song_search"])) {
    $song_search = $_POST["song_search"];
    $_SESSION["song_value_search"] = $song_search;
} else {
    $song_search = "";
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
    <!-- <div class="head">
        <a href="create.php"><button class="add_button">+ Add New Record</button></a>
        <form action="search.php" method="post">
            Search
            <input type="text" name="song_search" placeholder="Search" value="<?php echo $song_search?>">
            <input type="submit" value="Search"  class = "search_button">
        </form>
    </div> -->

    <div class="head">
    <a href="create.php"><button class="add_button">+ Add New Record</button></a>
    <form action="search.php" method="post">
        Search
        <input type="text" name="song_search" placeholder="Search" value="<?php echo $song_search ?>">
        <button type="submit" class="search_button">
        <img src="search-icon.png" alt="Search">
        </button>
    </form>
</div>

    
    

    <?php
    $xml = new DOMDocument();
    $xml->load("songs.xml");

    $songs = $xml->getElementsByTagName("song");

    $songsArray = [];

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

    // Construct HTML output for the table
    $output = "";

    $count = 1;
    foreach ($songsArray as $song) {
        // Check if the current song matches the search criteria
        if (stripos($song['song_code'], $song_search) !== false ||
            stripos($song['title'], $song_search) !== false ||
            stripos($song['genre'], $song_search) !== false ||
            stripos($song['album'], $song_search) !== false ||
            in_array(strtolower($song_search), array_map('strtolower', explode(", ", $song['singers']))) ||
            stripos($song['singers'], $song_search) !== false) {
            // Construct HTML table row
            $output .= "<tr><td>{$count}</td>
                            <td> {$song['song_code']} </td>
                            <td> {$song['title']} </td>
                            <td> {$song['genre']} </td>
                            <td> {$song['album']} </td>
                            <td> {$song['singers']} </td>
                            <td>
                                <div class='action_button'>
                                <form action='delete.php' method='post'>
                                <input type='hidden' name='song_value_code' value='{$song['song_code']}'>
                                <button class='delete_button'> <img src='delete.png' alt='Delete' class='icon-image'></button>
                            </form>
                            <form action='update.php' method='post'>
                            <input type='hidden' name='song_value_code' value='{$song['song_code']}'>
                            <button class='update_button'>
                                <img src='edit.png' alt='Update' class='icon-image'>
                            </button>
                        </form>
                        
                                </div>
                            </td>
                        </tr>";
                        $count++;
        }
    }

    echo "<table class='song-table'>
            <tr><th> # </th>
                <th> Song Code </th>
                <th> Title </th>
                <th> Genre </th>
                <th> Album </th>
                <th> Singers </th>
                <th> Action </th>
            </tr> $output </table>";
    ?>
</body>
</html>
