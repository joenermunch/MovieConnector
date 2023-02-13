<?php
$api_key = 'API_KEY_HERE';
$actor_id = get_query_var('actor_id');
$response = wp_remote_get("https://api.themoviedb.org/3/person/75341?api_key=$api_key");
$actor_data = json_decode(wp_remote_retrieve_body($response), true);

print_r($actor_data);

$name = isset($actor_data['name']) ? $actor_data['name'] : '';
$birthday = isset($actor_data['birthday']) ? $actor_data['birthday'] : '';
$place_of_birth = isset($actor_data['place_of_birth']) ? $actor_data['place_of_birth'] : '';
$deathday = isset($actor_data['deathday']) ? $actor_data['deathday'] : '';
$website = isset($actor_data['website']) ? $actor_data['website'] : '';
$popularity = isset($actor_data['popularity']) ? $actor_data['popularity'] : '';
$biography = isset($actor_data['biography']) ? $actor_data['biography'] : '';
$photo = isset($actor_data['profile_path']) ? 'https://image.tmdb.org/t/p/w500' . $actor_data['profile_path'] : '';

$response = wp_remote_get("https://api.themoviedb.org/3/person/$actor_id/images?api_key=$api_key");
$gallery_data = json_decode(wp_remote_retrieve_body($response), true);
$gallery = $gallery_data['profiles'];

$response = wp_remote_get("https://api.themoviedb.org/3/person/$actor_id/movie_credits?api_key=$api_key");
$movies_data = json_decode(wp_remote_retrieve_body($response), true);
$movies = $movies_data['cast'];
?>

<div class="actor-header">
  <img src="<?php echo $photo; ?>" alt="<?php echo $name; ?>">
  <h1><?php echo $name; ?></h1>
  <p><?php echo $birthday; ?></p>
  <p><?php echo $place_of_birth; ?></p>
  <p><?php echo $deathday; ?></p>
  <p><a href="<?php echo $website; ?>"><?php echo $website; ?></a></p>
  <p><?php echo $popularity; ?></p>
</div>

<div class="actor-bio">
  <h2>Bio</h2>
  <p><?php echo $biography; ?></p>
</div>

<div class="actor-gallery">
  <h2>Gallery</h2>
  <?php foreach (array_slice($gallery, 0, 10) as $image) : ?>
    <img src="https://image.tmdb.org/t/p/w500<?php echo $image['file_path']; ?>" alt="<?php echo $name; ?>">
  <?php endforeach; ?>
</div>

<div class="actor-movies">
  <h2>Movies</h2>
  <ul>
<?php foreach ($movies as $movie) : ?>
<li>
<img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?>">
<p><?php echo $movie['character']; ?></p>
<p><?php echo $movie['title']; ?></p>
<p><?php echo $movie['release_date']; ?></p>
</li>
<?php endforeach; ?>

  </ul>
</div>