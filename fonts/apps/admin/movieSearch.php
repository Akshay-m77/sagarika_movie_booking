<?php
include("auth.php");

include('../connect/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <?php include("include/css.php"); ?>
    <style>

.spinner-border {
            display: none;
            width: 3rem;
            height: 3rem;
            border-width: 0.25rem;
            color: #004085;
        }
        .loading-message {
            display: none;
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
        /* Custom styling here (same as original) */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;  
        }
        .container {
            margin-top: 50px;
            margin-bottom: 40px;
            background-color:#f4f4ff;
        }
        h1, h2 {
            color: #004085;
        }
        .content-wrapper {
            padding: 20px;
        }
        
        .form-group label {
            font-weight: bold;
        }
        .movie-item {
            margin-bottom: 15px;
        }
        .movie-item label {
            margin-left: 10px;
        }
        .btn-primary {
            background-color: #004085;
            border-color: #004085;
        }
        .btn-primary:hover {
            background-color: #003060;
            border-color: #003060;
        }
        .box {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .movie-details img {
            width: 100%;
            max-width: 300px;
            border-radius: 5px;
        }
        .animate__animated.animate__fadeIn {
            --animate-duration: 1.5s;
            width: 100%;
        }
        .movie-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center; 
        }
        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .movie-card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            max-height: 400px;
            object-fit: cover;
            align-self: center;
        }
        .movie-card-body {
            padding: 15px;
        }
        .movie-details {
            display: none;
        }

        .add_movie_manually {
            position: static;
            top: 10%;
            right: 0;
            transform: translateY(0) translateX(100%);
            width: 300px;
            padding: 20px;
            background: #41C9E2;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px; /* Apply rounded corners to all sides */
            text-align: left;
            font-family: 'Lucida Console', Monospace;
            color: #333;
            z-index: 1000;
            animation: slideIn 0.5s forwards;
            font-weight: bold;
        }

        @keyframes slideIn {
            0% {
                transform: translateY(0) translateX(100%);
            }
            100% {
                transform: translateY(0) translateX(0);
            }
        }

        .add_movie_manually h2 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #444;
        }

        .add_movie_manually form {
            display: flex;
            flex-direction: column;
        }

        .add_movie_manually input {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .add_movie_manually button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .add_movie_manually button:hover {
            background-color: #B3C8CF;
        }

        .down {
            outline: 0;
            grid-gap: 8px;
            align-items: center;
            background-color: #ff90e8;
            color: #000;
            border: 1px solid #000;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            flex-shrink: 0;
            font-size: 16px;
            gap: 8px;
            justify-content: center;
            line-height: 1.5;
            overflow: hidden;
            padding: 12px 16px;
            text-decoration: none;
            text-overflow: ellipsis;
            transition: all .14s ease-out;
            white-space: nowrap;
        }

        .down:hover {
            box-shadow: 4px 4px 0 #000;
            transform: translate(-4px, -4px);
        }

        .down:focus-visible {
            outline-offset: 1px;
        }

        @media (min-width: 600px) {
            .containe {
                grid-template-columns: repeat(2, 1fr);
            }
            .responsive-div {
                background-color: white;
            }
        }
        @media (min-width: 900px) {
            .containe {
                grid-template-columns: repeat(3, 1fr);
            }
            .responsive-div {
                background-color: white;
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
            <h1>Movie Search</h1>

            <!-- Search Form -->
            <form id="movie-search-form">
                <div class="form-group">
                    <label for="query">Search for a movie:</label>
                    <input type="text" id="query" name="query" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Loading Spinner and Message -->
            <div class="loading-message">
                <div class="spinner-border" role="status"></div>
                <span>Loading movie data...</span>
            </div>

            <!-- Movie Results Container -->
            <div id="movie-results" class="row mt-4"></div>

            <!-- Error Message Container -->
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
    <?php include("include/footer.php"); ?>
    <?php include("include/js.php"); ?>
      <script>
        // Function to handle movie search via AJAX
        document.getElementById('movie-search-form').addEventListener('submit', function(event) {
            event.preventDefault();  // Prevent form from submitting the traditional way
            
            let query = document.getElementById('query').value;
            if (query === '') {
                alert('Please enter a movie title.');
                return;
            }
            
            // Show the loading spinner and message
            document.querySelector('.loading-message').style.display = 'block';
            document.getElementById('movie-results').innerHTML = '';  // Clear previous results

            let apiKey = '3efd7065df87a91d0b83a8853361935a';  // Replace with your actual API key
            let baseUrl = 'https://api.themoviedb.org/3/search/movie';
            let url = `${baseUrl}?api_key=${apiKey}&query=${encodeURIComponent(query)}`;

            // Make AJAX request to the API
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Hide the loading spinner
                    document.querySelector('.loading-message').style.display = 'none';

                    if (data.results && data.results.length > 0) {
                        let resultsHTML = '';
                        data.results.forEach(movie => {
                            let movieId = movie.id;
                            let title = movie.title || 'Unknown Title';
                            let posterPath = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : 'https://via.placeholder.com/500x750?text=No+Poster';
                            let releaseDate = movie.release_date || 'Unknown Release Date';

                            resultsHTML += `
                                <div class="col-md-4 mb-4">
                                    <div class="movie-card">
                                        <a href="details.php?id=${movieId}">
                                            <img src="${posterPath}" alt="${title}">
                                            <div class="movie-card-body">
                                                <label>${title} (${releaseDate})</label>
                                            </div>
                                        </a>
                                    </div>
                                </div>`;
                        });
                        document.getElementById('movie-results').innerHTML = resultsHTML;
                    } else {
                        document.getElementById('movie-results').innerHTML = '<div class="alert alert-warning mt-3">No results found for your search query.</div>';
                    }
                })
                .catch(error => {
                    document.querySelector('.loading-message').style.display = 'none';
                    document.getElementById('movie-results').innerHTML = `<div class="alert alert-danger mt-3">Network error: Unable to connect to the server.Please check your network connection.</div>`;
                });
        });
    </script>
</body>
</html>
