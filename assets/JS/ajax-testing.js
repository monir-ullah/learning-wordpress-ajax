jQuery(document).ready(function ($) {
  $("#submit-btn").click(function () {
    $.ajax({
      url: variable.ajax_url, 
      method: "POST",
      data: {
        action: "get_post_with_ajax",
        _ajax_nonce: variable.nonce, 
        post_per_page: 3,
      },
      success: function (response) {
        console.log("AJAX request successful:", response);
      },
      error: function (xhr, status, error) {
        console.log("AJAX request failed:", status, error);
      },
    });
  });
});
