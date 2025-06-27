/**
 * @author tmtuan
 * created Date: 8/22/2023
 * project: unigem
 */

// const app = Vue.createApp({
//   components: {
//     "feature-img": featureImg,
//   },
//   data() {
//     return {
//       file: "",
//       demofile: "",
//       showImg: 0,
//     };
//   },
//   mounted() {
//     if (typeof post_img !== "undefined" && post_img != "") {
//       this.post_img = jQuery.parseJSON(post_img);
//       this.demofile = this.post_img;
//     }
//   },
//   method: {
//     deleteUploadFile(images) {
//       this.selectedImg = images;
//     },
//     removeFile(img) {
//       console.log(img);
//       for (var i = 0; i < this.selectedImg.length; i++) {
//         if (this.selectedImg[i].id == img.id) {
//           this.selectedImg.splice(i, 1);
//         }
//       }
//       if (this.selectedImg.length === 0) this.showImg = 0;
//       this.setAttachFiles(this.selectedImg);
//     },
//   },
// });
// app.mount("#postApp");

$(document).ready(function () {
  $("#cus_datepicker").datepicker({
    todayHighlight: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    endDate: new Date(),
  });

  // handle select country
  const countryElement = $("#country");
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

  const provinceElement = $(".select_province");
  const districtElement = $(".select_district");
  const wardElement = $(".select_ward");

  function toggleAddressFields() {
    var selectedValue = countryElement.val();
    var selectedOption = countryElement.find(
      'option[value="' + selectedValue + '"]'
    );
    var countryCode = selectedOption.attr("data-code");
    console.log("- Selected Value: ", selectedOption);
    console.log("- Country Code: ", countryCode);

    if (countryCode == "VN") {
      countryElement.parent().parent().removeClass("col-12");
      countryElement.parent().parent().addClass("col-6");
      provinceElement.parent().parent().removeClass("d-none");
      districtElement.parent().parent().removeClass("d-none");
      wardElement.parent().parent().removeClass("d-none");
    } else {
      countryElement.parent().parent().removeClass("col-6");
      countryElement.parent().parent().addClass("col-12");
      provinceElement.parent().parent().addClass("d-none");
      districtElement.parent().parent().addClass("d-none");
      wardElement.parent().parent().addClass("d-none");
    }
  }

  // Set selected value sau khi bind event
  const country_selected_value = $("#country").attr("country-selected");
  if (country_selected_value !== undefined && country_selected_value > 0) {
    $("#country").val(country_selected_value);
    $("#country").trigger("change");
  }

  countryElement.on("change", toggleAddressFields);
  toggleAddressFields();
});
