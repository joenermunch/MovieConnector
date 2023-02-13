<?php

// Add a top level menu page to the admin area

function movie_plugin_page() {
    add_menu_page(
        'Movie Connector',        
        'Movie Connector',       
        'manage_options',           
        'movie-connector',         
        'movie_plugin_content',      
        'dashicons-admin-plugins',  
        6                            
    );
}
add_action('admin_menu', 'movie_plugin_page');

// Callback function for the Movie Plugin page

function movie_plugin_content() {
    echo '<h1>Movie Plugin</h1>';
    echo '<button type="button" class="button button-primary" id="retrieve-movies">Retrieve Movies</button>';
    echo '<div id="import-result"></div>';
}