/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
const addMenu = {
    template: "#vaddMenu-template",
    props:['token', 'tkname'],
    data() {
        return {
            name: '',
        }
    },
    methods: {
        onSubmit() {
            if ( !this.name || this.name == '' ) {
                $('#inpName').addClass('is-invalid');
                $('#inpName').focus();
                return false;
            }

            let url = $("#addMenuFrm").attr('action');
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append(this.tkname, this.token);

            $.ajax({
                url: url,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    if (response.error == 1) {
                        SwalAlert.fire({
                            icon: "error",
                            title: response.message,
                        });
                    } else {
                        this.name = '';
                        $('#addMenuModal').modal('hide');
                        this.$emit('add-done', response.newItem);
                        SwalAlert.fire({
                            icon: "success",
                            title: response.message,
                        });
                    }
                }.bind(this)
            });
        },
    }
};

const menuApp = Vue.createApp({
    components: {
        'vaddMenu' : addMenu
    },
    data() {
        return {
            showModal: false,
            loading: true,
            menuList: [],
            searchKey: '',
        }
    },
    mounted() {
        this.loading = true;
        let listEduUrl = bkUrl + "/menu/list-menu";

        $.ajax({
            url: listEduUrl,
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.error === 0) {
                    this.menuList = response.data;
                } else {
                    SwalAlert.fire({
                        icon: "error",
                        text: response.message,
                    });
                }
                if ( this.menuList.length == 0 ) this.showAddMenu();
                this.loading = false;
            }.bind(this),
            error: (_xhr, _error) => {
                this.loading = false;
            },
        });
    },
    methods: {
        showAddMenu(){
            this.showModal = true;
            $("#addMenuModal").modal('show');
        },
        hideAddMenu() {
            this.showModal = false;
            $("#addMenuModal").modal('hide');
        },
        addSuccess(menu) {
            this.menuList.push(menu); console.log(menu);
            this.showModal = false;
            $("#addMenuModal").modal('hide');
        },
        rdStatus(item) {
            let bgClass = '';
            if ( item.status == 'draft' ) bgClass = 'bg-info';
            else bgClass = 'bg-success';
            let html = '<span class="p-2 '+bgClass+'">'+item.status+'</span>';
            return html;
        },
        rdTitle(item) {
            let editUrl = bkUrl+'/menu/edit/'+item.id;
            return '<a href="'+editUrl+'" >'+item.name+'</a>';
        },
        rdEditUrl(item) {
            return bkUrl+'/menu/edit/'+item.id;
        },
        searchItem() {
            let subUrl = '';
            if ( this.searchKey != '' ) {
                subUrl = '?name='+this.searchKey;
            }
            this.loading = true;
            let listEduUrl = bkUrl + "/menu/list-menu"+subUrl;

            $.ajax({
                url: listEduUrl,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.error === 0) {
                        this.menuList = response.data;
                    } else {
                        SwalAlert.fire({
                            icon: "error",
                            text: response.message,
                        });
                    }
                    if ( this.menuList.length == 0 ) this.showAddMenu();
                    this.loading = false;
                }.bind(this),
                error: (_xhr, _error) => {
                    this.loading = false;
                },
            });
        },
        deleteMenu(item) {
            let message =
                "Bạn chắc chắn muốn xóa menu này chứ? <br>Menu: " +
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
                    let url = bkUrl + "/menu/del-menu";
                    let token_key = $('#tk_key').val();
                    let token = $('#token').val();
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
                                for (var i = 0; i < this.menuList.length; i++) {
                                    if (this.menuList[i].id == item.id) {
                                        this.menuList.splice(i, 1);
                                    }
                                }
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
        }
    }
});

