jQuery(document).ready(function ($) {
  // User Profile Update

  $("#user_profile_update").on("submit", function (event) {
    event.preventDefault();

    // getting nonce value from user profile update forms
    const wpNonce = $("#user_profile_update input#_wpnonce").val();

    $.ajax({
      url: ajax_form_variable.ajax_url,
      method: "POST",
      data: {
        action: "update_user_profile_form",
        _ajax_nonce: wpNonce,
        display_name: $("#user_profile_update input#display_name").val(),
        user_email: $("#user_profile_update input#user_email").val(),
      },
      success: function (response) {
        console.log("Success:", response);
        setTimeout(function () {
          location.reload();
        }, 12000);
      },
      error: function (error) {
        alert("Error:", error.message);
        console.log(error);
      },
    });
  });
});
