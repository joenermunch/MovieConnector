<?php

// Enqueue frontend styles for the movie-connector plugin
function movie_connector_enqueue() {
    // Enqueue the 'movie-connector' style from the css/style.css file
    wp_enqueue_style(
        'movie-connector',
        plugins_url( '/css/style.css', dirname( __FILE__ ) )
    );
}

// Hook the movie_connector_enqueue function to the 'wp_enqueue_scripts' action
add_action('wp_enqueue_scripts', 'movie_connector_enqueue');

// Enqueue script for retrieving movies via AJAX
function enqueue_retrieve_movies_script() {
    // Enqueue the 'retrieve-movies' script from the js/retrieve-movies.js file
    wp_enqueue_script(
        'retrieve-movies',
        plugins_url( '/js/retrieve-movies.js', dirname( __FILE__ ) ),
        array('jquery'), // Dependency: jQuery
        '1.0',
        true
    );
    // Localize the 'retrieve-movies' script, making the WordPress admin-ajax.php URL available to it
    wp_localize_script('retrieve-movies', 'movie_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

// Hook the enqueue_retrieve_movies_script function to the 'admin_enqueue_scripts' action
add_action('admin_enqueue_scripts', 'enqueue_retrieve_movies_script');