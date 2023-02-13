<?php 
    /**
     * Template Name: Single Movie
     */
    get_header();
    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/header-template.php' );

    while ( have_posts() ) : the_post();

        $db_id = get_post_meta(get_the_ID(), 'db_id', true);
        $poster = 'https://image.tmdb.org/t/p/w500' . get_post_meta(get_the_ID(), 'poster', true);
        $alternative_titles = get_post_meta(get_the_ID(), 'alternative_titles', true);
        $overview = get_post_meta(get_the_ID(), 'overview', true);
        $release_date = date("F d, Y", get_post_meta(get_the_ID(), 'release_date', true));            
        $original_language = get_post_meta(get_the_ID(), 'original_language', true);
        $popularity = get_post_meta(get_the_ID(), 'popularity', true);
        $movie_trailer = get_post_meta(get_the_ID(), 'movie_trailer', true);
        $production_companies = get_post_meta(get_the_ID(), 'production_companies', true);
        $movie_reviews = get_post_meta(get_the_ID(), 'movie_reviews', true);
        $similar_movies = get_post_meta(get_the_ID(), 'similar_movies', true);
        $overview = get_post_meta(get_the_ID(), 'overview', true);
        $genres = get_the_terms( get_the_ID(), 'movie_genre' );
        $actors = get_the_terms( get_the_ID(), 'actors' );

        ?>

        <div class="single-movie-container">
            <div class="main-margin">
                <div class="inner-columns">
                    <div class="image-container">
                        <img src="<?php echo $poster; ?>" alt="<?php echo get_the_title(); ?>"/>
                    </div>
                    <div class="text-container">

                        <h1><?php echo get_the_title(); ?></h1>

                        <?php 
                            if ( $genres && ! is_wp_error( $genres ) ) {
                                echo '<ul class="post-genres">';
                                foreach ( $genres as $genre ) {
                                echo '<li>' . $genre->name . '</li>';
                                }
                                echo '</ul>';
                            }
                        ?>

                        <?php if($popularity) { ?>
                            <p class="popularity">Popularity: <span><?php echo $popularity; ?></span></p>
                        <?php } ?>                  

                        <p class="stat">Release Date: <span><?php echo date("F d, Y", strtotime($release_date)); ?></span></p>

                        <?php if($alternative_titles) { ?>
                            <p class="stat">Also known as: <span><?php echo $alternative_titles; ?><span></p>
                        <?php } ?>
                        
                        <?php if($original_language) { ?>
                            <p class="stat og">Original Language: <span><?php echo $original_language; ?><span></p>
                        <?php } ?>    

                        <?php if($movie_trailer) { ?>
                            <iframe id="player" type="text/html" width="100%" height="390"
                            src="http://www.youtube.com/embed/<?php echo $movie_trailer; ?>"
                            frameborder="0"></iframe>
                        <?php } ?>

                        <?php if($overview) { ?>
                            <p class="overview"><?php echo $overview; ?></p>
                        <?php } ?>  

                        <?php 
                            if ( $actors && ! is_wp_error( $actors ) ) {
                                echo '<div class="cast-container">';
                                echo '<h2>Cast</h2>';
                                echo '<ul class="actors-list">';
                                foreach ( $actors as $actor ) {
                                    $actor_link = get_term_link( $actor );
                                    echo '<li><a href="' . esc_url( $actor_link ) . '">' . esc_html( $actor->name ) . '</a></li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                        ?> 
                        <?php
                            if ( $production_companies ) {
                                echo '<div class="companies-container">';
                                echo '<h2>Production Companies</h2>';
                                echo '<ul class="company-list">';
                                foreach($production_companies as $company) {
                                    echo '<li class="company">';
                                    echo  $company['name'];
                                    echo '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';          
                            }
                        ?>
                        <?php
                            if (isset($similar_movies[0])) {
                                echo '<div class="similar-container">';
                                echo '<h2>Similar Movies</h2>';
                                echo '<ul class="similar-movies">';
                                foreach ($similar_movies as $movie) {
                                    echo '<li>' . $movie['title'] . '</li>';                        
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                        ?>
                        <?php 
                            if (isset($movie_reviews[0])) {
                                echo '<div class="reviews-container">';
                                echo '<h2>Reviews</h2>';
                                echo '<ul class="reviews">';
                                foreach ($movie_reviews as $review) {
                                    $author = $review['author_details']['name'];
                                    $content = $review['content'];
                                    $rating = intval($review['author_details']['rating']);
                                    $stars = str_repeat('&#9733;', $rating);
                                    echo '<li><div class="review-header">';                      
                                    echo '<h3 class="review-author">' . $review['author_details']['name'] . ' (' . $review['author'] . ')</h3>';
                                    echo '<span class="review-rating">' . $stars . '</span>';
                                    echo '</div><div class="review-content"><p>"' . $review['content'] . '"</p></div></li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                        ?>

                    </div>
                </div>
            </div>
        </div>

    <?php endwhile;
    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/footer-template.php' );

?>