<?php
require_once 'Movie.php';

function findMovieById($movies, $id) {
    
    if (empty($movies)) {
        return null;
    }

    foreach ($movies as $movie) {
        if ($movie->getId() == $id) {
            return $movie;
        }
    }
    
    return null;
}

function getTotalRevenue($movies) {

    if (empty($movies)) {
        return 0;
    }

    $totalRevenue = 0;

    foreach ($movies as $movie) {
        $totalRevenue += $movie->getRevenue();
    }

    return $totalRevenue;
}

function getBestSellingMovie($movies) {
    
    if (empty($movies)) {
        return null;
    }

    $bestMovie = $movies[0];

    foreach ($movies as $movie) {
        if ($movie->getSoldSeats() > $bestMovie->getSoldSeats()) {
            $bestMovie = $movie;
        }
    }

    return $bestMovie;
}
?>