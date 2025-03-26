/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
const catList = Vue.createApp({
    data() {
        return {
            cat_type: '',
            action: '',
            categories: [],
            loading: true,
            searchkey: '',
        }
    },
    created() {
        var catType = $('#listCat').attr('data-cat-type');
        var dataAction = $('#listCat').attr('data-action');
        let actionParam = '';

        if ( typeof dataAction != 'undefined' && dataAction == 'deleted'  ) actionParam = '?deleted=1';

        var url = bkUrl+'/category/list-cat/'+catType+actionParam;
        console.log('Y:', url);
        
        this.cat_type = catType;

        $.ajax({
            type: "GET",
            url: url ,
            dataType: "json",
            success: function (response) {
                if( response.error == 0 ) {
                    this.categories = response.data;
                } else {
                    if ( typeof dataAction != 'undefined' && dataAction != 'deleted'  )
                    {
                        Swal.fire({
                            icon: 'error',
                            text: response.message,
                        });
                    }
                }
                this.loading = false;
            }.bind(this)
        });
    },
    methods: {
        renderEditUrl(cat) {
            return bkUrl+'/category/edit/'+cat.id;
        },
        catParent(cat) {
            if ( cat.parent_id > 0 ) return cat.parent.title;
            else return '';
        },
        delCat(id) {
            var url = bkUrl+'/category/remove/'+id;

            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa danh mục này??',
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
        },
        onSearch(event) {
            if ( this.searchkey.length > 1 ) {
                var surl = bkUrl+'/category/list-cat/'+this.cat_type+'?s='+this.searchkey;
                $.ajax({
                    type: "GET",
                    url: surl ,
                    dataType: "json",
                    success: function (response) {
                        if( response.error == 0 ) {
                            this.categories = response.data;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                            })
                        }
                    }.bind(this)
                });
            }
        }
    }
});