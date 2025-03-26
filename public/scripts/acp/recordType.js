/**
 * @author tmtuan
 * created Date: 9/14/2021
 * project: woh-tuyendung
 */

const vrecords = Vue.createApp({
    data() {
        return {
            recordTypes: [],
            current_item: null,
            isActive: false,
            action: "",
            records_modal_title: "Thêm Record Type",
            dataTable: null,
            loading: false,
        }
    },
    mounted() {
        this.loading = true;
        var listRecordsUrl = bkUrl + "/record-type/list-data";

        $.ajax({
            url: listRecordsUrl,
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.error === 0) {
                    this.recordTypes = response.data;
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi...",
                        text: response.message,
                    });
                }
                this.loading = false;
            }.bind(this),
            error: (_xhr, _error) => {
                this.loading = false;
            },
        }).then(()=>{
            this.renderDataTable();
        });

        $(this.$refs.recordsModal).on("hidden.bs.modal", this.onCloseModal);
    },
    methods: {
        onCreate() {
            this.isActive = true;
            this.action = "add";
            this.current_item = null;
            this.pers_title = "Thêm Record Type";
            $("#vRecordsModal").modal("show");
        },
        onEdit(item) {
            this.isActive = true;
            this.action = "edit";
            this.pers_title = "Chỉnh sửa Record Type";
            this.current_item = item;
            $("#vRecordsModal").modal("show");
        },
        onCloseModal() {
            this.isActive = false;
            this.current_item = null;
            $("#vRecordsModal").modal("hide");
        },
        onClone(item) {
            this.isActive = true;
            this.action = "add";
            this.pers_title = "Thêm Record Type";
            this.current_item = item;
            $("#vRecordsModal").modal("show");
        },
        onDelete(item, token, token_key) {
            let message =
                "Bạn chắc chắn muốn xóa Record này chứ? <br>Record Type: " +
                item.name;

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
                    let url = bkUrl + "/record-type/del-item";
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
                                    title: response.errMess,
                                });
                            } else {
                                for (var i = 0; i < this.recordTypes.length; i++) {
                                    if (this.recordTypes[i].id == item.id) {
                                        this.recordTypes.splice(i, 1);
                                    }
                                }
                                //remove jquery datatable
                                $("#tblRecords").DataTable().destroy();
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
        onAddRecordSuccess(item) {
            if (this.action == "add" && item.id != undefined) {
                this.recordTypes.push(item);
            } else if (this.action == "edit") {
                for (var i = 0; i < this.recordTypes.length; i++) {
                    if (this.recordTypes[i].id == item.id)
                        this.recordTypes.splice(i, 1, item);
                }
            }
            // //remove jquery datatable
            $("#tblRecords").DataTable().destroy();
            //asked vue to flush old data then create new datatable
            this.renderDataTable();
            this.action = "";
            this.isActive = false;
            this.current_item = null;
            $("#vRecordsModal").modal("hide");
        },
        renderDataTable() {
            this.$nextTick(function () {
                $("#tblRecords").DataTable({
                    lengthChange: false,
                    info: false,
                    searching: true,
                    ordering: true,
                    responsive: true,
                    autoWidth: false,
                    language: {
                        paginate: {
                            previous: "Trước",
                            next: "Tiếp",
                        },
                        search: "Tìm kiếm:",
                    },
                });
            });
        }
    }
});

//add record type modal
vrecords.component( 'vrecord-modal', {
    template: "#vrecord-modal-tmpl",
    props: [
        "refs_record_item",
        "header",
        "active",
        "modal_action",
    ],
    data() {
        return {
            record_type: {
                id: "",
                name: "",
                developer_name: "",
                object_type: "",
            },
            errors: [],
        };
    },
    methods: {
        onSubmit() {
            this.errors = [];
            if (this.record_type.name === "") {
                this.errors.push("Vui lòng nhập tên Record");
            }
            if (this.record_type.developer_name === "") {
                this.errors.push("Vui lòng nhập Developer Name");
            }
            if (this.record_type.object_type === "") {
                this.errors.push("Vui lòng nhập Object");
            }
            if (this.errors.length > 0) return;

            let csName = $("#csname").val();
            let csToken = $("#cstoken").val();
            let url = "";
            if (this.modal_action === "edit") {
                url = bkUrl + "/record-type/edit/" + this.record_type.id;
            } else if (this.modal_action === "add") {
                url = bkUrl + "/record-type/add";
            }
            let formData = new FormData();

            for (const [key, _value] of Object.entries(this.record_type)) {
                formData.append(key, this.record_type[key]);
            }
            // add a sign token
            formData.append(csName, csToken);

            // send data to server
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
                        this.$emit("add-success", response.data);
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
            this.record_type = {
                id: "",
                name: "",
                developer_name: "",
                object_type: "",
            };
        },
        closeModal() {
            this.$emit("close-record-modal");
            this.clearForm();
        },
    },
    watch: {
        refs_record_item(newVal, oldVal) {
            if (newVal) {
                this.record_type.id = newVal.id;
                this.record_type.name = newVal.name;
                this.record_type.developer_name = newVal.developer_name;
                this.record_type.object_type  = newVal.object_type ;
            }
        },
    },
});

vrecords.mount('#lstRecordType');