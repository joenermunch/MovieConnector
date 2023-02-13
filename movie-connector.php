<?php
/*
    Plugin Name: Movie Database Connector
    Description: Connects to the MovieDatabase API
    Version: 1.0
    Author: Joener Münch
*/

require_once plugin_dir_path( __FILE__ ) . 'includes/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/taxonomies.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/retrieve-movies.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/templates.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/schedule.php';