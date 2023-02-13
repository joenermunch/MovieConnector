<?php 
    /**
     * Template Name: Home
     */
    get_header();
    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/header-template.php' );
?>

<div class="home-container">

    <div class="main-margin inner-container">

        <div class="movie-list-container">

            <h2>Upcoming Movies</h2>

            <?php 

                $today = strtotime(date('Y-m-d'));
                $current_month = '';


                $args = array(
                    'post_type' => 'movies',
                    'meta_key' => 'release_date',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'posts_per_page' => 10,
                    'meta_query' => array(
                        array(
                            'key' => 'release_date',
                            'value' => time(),
                            'compare' => '>'
                        )
                    )


                );




                $movies = new WP_Query( $args );


                echo '<ul class="movie-list home">';
                while ( $movies->have_posts() ) {
                    $movies->the_post();
                    $poster = 'https://image.tmdb.org/t/p/w500' . get_post_meta(get_the_ID(), 'poster', true);
                    $release_date = get_post_meta(get_the_ID(), 'release_date', true);
                    $current_year = date("Y", $release_date);
                    $month = date("F", $release_date);
                    $movie_genres = get_the_terms( get_the_ID(), 'movie_genre' );   
                
                    if ($current_year === date("Y")) {
                        if ($current_month !== $month) {
                            $current_month = $month;
                            echo '<h3 class="month">' . $current_month . '</h3>';
                        }
                
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
                        echo '<p>Release Date: ' . date("F d, Y", $release_date) .'</p>';
                        echo '</div>';
                        echo '</a>';
                        echo '</li>';  
                    }          
                }
                echo '</ul>';
                wp_reset_postdata();


            ?>

        </div>

        <div class="movie-list-container">

            <h2>Popular Actors</h2>

            <?php 

                $actors = get_terms( array(
                    'taxonomy' => 'actors',
                    'hide_empty' => false,
                    'meta_key' => 'popularity',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'number' => 10
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

       
        
        <div class="movie-list-container">

            <h2>Popular Movies</h2>

            <?php 

                $today = strtotime(date('Y-m-d'));

                $args_popular = array(
                    'post_type' => 'movies',
                    'meta_key' => 'popularity',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'posts_per_page' => 10,
                );

                $movies_popular = new WP_Query( $args_popular );

                echo '<ul class="movie-list home">';
                while ( $movies_popular->have_posts() ) {
                    $movies_popular->the_post();
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
                    echo '</a>';    
                    echo '</li>';     
                }
                echo '</ul>';
                wp_reset_postdata();



            ?>

        </div>
    </div>

</div>


    
<?php include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/footer-template.php' ); ?>