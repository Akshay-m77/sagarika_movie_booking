<?php
include("auth.php");
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
        /* Custom styling for the page */
        .justified {
            text-align: justify;
            margin: 0;
            padding: 0;
        }

        .btn-rounded {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 50px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-rounded:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-rounded:active {
            transform: translateY(0);
        }

        .details {
            display: none; /* Initially hidden */
            gap: 20px;
        }

        .movie-poster img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .loading-message {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .spinner-border {
            display: block;
            width: 3rem;
            height: 3rem;
            border-width: 0.25rem;
            color: #004085;
            margin-right: 10px;
        }

        .error-message {
            color: red;
            margin-top: 20px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .details {
                flex-direction: column;
            }

            .movie-poster img {
                width: 100%;
                height: auto;
            }

            h2 {
                font-size: 5vw;
            }

            p {
                font-size: 4vw;
            }

            .btn-info {
                font-size: 4vw;
            }
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <?php include("include/header.php"); ?>
        </header>
        <aside class="main-sidebar">
            <?php include("include/leftmenu.php"); ?>
        </aside>
        <div class="content-wrapper">
            <?php include("include/topmenu.php"); ?>
        </div>

        <div class="container">
            <div class="loading-message">
                <div class="spinner-border" role="status"></div>
                <span>Loading movie details...</span>
            </div>

            <div class="error-message" id="error-message"></div>
            <div id="movie-details" class="movie-details">
                <!-- Movie details will be inserted here -->
            </div>
        </div>
    </div>

    <?php include("include/footer.php"); ?>
    <?php include("include/js.php"); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let movieId = new URLSearchParams(window.location.search).get('id');
            if (!movieId) {
                document.getElementById('error-message').textContent = 'No movie ID provided.';
                document.querySelector('.loading-message').style.display = 'none';
                return;
            }

            let apiKey = '3efd7065df87a91d0b83a8853361935a'; // Replace with your actual API key
            let movieDetailsUrl = `https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&append_to_response=credits,videos`;

            fetch(movieDetailsUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    document.querySelector('.loading-message').style.display = 'none'; // Hide loading spinner
                    populateMovieDetails(data);
                })
                .catch(error => {
                    document.querySelector('.loading-message').style.display = 'none'; // Hide loading spinner
                    document.getElementById('error-message').textContent = `Error: ${error.message}`;
                });

            function populateMovieDetails(movieDetails) {
                let title = movieDetails.title;
                let releaseDate = movieDetails.release_date;
                let overview = movieDetails.overview;
                let rating = movieDetails.vote_average;
                let posterPath = movieDetails.poster_path ? `https://image.tmdb.org/t/p/w500${movieDetails.poster_path}` : 'https://via.placeholder.com/500x750?text=No+Poster';

                // Get the cast, limiting it to a maximum of 10 names
                let cast = movieDetails.credits.cast;
                let limitedCast = cast.slice(0, 10);
                let actorNamesString = limitedCast.map(actor => actor.name).join(', ');

                let trailerLink = '';
                movieDetails.videos.results.forEach(video => {
                    if (video.type === 'Trailer' && video.site === 'YouTube') {
                        trailerLink = `https://www.youtube.com/watch?v=${video.key}`;
                    }
                });

                // Populate the details
                let movieDetailsHTML = `
                    <h2>${title}</h2>
                    <div class='details'>
                        <div class='movie-poster'>
                            <img src='${posterPath}' alt='Movie Poster'>
                        </div>
                        <div class='justified'>
                            <p><strong>Overview:</strong> ${overview}</p>
                            <p><strong>Rating:</strong> ${rating}</p>
                            <p><strong>Cast:</strong> ${actorNamesString}</p>
                            <a href='${trailerLink}' target='_blank' class='btn btn-info'>Watch Trailer</a>
                        </div>
                    </div>
                    <br>
                    <form method='POST' action='addmovieauto.php'>
                        <input type='hidden' name='title' value='${title}'>
                        <input type='hidden' name='image' value='${posterPath}'>
                        <input type='hidden' name='desc' value='${overview}'>
                        <input type='hidden' name='rating' value='${rating}'>
                        <input type='hidden' name='cast' value='${actorNamesString}'>
                        <input type='hidden' name='trailer' value='${trailerLink}'>
                        <center><button type='submit' class='btn-rounded'>Add Movie</button></center>
                    </form>
                `;
                document.getElementById('movie-details').innerHTML = movieDetailsHTML;
                document.querySelector('.details').style.display = 'flex'; // Show the details
            }
        });
    </script>
</body>
</html>
