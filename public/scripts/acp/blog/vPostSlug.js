/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
const postSlug = {
    props:['slug', 'fullSlug', 'label', 'post', 'token', 'tkname'],
    template: "#vpost-slug",
    data: function () {
        return {
            showSlug: true,
            newSlug: '',
            newUrl: '',
        }
    },
    mounted(){
        this.newSlug = this.slug;
        this.newUrl = this.fullSlug;
    },
    methods: {
        edit() {
            this.showSlug = false;
        },
        saveSlug() {
            if ( this.newSlug == '' ) {
                SwalAlert.fire({
                    icon: 'error',
                    title: 'Vui lòng nhập vào url',
                });
                return false;
            } else {
                var url = base_url+"/acp/post/ajxEditSlug/"+this.post;
                data = new FormData();
                data.append("post_slug", this.newSlug);
                data.append(this.tkname, this.token);
                $.ajax({
                    url: url,
                    data: data,
                    cache: false,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    type: "POST",
                    success: function (response) {
                        if ( response.error == 0 ) {
                            this.showSlug = true;
                            this.newUrl = response.postData.fullSlug;
                            SwalAlert.fire(
                                'Updated!',
                                response.text,
                                'success'
                            );
                        } else {
                            SwalAlert.fire(
                                '',
                                response.text,
                                'error'
                            );
                        }

                    }.bind(this),
                    error: function (jqXHR, textStatus, errorThrow) {
                        console.log(textStatus+' '+errorThrow);
                    }
                })
            }
        },
        cancelSlug() {
            this.newSlug = '';
            this.showSlug = true;
        },
        renderPostUrl() {
            if ( this.newSlug == '' ) return this.fullSlug;
            else return this.newUrl;
        }
    }
};