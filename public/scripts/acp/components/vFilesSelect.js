//add component for image select
var vFileSelector = {
    template: "#tpl-vImgSelect",
    props:['htmlnote', 'file_name', 'clear_inpt', 'fileLabel'],
    data: function () {
        return {
            files: [],
            demofile: 0,
            uploaderror: 0,
        }
    },
    methods: {
        handleFileUpload() {
            this.files = [];
            let uploadedFiles = this.$refs.image.files;
            for( var i = 0; i < uploadedFiles.length; i++ ){
                this.files.push( uploadedFiles[i] );
            }
            this.demofile = 1;
        },
        unsetFile( key ){
            this.files.splice( key, 1 );
            if ( this.files.length == 0 ) {
                //remove file input
                const input = this.$refs.image;
                input.type = 'text';
                input.type = 'file';

                this.demofile = 0;
            }
        },
        submitFile() {
            if (this.files.length == 0) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Thông Báo',
                    autohide: true,
                    delay: 1000,
                    body: "Vui lòng chọn ảnh để upload"
                });
                return;
            }
            let csName = $('#csname').val();
            let csToken = $('#cstoken').val();
            let url = bkUrl+"/attach/upload";
            let formData = new FormData();

            //handle multi files upload
            for( var i = 0; i < this.files.length; i++ ){
                let img_title = "";
                let file = this.files[i];

                if ( i > 0 ) img_title = file.name + ' ' + i;
                else img_title = file.name;

                formData.append('images', file);
                formData.append('title', img_title);
                formData.append(csName, csToken);

                $.ajax({
                    xhr: function(){
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if ( evt.lengthComputable ) {
                                var PercentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(PercentComplete + '%');
                                $(".progress-bar").html(PercentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    url: url,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    type: "POST",
                    context: this,
                    beforeSend: function(){
                        $(".progress").show();
                        $(".progress-bar").width('0%');
                    },
                    success: response =>  {
                        if ( response.code == 1 ) {
                            this.uploaderror = 1;
                            Swal.fire({
                                icon: 'error',
                                title: response.text,
                            })
                        } else {
                            $(".progress").hide();
                            //add img data to vue item
                            this.$emit('image-uploaded', response.imgData);
                            //remove the file in upload
                            this.unsetFile(file)
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrow) { console.log(errorThrow);
                        this.uploaderror = 1;
                        Swal.fire({
                            icon: 'error',
                            title: 'Có lỗi xảy ra khi upload! Vui lòng refresh lại trình duyệt rồi thử lại',
                        })
                    },
                });
            }

        },
    },
    watch: {
    }
};

const filePreview = {
    props: ['images'],
    template: "#vFilePreReview-template",
    methods: {
        imageUrl(img) {
            let url = URL.createObjectURL(img);
            return url;
        },
        removeAttach(key) {
            $(".progress").hide();
            this.$emit('remove-file-upload', key);
        }
    }
};