jQuery(document).ready(function ($) {
  // Showing post in the admin panel
  $("#submit-btn").click(function () {
    $.ajax({
      url: variable.ajax_url,
      method: "POST",
      data: {
        action: "get_post_with_ajax",
        _ajax_nonce: variable.nonce,
        post_per_page: 10,
      },
      success: function (response) {
        if (Array.isArray(response)) {
          console.log(response);
          // $("#show-post").html("This is array");
          let startUL = "<ul>";
          let endUL = "</ul>";
          let li = "";
          response.forEach(function (element) {
            li += `<li><a href="${element.guid}">${element.post_title}</a></li>`;
          });
          $("#show-post").append(startUL + li + endUL);
        } else {
          alert("This is not an array");
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX request failed:", status, error);
      },
    });
  });
});
