jQuery(document).ready(function ($) {
  $("#submit-btn").click(function () {
    $.ajax({
      url: variable.ajax_url, // Provided by wp_localize_script
      method: "POST",
      data: {
        action: "get_post_with_ajax",
        _ajax_nonce: variable.nonce, // Ensure nonce matches the one created in PHP
        post_per_page: 3, // Ensure this matches the key checked in PHP
      },
      success: function (response) {
        console.log("AJAX request successful:", response);

        if (Array.isArray(response)) {
          var html = "<ul>";

          response.forEach(function (item) {
            html += "<li>" + item.post_title + "</li>";
          });

          html += "</ul>";

          jQuery("#show-post").html(html); // Display posts in the #show-post div
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX request failed:", status, error);
      },
    });
  });
});
