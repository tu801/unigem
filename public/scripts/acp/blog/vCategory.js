/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
const catList = Vue.createApp({
  data() {
    return {
      cat_type: "",
      dataAction: "",
      categories: [],
      allCategories: null, // Thêm thuộc tính này để lưu trữ danh sách gốc
      loading: true,
      searchkey: "",
      isRootAdmin: false,
    };
  },
  created() {
    var dataAction = $("#listCat").attr("data-action");
    this.dataAction = dataAction;
    this.fetchCategories();
  },
  methods: {
    renderEditUrl(cat) {
      return bkUrl + "/category/edit/" + cat.id;
    },
    renderRecoverUrl(cat) {
      return bkUrl + "/category/recover/" + cat.id;
    },
    catParent(cat) {
      if (cat.parent_id > 0) {
        if (cat.parent === undefined || cat.parent === null) {
          return (
            '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i></span> ' +
              cat.parent_not_found_message ?? ""
          );
        }
        return cat.parent.title;
      } else return "";
    },
    delCat(id) {
      var url = bkUrl + "/category/remove/" + id;
      console.log("remove cat: ", url);
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
      let actionParam = "";

      if (typeof this.dataAction != "undefined" && this.dataAction == "deleted")
        actionParam = "?deleted=1";

      var url = bkUrl + "/category/list-cat/" + catType + actionParam;

      this.cat_type = catType;

      $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
          if (response.error == 0) {
            this.isRootAdmin = response.isRootAdmin;
            this.categories = response.data;
            this.allCategories = [...response.data]; // Lưu trữ danh sách gốc
          } else {
            if (
              typeof this.dataAction != "undefined" &&
              this.dataAction != "deleted"
            ) {
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
