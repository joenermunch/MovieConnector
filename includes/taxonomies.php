<?php
// This function creates the custom taxonomy type "Movie Genres"
function register_movie_genre_taxonomy() {
    register_taxonomy(
        'movie_genre',
        'movies',
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Movie Genres',
                'singular_name' => 'Movie Genre',
                'search_items' => 'Search Movie Genres',
                'all_items' => 'All Movie Genres',
                'parent_item' => 'Parent Movie Genre',
                'parent_item_colon' => 'Parent Movie Genre:',
                'edit_item' => 'Edit Movie Genre',
                'update_item' => 'Update Movie Genre',
                'add_new_item' => 'Add New Movie Genre',
                'new_item_name' => 'New Movie Genre Name',
                'menu_name' => 'Movie Genres',
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
        )
    );
}

add_action('init', 'register_movie_genre_taxonomy');

// This function creates the custom taxonomy type "Actors"
function register_actors_taxonomy() {
    register_taxonomy(
        'actors',
        'movies',
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Actors',
                'singular_name' => 'Actor',
                'search_items' => 'Search Actors',
                'all_items' => 'All Actors',
                'parent_item' => 'Parent Actor',
                'parent_item_colon' => 'Parent Actor:',
                'edit_item' => 'Edit Actor',
                'update_item' => 'Update Actor',
                'add_new_item' => 'Add New Actor',
                'new_item_name' => 'New Actor Name',
                'menu_name' => 'Actors',
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
        )
    );
}

add_action('init', 'register_actors_taxonomy');