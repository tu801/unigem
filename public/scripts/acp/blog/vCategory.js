/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
const catList = Vue.createApp({
  data() {
    return {
      cat_type: "",
      action: "",
      categories: [],
      allCategories: null, // Thêm thuộc tính này để lưu trữ danh sách gốc
      loading: true,
      searchkey: "",
    };
  },
  created() {
    this.fetchCategories();
  },
  methods: {
    renderEditUrl(cat) {
      return bkUrl + "/category/edit/" + cat.id;
    },
    catParent(cat) {
      if (cat.parent_id > 0) return cat.parent.title;
      else return "";
    },
    delCat(id) {
      var url = bkUrl + "/category/remove/" + id;

      Swal.fire({
        title: "Bạn có chắc chắn muốn xóa danh mục này??",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: `Xóa`,
        cancelButtonColor: "#20c997",
        confirmButtonColor: "#DD6B55",
      }).then((result) => {
        /* Delete item */
        if (result.isConfirmed) {
          window.location.replace(url);
        } else if (result.isDenied) {
          return false;
        }
      });
      return false;
    },
    onSearch() {
      if (this.searchkey.length > 2) {
        // Lưu trữ tất cả categories gốc nếu chưa có
        if (!this.allCategories) {
          this.allCategories = [...this.categories];
        }

        // Tìm kiếm cục bộ trong categories
        const searchTerm = this.searchkey.toLowerCase();
        this.categories = this.allCategories.filter((cat) =>
          cat.title.toLowerCase().includes(searchTerm)
        );
      } else {
        // Nếu từ khóa tìm kiếm quá ngắn, hiển thị lại tất cả categories
        if (this.allCategories) {
          this.categories = [...this.allCategories];
        } else {
          this.fetchCategories();
        }
      }
    },
    fetchCategories() {
      var catType = $("#listCat").attr("data-cat-type");
      var dataAction = $("#listCat").attr("data-action");
      let actionParam = "";

      if (typeof dataAction != "undefined" && dataAction == "deleted")
        actionParam = "?deleted=1";

      var url = bkUrl + "/category/list-cat/" + catType + actionParam;
      console.log("Y:", url);

      this.cat_type = catType;

      $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
          if (response.error == 0) {
            this.categories = response.data;
            this.allCategories = [...response.data]; // Lưu trữ danh sách gốc
          } else {
            if (typeof dataAction != "undefined" && dataAction != "deleted") {
              const productCategoryCheck = localStorage.getItem(
                "product_category_is_empty"
              );

              if (parseInt(productCategoryCheck) !== 1) {
                console.log("Show message");
                Swal.fire({
                  icon: "error",
                  text: response.message,
                });
              } else {
                localStorage.removeItem("product_category_is_empty");
              }
            }
          }
          this.loading = false;
        }.bind(this),
      });
    },
  },
});
