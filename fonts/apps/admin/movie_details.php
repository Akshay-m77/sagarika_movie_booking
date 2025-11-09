<?php
include("auth.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $movieId = $_GET['id'];
    $apiKey = '3efd7065df87a91d0b83a8853361935a';
    $movieDetailsUrl = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=credits,videos";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $movieDetailsUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die("cURL error: " . curl_error($ch));
    } else {
        $movieDetails = json_decode($response, true);
    }
    curl_close($ch);
} else {
    die("No movie ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <?php include("include/css.php"); ?>

    <style>
        /* Add your custom CSS styling here */
        /* Reuse your existing styles */
    </style>
</head>

<body>
    <div class="wrapper">
        <header class="main-header">
            <?php include("include/header.php"); ?>
        </header>
        <aside class="main-sidebar">
            <?php include("include/leftmenu.php"); ?>
        </aside>

        <div class="content-wrapper">
            <div class="container">
                <h2><?php echo $movieDetails['title']; ?> (<?php echo $movieDetails['release_date']; ?>)</h2>
                <div class="row">
                    <div class="col-md-4">
                        <img src="https://image.tmdb.org/t/p/w500<?php echo $movieDetails['poster_path']; ?>" alt="Movie Poster">
                    </div>
                    <div class="col-md-8">
                        <div class="box">
                            <p><strong>Overview:</strong> <?php echo $movieDetails['overview']; ?></p>
                            <p><strong>Rating:</strong> <?php echo $movieDetails['vote_average']; ?></p>
                            <p><strong>Cast:</strong> <?php echo implode(', ', array_column($movieDetails['credits']['cast'], 'name')); ?></p>
                            <?php if (!empty($movieDetails['videos']['results'])): ?>
                                <p><strong>Watch Trailer:</strong> 
                                    <a href="https://www.youtube.com/watch?v=<?php echo $movieDetails['videos']['results'][0]['key']; ?>" target="_blank">Watch Trailer</a>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("include/footer.php"); ?>
    <?php include("include/js.php"); ?>
</body>
</html>
