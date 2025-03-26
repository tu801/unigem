//add component add user group
const vaddGroup = {
    template: "#vaddgroup-tmpl",
    props: ["groupeditdata", "header", "token", "tkname"],
    data() {
        return {
            userg: {
                id: 0,
                name: "",
                description: "",
                permissions: "",
                created_at: "",
            },
        };
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", () => {
            this.clearForm();
        });
    },
    methods: {
        onSubmit() {
            if (this.userg.name === "") {
                SwalAlert.fire({
                    icon: "error",
                    title: "Vui lòng điền tên nhóm!",
                });
            } else {
                let url = bkUrl + "/user/update-group";

                let formData = new FormData();
                for (const [key, _value] of Object.entries(this.userg)) {
                    formData.append(key, this.userg[key]);
                }
                formData.append(this.tkname, this.token);

                $.ajax({
                    url: url,
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    type: "POST",
                    success: (response) => {
                        //console.log("Update: ", response);
                        if (response.error == 1) {
                            SwalAlert.fire({
                                icon: "error",
                                title: response.errMess,
                            });
                        } else {
                            this.$emit("add-success", response.data);
                            this.clearForm();
                            let mess = !this.userg.id
                                ? "Đã update thành công group #" + response.data.name
                                : "Đã thêm thành công nhóm mới!";
                            SwalAlert.fire({
                                icon: "success",
                                title: mess,
                            });
                        }
                    },
                    statusCode: {
                        403: function () {
                            SwalAlert.fire({
                                icon: "error",
                                title:
                                    "Có lỗi xảy ra! Vui lòng refresh lại trình duyệt rồi thử lại!",
                            });
                        },
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        SwalAlert.fire({
                            icon: "error",
                            title: "Có lỗi xảy ra! Vui lòng thử lại sau",
                        });
                    },
                });
            }
        },
        clearForm() {
            this.userg.id = null;
            this.userg.name = "";
            this.userg.description = "";
            this.userg.permissions = "";
            this.userg.created_at = "";
        },
    },
    watch: {
        groupeditdata: function (newVal, oldVal) {
            if (newVal) {
                this.userg.id = newVal.id;
                this.userg.name = newVal.name;
                this.userg.permissions = newVal.permissions;
                this.userg.description = newVal.description;
                this.userg.created_at = newVal.created_at;
            }
        },
    },
};

//create userGroup app
const app = Vue.createApp({
    data() {
        return {
            userg_list: [],
            edit_data: null,
            showmodal: false,
            action: "",
            mdheader: "Thêm nhóm",
            dataTable: null,
        };
    },
    mounted() {
        let listUrl = bkUrl + "/user/get-groups";
        $.ajax({
            url: listUrl,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.data) {
                    //console.log("response.data", response.data);
                    this.userg_list = response.data;
                }
            }.bind(this),
        });
    },
    methods: {
        addGroup() {
            this.showmodal = true;
            this.action = "add";
            this.edit_data = null;
            this.mdheader = "Thêm nhóm";
            $("#vAddGroupModal").modal("show");
        },
        addGroupSuccess(group) {
            if (this.action == "add") if (group.id) this.userg_list.push(group);
            if (this.action == "edit") {
                for (var i = 0; i < this.userg_list.length; i++) {
                    if (this.userg_list[i].id == group.id)
                        this.userg_list.splice(i, 1, group);
                }
            }
            //remove jquery datatable
            $("#usergTbl").DataTable().destroy();
            //asked vue to flush old data then create new datatable
            this.$nextTick(function () {
                $("#usergTbl").DataTable({});
            });
            this.action = "";
            this.showmodal = false;
            this.edit_data = null;
            $("#vAddGroupModal").modal("hide");
        },
        editGroup(group) {
            this.showmodal = true;
            this.action = "edit";
            this.mdheader = "Edit nhóm";
            this.edit_data = group;
            $("#vAddGroupModal").modal("show");
        },
        created_view(groupData) {
            return moment(groupData.created_at).format("d/M/Y");
        },
        per_url(group) {
            return bkUrl + "/user/group/permission/" + group.id;
        },
        delGroup(group) {
            let message =
                "Bạn chắc chắn muốn xóa nhóm #" +
                group.id +
                " này chứ? <br>Tên nhóm: " +
                group.name;
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
                    let url = bkUrl + "/user/del-group/" + group.id;

                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: "GET",
                        success: (response) => {
                            if (response.error == 1) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: response.errMess,
                                });
                            } else {
                                for (var i = 0; i < this.userg_list.length; i++) {
                                    if (this.userg_list[i].id == group.id)
                                        this.userg_list.splice(i, 1);
                                }
                                //remove jquery datatable
                                $("#usergTbl").DataTable().destroy();
                                //asked vue to flush old data then create new datatable
                                this.$nextTick(function () {
                                    $("#usergTbl").DataTable({});
                                });

                                SwalAlert.fire({
                                    icon: "success",
                                    title: response.text,
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
    },
    updated() {
        if (!this.dataTable) this.dataTable = $("#usergTbl").DataTable({});
    },
});

app.component("vaddgroup", vaddGroup);
app.mount("#usergApp");
