<?php 

function retrieve_movies() {   
    
    // API Key for themoviedb.org
    $api_key = 'API_KEY_HERE';

    // Array to store movie information
    $movies = array();

    // Variable to keep track of the page number
    $page = 8;

    // Variable to keep track of imported movies
    $imported_movies = 0;


    // Get popular movies using the API
    $url = 'https://api.themoviedb.org/3/movie/popular?api_key=' . $api_key . '&language=en-US&page=1';
    $response = wp_remote_get($url);

    // Check for errors in the response
    if (is_wp_error($response)) {
        return false;
    }

    // Decode the JSON response
    $popular_movies = json_decode(wp_remote_retrieve_body($response), true);

    // Get upcoming movies and loop through the pages
    while ($page <= 12) {
        
        
        $url = 'https://api.themoviedb.org/3/movie/upcoming?api_key=' . $api_key . '&language=en-US&page='.$page;

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return false;  
        }

        $page++;

        $upcoming_movies = json_decode(wp_remote_retrieve_body($response), true);

        // Combine the arrays of popular and upcoming movies
        $all_movies = array_merge($popular_movies['results'], $upcoming_movies['results']);

        // Remove duplicates from the array of all movies
        $all_movies = array_unique($all_movies, SORT_REGULAR);

        // Create a movie post for each movie
        foreach ($all_movies as $movie) {

            // Check if movie post already exists
            $existing_post = get_posts(array(
                'post_type' => 'movies',
                'meta_key' => 'db_id',
                'meta_value' => $movie['id'],
                'post_status' => 'any',
                'posts_per_page' => 1
            ));
            
            // If movie post doesn't exist, create new movie post
            if (!$existing_post) {
                $post = array(
                    'post_title' => $movie['title'],
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'movies'
                );
                $post_id = wp_insert_post($post);
                if ($post_id) {                    
                    // Add movie meta data
                    add_post_meta($post_id, 'db_id', $movie['id']);
                    add_post_meta($post_id, 'poster', $movie['poster_path']);
                    add_post_meta($post_id, 'alternative_titles', $movie['original_title']);
                    add_post_meta($post_id, 'overview', $movie['overview']);
                    add_post_meta($post_id, 'release_date', strtotime($movie['release_date']));
                    add_post_meta($post_id, 'original_language', $movie['original_language']);
                    add_post_meta($post_id, 'popularity', $movie['popularity']);

                    // Get movie details
                    $movie_details_url = 'https://api.themoviedb.org/3/movie/' . $movie['id'] . '?api_key=' . $api_key . '&language=en-US';

                    $movie_details_response = wp_remote_get($movie_details_url);

                    if (!is_wp_error($movie_details_response)) {

                        $movie_details = json_decode(wp_remote_retrieve_body($movie_details_response), true);
                        $genres = $movie_details['genres'];
                        $genre_names = array();

                        foreach ($genres as $genre) {
                            $genre_names[] = $genre['name'];
                        }
                
                        // Assign meta
                        wp_set_object_terms($post_id, $genre_names, 'movie_genre');
                        add_post_meta($post_id, 'production_companies', $movie_details['production_companies']);
                        add_post_meta($post_id, 'overview', $movie_details['overview']);
                    }

                    // Get movie trailer
                    $trailer_url = 'https://api.themoviedb.org/3/movie/' . $movie['id'] . '/videos?api_key=' . $api_key . '&language=en-US';

                    $trailer_response = wp_remote_get($trailer_url);

                    if (!is_wp_error($trailer_response)) {

                        $trailer_details = json_decode(wp_remote_retrieve_body($trailer_response), true);

                        if (count($trailer_details['results']) > 0) {
                            $trailer = $trailer_details['results'][0];
                            update_post_meta($post_id, 'movie_trailer', $trailer['key']);
                        }
                    }

                    // Get movie reviews
                    $reviews_url = 'https://api.themoviedb.org/3/movie/' . $movie['id'] . '/reviews?api_key=' . $api_key . '&language=en-US&page=1';

                    $reviews_response = wp_remote_get($reviews_url);

                    if (!is_wp_error($reviews_response)) {
                        $reviews_details = json_decode(wp_remote_retrieve_body($reviews_response), true);

                        if (count($reviews_details['results']) > 0) {
                            $reviews = $reviews_details['results'];
                            update_post_meta($post_id, 'movie_reviews', $reviews);
                        }
                    }

                    // Get similar movies
                    $similar_movies_url = 'https://api.themoviedb.org/3/movie/' . $movie['id'] . '/similar?api_key=' . $api_key . '&language=en-US&page=1';
                    
                    $similar_movies_response = wp_remote_get($similar_movies_url);
                    
                    if (!is_wp_error($similar_movies_response)) {
                        $similar_movies_details = json_decode(wp_remote_retrieve_body($similar_movies_response), true);

                        if (count($similar_movies_details['results']) > 0) {
                            $similar_movies = $similar_movies_details['results'];
                            update_post_meta($post_id, 'similar_movies', $similar_movies);
                        }
                    }                    

                    // Get movie cast
                    $url = 'https://api.themoviedb.org/3/movie/' . $movie['id'] . '/credits?api_key=' . $api_key;

                    $response = wp_remote_get($url);

                    if (!is_wp_error($response)) {
                        $movie_cast = json_decode(wp_remote_retrieve_body($response), true);
                        $i = 0;
                        foreach ($movie_cast['cast'] as $actor) {
                            if ($i <= 3) {
                                $actor_term = wp_set_object_terms($post_id, $actor['name'], 'actors', true);
                                $actor_term_id = $actor_term[0];
                                update_term_meta($actor_term_id, 'db_actor_id', $actor['id']);
                                update_term_meta($actor_term_id, 'popularity', $actor['popularity']);
                                update_term_meta($actor_term_id, 'profile_path', $actor['profile_path']);  
                                $i++;
                            }                            
                        }
                    }                    
                    $imported_movies++;
                }
            }
        }
    }

    return $imported_movies;
} 

// AJAX request handler

function retrieve_movies_ajax_handler() {
    $movies_imported = retrieve_movies();
    $response = array(
            'status' => 'success',
            'message' => sprintf('%d movies were imported successfully.', $movies_imported)
        );
    wp_send_json($response);
    wp_die();
}
add_action('wp_ajax_retrieve_movies', 'retrieve_movies_ajax_handler');