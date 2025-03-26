/**
 * @author brianha289
 */

const vChkPost = Vue.createApp({
  data() {
    return {};
  },
  created: function () {
    this.fetchCategories();
  },
  methods: {
    fetchCategories() {
      var url = bkUrl + "/category/list-cat/post";
      $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        success: (response) => {
          if (response.error != 0) {
            Swal.fire({
              icon: "error",
              text: response.message,
              showDenyButton: false,
              showCancelButton: false,
              confirmButtonText: "Đồng ý",
              confirmButtonColor: "#DD6B55",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location = bkUrl + "/category/post";
              }
            });
          }
        },
      });
    },
  },
});

vChkPost.mount("#chkCategory");
