<?php 
    /**
    * Template Name: Actors Archive
    */
    get_header();
    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/header-template.php' );
    // If movie GET parameter is set and not empty
    
?>

<div class="actors-archive-container main-margin">

    <?php 
    if (isset($_GET['movie']) && $_GET['movie'] !== '') {
        $movie_id = $_GET['movie'];
        // Echo the title of the movie
        echo '<h2>Actors from the movie: '. get_the_title($movie_id) .'</h2>';
    } else {
        echo '<h2>Actors</h2>';
    }
    ?>

    <!-- Form for filtering the actors by movie -->
    <form class="filters" action="<?php echo esc_url( home_url( '/actors' ) ); ?>" method="get">
        <select name="movie">
            <!-- Default option for all movies -->
            <option value="">All Movies</option>
            <?php
            // Query all published movies
            $movies = get_posts( array(
                'post_type' => 'movies',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'actors',
                        'field' => 'slug',
                        'terms' => '',
                        'operator' => 'EXISTS'
                    )
                )
            ));
            // Loop through the movies
            foreach ( $movies as $movie ) {
                // If the movie id matches the selected movie
                if ($movie->ID == $movie_id ) {
                    // Set selected to selected
                    $selected = 'selected';
                } else {
                    // Otherwise set selected to empty
                    $selected = ''; 
                }
                // Echo the option for the movie
                echo '<option '. $selected .'  value="' . $movie->ID . '">' . $movie->post_title . '</option>';
            }
            ?>
        </select>
        <!-- Submit button for filtering the actors -->
        <input type="submit" value="Filter">
    </form>

    <?php
        // If movie GET parameter is set
        $movie_id = '';
        if (isset($_GET['movie'])) {
            // Set movie id to the GET parameter value
            $movie_id = $_GET['movie'];
        }
        // Query all actors that are related to the selected movie
        $actors = get_terms( array(
            'taxonomy' => 'actors',
            'hide_empty' => false,
            'object_ids' => $movie_id,
        ));        
        echo '<ul class="actors-archive-list">';
        // Loop through the actors
        foreach ( $actors as $actor ) {
            $profile_path = '';
            echo '<li>'; 
            echo '<a href="' . get_term_link( $actor ) . '">';
            if (isset(get_term_meta($actor->term_id, 'profile_path')[0])) {
                $profile_path = 'https://image.tmdb.org/t/p/w500' . get_term_meta($actor->term_id, 'profile_path')[0];
                echo '<img src="'. $profile_path.'" alt="'.$actor->name.'"/>';
            }            
            echo '<h2>' . $actor->name . '</h2>';
            echo '</a>'; 
            echo '</li>';
        }
        echo '</ul>';
    ?>
</div>

<?php include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/footer-template.php' ); ?>