<?php

// Schedule the daily retrieval of movies
function schedule_retrieve_movies() {
    // Check if the event has not been scheduled
    if ( ! wp_next_scheduled( 'daily_retrieve_movies' ) ) {
        // Schedule the event to run every day
        wp_schedule_event( time(), 'daily', 'daily_retrieve_movies' );
    }
}
// Hook the schedule_retrieve_movies function to the 'wp' action
add_action( 'wp', 'schedule_retrieve_movies' );

// Function that runs the retrieve_movies function
function run_retrieve_movies() {
    // Call the retrieve_movies function
    retrieve_movies();
}
// Hook the run_retrieve_movies function to the 'daily_retrieve_movies' action
add_action( 'daily_retrieve_movies', 'run_retrieve_movies' );