document.addEventListener("DOMContentLoaded", function () {
  // handle login form submission
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const loginButton = document.getElementById("login-submit");
      const originalText = loginButton.textContent;
      const emailInput = document.getElementById("login-email");
      const email = emailInput.value.trim();
      const passInput = document.getElementById("login-password");
      const password = passInput.value.trim();
      const tmtTokenInput = document.getElementById("tmtToken");
      const tmtToken = tmtTokenInput ? tmtTokenInput.value.trim() : "tmt_token";
      const tmtHashInput = document.getElementById("tmtHashField");
      const tmtHashField = tmtHashInput
        ? tmtHashInput.value.trim()
        : "tmt_hash_field";

      // validate form data
      if (!email) {
        toastr.error(loginValidateMessage.emailRequired);
        return;
      }
      if (!password) {
        toastr.error(loginValidateMessage.passwordRequired);
        return;
      }
      // Simple email validation regex
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        toastr.error(loginValidateMessage.emailInvalid);
        return;
      }

      let formData = new FormData();
      formData.append("username", email);
      formData.append("password", password);
      formData.append(tmtToken, tmtHashField);

      loginButton.disabled = true;
      loginButton.textContent = loginValidateMessage.processing;

      $.ajax({
        url: "/ajax/customer/login",
        method: "POST",
        dataType: "json",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
          if (data.code == 200) {
            toastr.success(data.message || loginValidateMessage.loginSuccess);
            // redirect to main page
            setInterval(function () {
              window.location.href = "/";
            }, 500);
          } else {
            toastr.error(
              data.message || loginValidateMessage.somethingWentWrong
            );
          }
          // Enable lại nút login
          loginButton.disabled = false;
          loginButton.textContent = originalText;
        },
        error: function (xhr) {
          toastr.error(loginValidateMessage.somethingWentWrong);
          // Enable lại nút login
          loginButton.disabled = false;
          loginButton.textContent = originalText;
        },
      });
    });
  }
  // handle forgot password form submission
  const forgotPasswordForm = document.getElementById("forgotPasswordForm");
  if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const emailInput = document.getElementById("forgot-email");
      const email = emailInput.value.trim();
      const submitButton = document.getElementById("forgot-submit");
      const originalText = submitButton.textContent;

      // validate email
      if (!email) {
        toastr.error(loginValidateMessage.emailRequired);
        return;
      }
      // Simple email validation regex
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        toastr.error(loginValidateMessage.emailInvalid);
        return;
      }

      submitButton.disabled = true;
      submitButton.textContent = loginValidateMessage.processing;

      $.ajax({
        url: "/ajax/customer/forgot-password",
        method: "POST",
        dataType: "json",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
        data: { email: email },
        success: function (data) {
          if (data.code == 200) {
            toastr.success(
              data.message || loginValidateMessage.forgotPasswordSuccess
            );
          } else {
            toastr.error(
              data.message || loginValidateMessage.somethingWentWrong
            );
          }
          // Enable lại nút submit
          submitButton.disabled = false;
          submitButton.textContent = originalText;

          // close modal if exists
          $("#forgotPassword").modal("hide");
        },
        error: function (xhr) {
          toastr.error(loginValidateMessage.somethingWentWrong);
          // Enable lại nút submit
          submitButton.disabled = false;
          submitButton.textContent = originalText;
        },
      });
    });
  }
});
