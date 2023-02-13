<?php

    get_header();
    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/header-template.php' );


    // Define query arguments
    $query_args = array(
    'post_type' => 'movies',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
    );

    // Filter by movie genre
    if ( ! empty( $_GET['movie-genre'] ) ) {
    $query_args['tax_query'] = array(
        array(
        'taxonomy' => 'movie_genre',
        'field' => 'slug',
        'terms' => $_GET['movie-genre'],
        ),
    );
    }

    // Filter by release year
    if ( ! empty( $_GET['release-year'] ) ) {
        $start_date = $_GET['release-year'] . '-01-01';
        $end_date = $_GET['release-year'] . '-12-31';
        $query_args['meta_query'] = array(
        'relation' => 'AND',
        array(
            'key' => 'release_date',
            'value' => strtotime($start_date),
            'compare' => '>=',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => 'release_date',
            'value' => strtotime($end_date),
            'compare' => '<=',
            'type' => 'NUMERIC',
        ),
        );
        $query_args['orderby'] = 'meta_value_num';
        $query_args['meta_key'] = 'release_date';
    }

    // Search
    if ( ! empty( $_GET['s'] ) ) {
    $query_args['s'] = $_GET['s'];
    }

    // Run the query
    $movies = new WP_Query( $query_args );

?>

<div class="movie-archive main-margin">
    <h1>Movies</h1>
    <form class="filters" action="<?php echo esc_url( home_url( '/movies' ) ); ?>" method="get">
        <div class="search-container">
            <input type="text" id="s" placeholder="Search for movie" name="s" value="<?php echo esc_attr( isset( $_GET['s'] ) ? $_GET['s'] : '' ); ?>">
        </div>
        <div class="filter-container">
            <select id="movie-genre" name="movie-genre">
                <option value="">All Genres</option>
                <?php
                // Get movie genres
                $genres = get_terms( array(
                    'taxonomy' => 'movie_genre',
                    'hide_empty' => false,
                ) );
                // Display movie genres as options
                foreach ( $genres as $genre ) {
                    $selected = $_GET['movie-genre'] == $genre->slug ? ' selected' : '';
                    echo '<option value="' . $genre->slug . '"' . $selected . '>' . $genre->name . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="filter-container">
            <select id="release-year" name="release-year">
                <option value="">All Years</option>
                <?php
                // Get release years
                $years = array();
                while ( $movies->have_posts() ) {
                    $movies->the_post();
                    $unix_timestamp = get_post_meta(get_the_ID(), 'release_date', true);
                    $date = date('Y-m-d', $unix_timestamp);
                    $year = substr( $date, 0, 4 );
                    if ( ! in_array( $year, $years ) ) {
                        $years[] = $year;
                    }
                }
                wp_reset_postdata();
                // Sort release years
                sort( $years );
                // Display release years as options
                foreach ( $years as $year ) {
                    $selected = $_GET['release-year'] == $year ? ' selected' : '';
                    echo '<option value="' . $year . '"' . $selected . '>' . $year .'</option>';
                    }
                ?>
            </select>
        </div>        
        <input type="submit" value="Filter">
    </form>
    <ul class="movie-list">
        <?php
        while ( $movies->have_posts() ) {
            $movies->the_post();
            $poster = 'https://image.tmdb.org/t/p/w500' . get_post_meta(get_the_ID(), 'poster', true);
            $release_date = date("F d, Y", get_post_meta(get_the_ID(), 'release_date', true));
            $movie_genres = get_the_terms( get_the_ID(), 'movie_genre' );
            
            echo '<li>';
            echo '<a href="' . esc_url( get_permalink() ) . '">';
            echo '<img src="'.$poster.'" />';
            echo '<div class="text-container">';
            echo '<h2>' . get_the_title() . '</h2>';
            if ( $movie_genres && ! is_wp_error( $movie_genres ) ) {
                echo '<p>Genre: ';
                $genre_list = array();
                foreach ( $movie_genres as $genre ) {
                    $genre_list[] = $genre->name;
                }
                echo implode(', ', $genre_list);
                echo '</p>';
            }
            echo '<p>Release Date: ' . $release_date .'</p>';
            echo '</div>';            
        }
        wp_reset_postdata();
        ?>
    </ul>
</div>

<?php include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/footer-template.php' ); ?>