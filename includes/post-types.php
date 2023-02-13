<?php

// This function creates the custom post type "Movies" and a page called "Actors".
function create_movies_post_type() {
    register_post_type('movies',
        array(
            'labels' => array(
                'name' => 'Movies',
                'singular_name' => 'Movie',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Movie',
                'edit_item' => 'Edit Movie',
                'new_item' => 'New Movie',
                'view_item' => 'View Movie',
                'search_items' => 'Search Movies',
                'not_found' => 'No Movies found',
                'not_found_in_trash' => 'No Movies found in Trash',
                'parent_item_colon' => 'Parent Movie:',
                'menu_name' => 'Movies'
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array('category', 'post_tag'),
            'menu_position' => 5,
            'exclude_from_search' => false,
            'rewrite'     => array( 'slug' => 'movies' ),
            'publicly_queryable'    => true

            )
    );

    $actors_page_id = get_option( 'actors_page_id' );
    if ( ! $actors_page_id ) {
        $actors_page = array(
            'post_title'   => 'Actors',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_type'    => 'page',
            'post_slug'    => 'actors',
        );

        $actors_page_id = wp_insert_post( $actors_page );
        update_option( 'actors_page_id', $actors_page_id );
    }
}
add_action('init', 'create_movies_post_type');