jQuery(document).ready(function ($) {
  // Click event for the "retrieve-movies" button
  $("#retrieve-movies").click(function () {
    // Display a "loading" message
    $("#import-result").html("Loading...");

    // Perform an AJAX request
    $.ajax({
      url: movie_ajax_object.ajax_url,
      type: "post",
      data: {
        action: "retrieve_movies",
      },
      // Success callback function
      success: function (response) {
        // Update the "import-result" element with the response message
        $("#import-result").html(response.message);
      },
      // Error callback function
      error: function (jqXHR, textStatus, errorThrown) {
        // Update the "import-result" element with the error message
        $("#import-result").html(
          "An error occurred while importing the movies: " + errorThrown
        );
      },
    });
  });
});
