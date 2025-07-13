$(document).ready(function () {
  var countryElement = $("#country");
  countryElement.select2({
    templateResult: function (state) {
      if (!state.id) return state.text;
      var flag = $(state.element).data("flag");
      if (flag) {
        return $(
          '<span><img src="' +
            flag +
            '" style="width:20px;height:14px;vertical-align:middle;object-fit:contain;margin-right:6px;">' +
            state.text +
            "</span>"
        );
      }
      return state.text;
    },
    templateSelection: function (state) {
      if (!state.id) return state.text;
      var flag = $(state.element).data("flag");
      if (flag) {
        return $(
          '<span><img src="' +
            flag +
            '" style="width:20px;height:14px;vertical-align:middle;object-fit:contain;margin-right:6px;">' +
            state.text +
            "</span>"
        );
      }
      return state.text;
    },
    escapeMarkup: function (m) {
      return m;
    },
  });

  function toggleAddressFields() {
    var selectedValue = countryElement.val();
    var selectedOption = countryElement.find(
      'option[value="' + selectedValue + '"]'
    );
    var countryCode = selectedOption.attr("data-code");

    if (countryCode == "VN") {
      $("#vietnam_address").removeClass("d-none");
      $("#other_country_address").addClass("d-none");
      $('#vietnam_address input[name="cus_address"]').prop("disabled", false);
      $('#other_country_address input[name="cus_address"]').prop(
        "disabled",
        true
      );
    } else {
      $("#vietnam_address").addClass("d-none");
      $("#other_country_address").removeClass("d-none");
      $('#vietnam_address input[name="cus_address"]').prop("disabled", true);
      $('#other_country_address input[name="cus_address"]').prop(
        "disabled",
        false
      );
    }
  }

  countryElement.on("change", toggleAddressFields);

  // Set selected value sau khi bind event
  var country_selected_value = $("#country").attr("country-selected");
  if (country_selected_value !== undefined && country_selected_value > 0) {
    $("#country").val(country_selected_value);
    $("#country").trigger("change");
  }

  toggleAddressFields();

  // Form validation
  $("#registerForm").on("submit", function (e) {
    e.preventDefault();

    // Reset previous error styles
    $(".tf-field").removeClass("error");
    $(".error-message").remove();

    let isValid = true;

    // Validate full name
    const fullName = $('input[name="cus_full_name"]');
    if (!fullName.val().trim()) {
      showError(fullName, errorMessages.fullNameRequired);
      isValid = false;
    }

    // Validate phone
    const phone = $('input[name="cus_phone"]');
    const phoneRegex = /^[0-9]{10,11}$/;
    if (!phone.val().trim()) {
      showError(phone, errorMessages.phoneRequired);
      isValid = false;
    } else if (!phoneRegex.test(phone.val().trim())) {
      showError(phone, errorMessages.phoneInvalid);
      isValid = false;
    }

    // Validate email
    const email = $('input[name="cus_email"]');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.val().trim()) {
      showError(email, errorMessages.emailRequired);
      isValid = false;
    } else if (!emailRegex.test(email.val().trim())) {
      showError(email, errorMessages.emailInvalid);
      isValid = false;
    }

    // Validate password
    const password = $('input[name="password"]');
    if (!password.val()) {
      showError(password, errorMessages.passwordRequired);
      isValid = false;
    } else if (password.val().length < 6) {
      showError(password, errorMessages.passwordMinLength);
      isValid = false;
    }

    // Validate password confirmation
    const passwordConfirm = $('input[name="password_confirm"]');
    if (!passwordConfirm.val()) {
      showError(passwordConfirm, errorMessages.passwordConfirmRequired);
      isValid = false;
    } else if (password.val() !== passwordConfirm.val()) {
      showError(passwordConfirm, errorMessages.passwordNotMatch);
      isValid = false;
    }

    // Validate country
    const country = $("#country");
    if (!country.val()) {
      showError(country, errorMessages.countryRequired);
      isValid = false;
    }

    // Validate Vietnam address fields if Vietnam is selected
    if ($("#vietnam_address").is(":visible")) {
      const province = $("#province");
      const district = $("#district");
      const ward = $("#ward");

      if (!province.val()) {
        showError(province, errorMessages.provinceRequired);
        isValid = false;
      }

      if (!district.val()) {
        showError(district, errorMessages.districtRequired);
        isValid = false;
      }

      if (!ward.val()) {
        showError(ward, errorMessages.wardRequired);
        isValid = false;
      }

      const vnAddress = $('#vietnam_address input[name="cus_address"]');
      if (!vnAddress.val().trim()) {
        showError(vnAddress, errorMessages.vnAddressRequired);
        isValid = false;
      }
    }

    // Validate other country address
    if ($("#other_country_address").is(":visible")) {
      const otherAddress = $(
        '#other_country_address input[name="cus_address"]'
      );
      if (!otherAddress.val().trim()) {
        showError(otherAddress, errorMessages.addressRequired);
        isValid = false;
      }
    }

    // If all valid, submit form
    if (isValid) {
      // Show loading state
      const submitBtn = $(this).find('button[type="submit"]');
      const originalText = submitBtn.text();
      submitBtn.prop("disabled", true).text(errorMessages.processing);

      // Submit form
      this.submit();
    } else {
      // Scroll to first error
      const firstError = $(".tf-field.error").first();
      if (firstError.length) {
        $("html, body").animate(
          {
            scrollTop: firstError.offset().top - 100,
          },
          500
        );
      }
    }
  });

  // Function to show error
  function showError(element, message) {
    const fieldContainer = element.closest(".tf-field, .mb_15, fieldset");
    fieldContainer.addClass("error");

    // Remove existing error message
    fieldContainer.find(".error-message").remove();

    // Add error message
    fieldContainer.append(
      '<div class="error-message text-danger mt-1 small">' + message + "</div>"
    );
  }

  // Remove error on input focus
  $("input, select").on("focus change", function () {
    const fieldContainer = $(this).closest(".tf-field, .mb_15, fieldset");
    fieldContainer.removeClass("error");
    fieldContainer.find(".error-message").remove();
  });

  // Real-time validation for email
  $('input[name="cus_email"]').on("blur", function () {
    const email = $(this).val().trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email && !emailRegex.test(email)) {
      showError($(this), errorMessages.emailInvalid);
    }
  });

  // Real-time validation for phone
  $('input[name="cus_phone"]').on("blur", function () {
    const phone = $(this).val().trim();
    const phoneRegex = /^[0-9]{10,11}$/;

    if (phone && !phoneRegex.test(phone)) {
      showError($(this), errorMessages.phoneInvalid);
    }
  });

  // Real-time validation for password match
  $('input[name="password_confirm"]').on("blur", function () {
    const password = $('input[name="password"]').val();
    const confirmPassword = $(this).val();

    if (confirmPassword && password !== confirmPassword) {
      showError($(this), errorMessages.passwordNotMatch);
    }
  });
});
