
<?php
    /**
     * Template Name: Taxonomy Actors
     */
    get_header();

    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/header-template.php' );

    $actor_id = get_term_meta(get_queried_object_id(), 'db_actor_id')[0];

    // TMDb API Key
    $api_key = 'API_KEY_HERE';

    // TMDb API URL
    $url = 'https://api.themoviedb.org/3/person/'.$actor_id.'?api_key='.$api_key.'&language=en-US';

    // Get the data from the API
    $response = wp_remote_get($url);


    // Check if the response was successful
    if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {
        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        // Actor photo
        $photo = 'https://image.tmdb.org/t/p/w500'.$data['profile_path'];

        // Actor name
        $name = $data['name'];

        // Actor birthday
        $birthday = $data['birthday'];

        // Actor place of birth
        $place_of_birth = $data['place_of_birth'];

        // Actor day of death
        $day_of_death = $data['deathday'];

        // Actor website
        $website = $data['homepage'];

        // Actor popularity
        $popularity = $data['popularity'];

        // Actor bio
        $bio = $data['biography'];

    }

?>

<div class="actor-container main-margin">
    <div class="inner-columns">
        <div class="actor-photo">
            <img src="<?php echo $photo; ?>" alt="<?php echo $name; ?>">
        </div>
        <div class="actor-details">        
            <!-- Actor information -->
            <div class="actor-info">
                <h1><?php echo $name; ?></h1>
                <p><strong>Birthday:</strong> <?php echo date('F d, Y', strtotime($birthday)); ?></p>
                <p><strong>Place of Birth:</strong> <?php echo $place_of_birth; ?></p>
                <?php if ( !empty( $day_of_death ) ) : ?>
                    <p><strong>Day of Death:</strong> <?php echo $day_of_death; ?></p>
                <?php endif; ?>
                <?php if ( !empty( $website ) ) : ?>
                    <p><strong>Website:</strong> <a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a></p>
                <?php endif; ?>
                <p><strong>Popularity:</strong> <?php echo $popularity; ?></p>
                <p><strong>Bio:</strong> <?php echo $bio; ?></p>
            </div>
            <!-- Actor images -->
            <div class="actor-images">
                <h2>Gallery</h2>
                <div class="actor-images-grid">
                    <?php
                    $url = 'https://api.themoviedb.org/3/person/'.$actor_id.'/images?api_key='.$api_key;
                    $response = wp_remote_get($url);
                    if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {
                        $data = json_decode( wp_remote_retrieve_body( $response ), true );
                        $images = array_slice($data['profiles'], 0, 10);
                        foreach ( $images as $image ) {
                            echo '<div class="actor-image-item">';
                            echo '<img src="https://image.tmdb.org/t/p/w500'.$image['file_path'].'" alt="'.$name.'">';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Actor Movies -->
    <div class="actor-movies">
        <h2>Movies</h2>
        <div class="actor-movies-grid">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $poster = 'https://image.tmdb.org/t/p/w500' . get_post_meta(get_the_ID(), 'poster', true);
                    $release_date = date("F d, Y", get_post_meta(get_the_ID(), 'release_date', true));
                    ?>
                    <div class="actor-movie-item">
                        <a href="<?php the_permalink(); ?>">                           
                            <img src='<?php echo $poster; ?>'/>
                            <div class="text-container">
                            <h3><?php the_title(); ?></h3>
                            <p>Release Date: <?php echo $release_date;?></p>
                            </div>
                        </a>
                    </div>
                    <?php
                endwhile;
            else :
                echo '<p>No movies found.</p>';
            endif;
            ?>
        </div>
    </div>
</div>

<?php include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/footer-template.php' ); ?>

