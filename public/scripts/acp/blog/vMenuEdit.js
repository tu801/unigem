/**
 * @author tmtuan
 * created Date: 10/15/2021
 * project: basic_cms
 */

function showeditmenuform(id){
    $("#editForm_"+id).toggle();
}

function editmenuitem(id, catId) {
    var title = $("#contentTitle_"+id).val();
    var url = '';

    if ( title == "" ) {
        SwalAlert.fire({
            icon: 'error',
            title: 'Vui lòng nhập tiêu đề cho menu!',
        });
        $("#contentTitle_"+id).focus();
        return false;
    }

    if ( catId == 0 ) {
        var url = $("#contentUrl_"+id).val();
        if ( url == '' ) {
            SwalAlert.fire({
                icon: 'error',
                title: 'Vui lòng nhập đường dẫn tĩnh cho menu!',
            });
            $("#contentUrl_"+id).focus();
            return false;
        }
    }
    let token = $('#tmt-tk').val();
    let tokenName = $('#tmt-tkname').val();

    formData = new FormData();
    formData.append("cat_id", catId);
    formData.append("title", title);
    formData.append("url", url);
    formData.append(tokenName, token);

    $.ajax({
        url: base_url+'/acp/menu/edit-menu/'+id,
        data: formData,
        cache: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        type: "POST",
        success: function (response) {
            if(response.error == 0) {
                SwalAlert.fire({
                    icon: 'success',
                    title: response.text,
                });
                event.stopPropagation();
                $("#menuTitle_"+id).html(title);
                $("#editForm_"+id).collapse("hide");
            } else {
                SwalAlert.fire({
                    icon: 'error',
                    title: response.text,
                })
            }

        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.log(textStatus+' '+errorThrow);
            SwalAlert.fire({
                icon: 'error',
                title: textStatus+' '+errorThrow,
            })
        }
    });
    return false;
}

const customUrlApp = Vue.createApp({
    data() {
        return {
            title: '',
            parent_id: '',
            url: '',
            target: '_self',
        }
    },
    methods: {
        submitForm(){
            let url = $('#addUrlApp').attr('data-action');
            let redirectUrl = $('#addUrlApp').attr('data-redirect');
            let token = $('#tmt-tk').val();
            let tokenName = $('#tmt-tkname').val();
            let key = $('#menukey').val();

            formData = new FormData();
            formData.append("title", this.title);
            formData.append("parent_id", this.parent_id);
            formData.append("url", this.url);
            formData.append("target", this.target);
            formData.append("key", key);
            formData.append(tokenName, token);

            $.ajax({
                url: url,
                data: formData,
                cache: false,
                dataType: 'json',
                contentType: false,
                processData: false,
                type: "POST",
                success: function (response) {
                    if(response.error == 0) {
                        SwalAlert.fire({
                            icon: 'success',
                            title: response.message,
                        });

                        window.setTimeout(function(){
                            // Move to a new location or you can do something else
                            window.location.href = redirectUrl;
                        }, 600);
                    } else {
                        SwalAlert.fire({
                            icon: 'error',
                            title: response.message,
                        })
                    }

                },
                error: function (jqXHR, textStatus, errorThrow) {
                    console.log(textStatus+' '+errorThrow);
                }
            });
            return false;
        },
        resetForm() {
            this.title = '';
            this.parent_id = '';
            this.url = '';
            this.target = '_self';
        }
    }
});

const listPagesApp = Vue.createApp({
    data() {
        return {
            title: '',
            pageList: [],
            loading: true,
        }
    },
    mounted(){
        this.fetchPages();
    },
    methods: {
        fetchPages(){
            this.loading = true;
            let searchSlug = '';
            if ( this.title != '' ) searchSlug = '&title=' + this.title;

            let menuItem = $('#listPageApp').attr('data-sltMenu');
            let listUrl = bkUrl + "/menu/list-page-menu?menu="+menuItem+searchSlug;

            $.ajax({
                url: listUrl,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.error === 0) { //console.log(response.data);
                        this.pageList = response.data;
                    }
                    this.loading = false;
                }.bind(this),
                error: (_xhr, _error) => {
                    this.loading = false;
                },
            });
        },
        onKeyUp() {
            if ( this.title != '' && this.title.length > 1 ) this.fetchPages();
            else if ( this.title == '' ) this.fetchPages();
        },
        rdAddBtn(item) {
            let html = '';
            if ( item.add_to_menu == 1 ) {
                html += '<a class="btn btn-primary btn-sm" href="';
                html += item.add_menu_url + '"><i class="fa fa-plus-square"></i></a>';
            }
            return html;
        }
    }
});

$('.acpRemoveMenu').on('click', function () {
    var url =  $(this).attr('href');

    Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa menu item này??',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: `Xóa`,
        cancelButtonColor: "#20c997",
        confirmButtonColor: "#DD6B55",
    }).then((result) => {
        /* Delete item */
        if (result.isConfirmed) {
            window.location.replace(url)
        } else if (result.isDenied) {
            return false;
        }
    });
    return false;
});