document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("subscribe-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const emailInput = document.getElementById("subscribe-email");
    const email = emailInput.value.trim();
    const tmtTokenInput = document.getElementById("tmtToken");
    const tmtToken = tmtTokenInput ? tmtTokenInput.value.trim() : "tmt_token";
    const tmtHashInput = document.getElementById("tmtHashField");
    const tmtHashField = tmtHashInput
      ? tmtHashInput.value.trim()
      : "tmt_hash_field";

    // Simple email validation regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      toastr.error("Email không hợp lệ!");
      return;
    }

    // Disable button to prevent multiple submits
    const submitBtn = document.getElementById("subscribe-button");
    submitBtn.disabled = true;

    let formData = new FormData();
    formData.append("subscribeEmail", email);
    formData.append(tmtToken, tmtHashField);

    $.ajax({
      url: "/ajax/subscribe-email",
      method: "POST",
      dataType: "json",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {
        if (data.error) {
          toastr.error(data.message || "Có lỗi xảy ra, vui lòng thử lại!");
        } else {
          if (data.code == 200) {
            toastr.success(data.message || "Đăng ký thành công!");
            form.reset();
          } else {
            toastr.error(data.message || "Có lỗi xảy ra, vui lòng thử lại!");
          }
        }
      },
      error: function () {
        toastr.error("Can not connect to the server!");
      },
      complete: function () {
        submitBtn.disabled = false;
      },
    });
  });
});
