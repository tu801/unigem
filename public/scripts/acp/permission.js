/**
 * @author tmtuan
 */

 const vPermissions = Vue.createApp({
  data() {
      return {
          permissions: [],
          selected_per_groups: {
              admin: {
                  title: null,
                  objects: [],
              },
          },
          selected_group_key: "acp",
          current_pers_item: null,
          isActivePers: false,
          action: "",
          pers_title: "Thêm phân quyền",
          dataTable: null,
          loading: false,
      };
  },
  mounted: function () {
      this.loading = true;
      var persConfigUrl = bkUrl + "/permission/per-groups";
      var listPersUrl = bkUrl + "/permission/list-pers";

      $.ajax({
          url: listPersUrl,
          method: "GET",
          dataType: "json",
          success: (response) => {
              if (response.error === 0) {
                  this.permissions = response.data;
              } else {
                  Swal.fire({
                      icon: "error",
                      title: "Lỗi...",
                      text: response.message,
                  });
              }
              this.loading = false;
          },
          error: (_xhr, _error) => {
              this.loading = false;
          },
      }).then(() => {
          this.renderDataTable();
      });

      $.ajax({
          url: persConfigUrl,
          method: "GET",
          dataType: "json",
          success: (response) => {
              if (response.error == 0) {
                  this.selected_per_groups = response.data;
              } else {
                  Swal.fire({
                      icon: "error",
                      text: response.message,
                  });
              }
          },
      });

      $(this.$refs.persModal).on("hidden.bs.modal", this.onHidePers);
  },
  methods: {
      onCreate() {
          this.isActivePers = true;
          this.action = "add";
          this.current_pers_item = null;
          this.pers_title = "Thêm phân quyền";
          $("#vPersModal").modal("show");
      },
      onEdit(item) {
          this.isActivePers = true;
          this.action = "edit";
          this.pers_title = "Chỉnh sửa phân quyền";
          this.current_pers_item = item;
          $("#vPersModal").modal("show");
      },
      onCopy(item) {
          this.isActivePers = true;
          this.action = "add";
          this.pers_title = "Thêm phân quyền";
          this.current_pers_item = item;
          $("#vPersModal").modal("show");
      },
      onDelete(item, token, token_key) {
          let message =
              "Bạn chắc chắn muốn xóa quyền này chứ? <br>Tên phân quyền: " +
              item.description;

          Swal.fire({
              title: message,
              icon: "warning",
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: "Đồng ý",
              cancelButtonText: "Bỏ qua",
              cancelButtonColor: "#20c997",
              confirmButtonColor: "#DD6B55",
          }).then((result) => {
              /* Delete item */
              if (result.isConfirmed) {
                  let url = bkUrl + "/permission/del-permission";
                  let formData = new FormData();
                  formData.append("id", item.id);
                  formData.append(token_key, token);
                  $.ajax({
                      url: url,
                      data: formData,
                      contentType: false,
                      processData: false,
                      dataType: "json",
                      type: "POST",
                      success: (response) => {
                          if (response.error == 1) {
                              SwalAlert.fire({
                                  icon: "error",
                                  title: response.message,
                              });
                          } else {
                              for (var i = 0; i < this.permissions.length; i++) {
                                  if (this.permissions[i].id == item.id) {
                                      this.permissions.splice(i, 1);
                                  }
                              }
                              //remove jquery datatable
                              $("#tblPermission").DataTable().destroy();
                              this.renderDataTable();

                              SwalAlert.fire({
                                  icon: "success",
                                  title: response.message,
                              });
                          }
                      },
                      error: function (jqXHR, textStatus, errorThrow) {
                          SwalAlert.fire({
                              icon: "error",
                              title: "Có lỗi xảy ra! Vui lòng thử lại sau",
                          });
                      },
                  });
              } else if (result.isDenied) {
                  return false;
              }
          });
      },
      onHidePers() {
          this.isActivePers = false;
          this.current_pers_item = null;
          $("#vPersModal").modal("hide");
      },
      onUpdatePageMore(item) {
          if (this.action == "add" && item.id != undefined) {
              this.permissions.push(item);
          } else if (this.action == "edit") {
              for (var i = 0; i < this.permissions.length; i++) {
                  if (this.permissions[i].id == item.id)
                      this.permissions.splice(i, 1, item);
              }
          }
          // //remove jquery datatable
          $("#tblPermission").DataTable().destroy();
          //asked vue to flush old data then create new datatable
          this.$nextTick(function () {
              // alert("here");
              $("#tblPermission").DataTable({
                  responsive: true,
                  lengthChange: false,
                  autoWidth: false,
                  info: false,
                  language: {
                      paginate: {
                          previous: "Trước",
                          next: "Tiếp",
                      },
                      search: "Tìm kiếm:",
                  },
              });
          });
          this.action = "";
          this.isActivePers = false;
          this.current_pers_item = null;
          $("#vPersModal").modal("hide");
      },
      renderDataTable() {
          //asked vue to flush old data then create new datatable
          this.$nextTick(function () {
              $("#tblPermission").DataTable({
                  paging: true,
                  lengthChange: false,
                  searching: true,
                  ordering: true,
                  info: true,
                  autoWidth: false,
                  responsive: true,
              });
          });
      }
  },
});

