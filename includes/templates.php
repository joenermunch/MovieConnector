<?php

// Function to assign a template for actors taxonomy
function my_plugin_taxonomy_template( $template ) {
    // If the current page is an actors taxonomy
    if ( is_tax( 'actors' ) ) {
        // Assign the actors taxonomy template file
        $template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/taxonomy-actors.php';
    }
    // Return the template
    return $template;
}
// Apply the filter to taxonomy_template hook
add_filter( 'taxonomy_template', 'my_plugin_taxonomy_template' );

// Function to load template for various pages
function my_plugin_load_template( $template ) {

    // If the current page is actors page
    if ( is_page( 'actors' ) ) {
        // Assign the actors page template file
        $template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/page-actors.php';
    }

    // If the current page is front page
    if ( is_front_page( ) ) {
        // Assign the front page template file
        $template = plugin_dir_path( dirname( __FILE__ ) ) .  '/templates/home-template.php';
    }

    // If the current page is movie archive and not an actors taxonomy
    if ( is_archive( 'movies' ) && !is_tax('actors')  ) {
        // Assign the movies archive template file
        $template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/archive-movies.php';
    }

    // Return the template
    return $template;
}
// Apply the filter to template_include hook
add_filter( 'template_include', 'my_plugin_load_template' );

// Function to load custom post type template for movies
function my_custom_post_type_template() {
    // If the current page is a single movie post
    if (is_singular('movies')) {
        // Get the global wp_query object
        global $wp_query;
        // Get the current post from wp_query
        $post = $wp_query->post;
        
        // If the post exists and its post type is movies
        if (!empty($post) && $post->post_type === 'movies') {
            // Assign the single movie template file
            $template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/single-movies.php';
            // Include the template
            include $template;
            // Exit the function
            exit;
        } else {
            // If the movie doesn't exist, display an error message
            echo 'Sorry, the movie you are looking for does not exist.';
            // Exit the function
            exit;
        }
    }
}
// Apply the action to template_redirect hook
add_action('template_redirect', 'my_custom_post_type_template');

// Function to add header metadata
function header_metadata() { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php }

add_action( 'wp_head', 'header_metadata' );