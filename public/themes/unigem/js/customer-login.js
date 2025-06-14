document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const loginButton = document.getElementById("login-submit");
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
    $.ajax({
      url: "/ajax/customer-login",
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
          toastr.error(data.message || loginValidateMessage.somethingWentWrong);
        }
        loginButton.disabled = false;
      },
      error: function (xhr) {
        toastr.error("An error occurred. Please try again.");
      },
    });
  });
});