// add: vpers-modal
vPermissions.component("vpers-modal", {
  template: "#vpers-modal-tmpl",
  props: [
      "refs_pers_item",
      "header",
      "group_main_info",
      "groupkey",
      "pers_item_action",
      "group_data_options",
      "active",
  ],
  data() {
      return {
          permission: {
              id: "",
              name: "",
              group: "",
              description: "",
              action: "",
          },
          errors: [],
      };
  },
  methods: {
      onSubmit() {
          this.errors = [];
          if (this.permission.name === "") {
              this.errors.push("Vui lòng nhập tên mô-đun");
          }
          if (this.permission.description === "") {
              this.errors.push("Vui lòng nhập mô tả");
          }
          if (this.permission.action === "") {
              this.errors.push("Vui lòng nhập method");
          }
          if (this.errors.length > 0) return;
          let csName = $("#csname").val();
          let csToken = $("#cstoken").val();
          let url = "";
          if (this.pers_item_action === "edit") {
              url = bkUrl + "/permission/update/" + this.permission.id;
          } else if (this.pers_item_action === "add") {
              url = bkUrl + "/permission/create";
          }
          let formData = new FormData();
          if (this.permission.group == "") {
              this.permission.group = this.groupkey;
          }
          for (const [key, _value] of Object.entries(this.permission)) {
              formData.append(key, this.permission[key]);
          }
          // add a sign token
          formData.append(csName, csToken);
          // send to
          $.ajax({
              url: url,
              data: formData,
              dataType: "json",
              contentType: false,
              processData: false,
              type: "POST",
              success: (response) => {
                  if (response.error == 1) {
                      SwalAlert.fire({
                          icon: "error",
                          title: response.message,
                      });
                  } else {
                      this.$emit("add-success-more-per", response.data);
                      $("#csname").val(response._csname);
                      $("#cstoken").val(response._cstoken);

                      this.clearForm();
                      SwalAlert.fire({
                          icon: "success",
                          title: response.message,
                      });
                  }
              },
              error: (_xhr, _status, _error) => {
                  SwalAlert.fire({
                      icon: "error",
                      title: "Có lỗi xảy ra! Vui lòng làm mới trình duyệt và thử lại sau",
                  });
              },
          });
      },
      clearForm() {
          this.permission = {
              id: "",
              name: "",
              group: "",
              description: "",
              action: "",
          };
      },
      closeModal() {
          this.$emit("close-pers-modal");
          this.clearForm();
      },
  },
  watch: {
      refs_pers_item(newVal, oldVal) {
          if (newVal) {
              this.permission.id = newVal.id;
              this.permission.group = newVal.group;
              this.permission.description = newVal.description;
              this.permission.name = newVal.name;
              this.permission.action = newVal.action;
          }
      },
  },
});

vPermissions.mount("#lstPermissions");
