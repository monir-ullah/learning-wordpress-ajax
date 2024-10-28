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

  // Login Form

  $("#user_login_form").on("submit", function (event) {
    event.preventDefault();

    const wpNonce = $("#user_login_form input#_wpnonce").val();
    const user_login = $("#user_login_form input#user_name").val();
    const user_password = $("#user_login_form input#user_password").val();

    $.ajax({
      url: ajax_form_variable.ajax_url,
      method: "POST",
      data: {
        action: "user_login_form_action",
        _ajax_nonce: wpNonce,
        user_login,
        user_password,
      },
      success: function (response) {
        let user_message = $("#user_login_message");
        if (!response.success) {
          user_message.append(response.error_message);
        }

        if (response.success && response.success_message) {
          user_message.append("");
          user_message.append(response.success_message);
          setTimeout(function () {
            location.reload();
          }, 2000);
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
});
