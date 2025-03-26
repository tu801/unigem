const vSliderSelector = {
    template: "#tpl-vSliderSelector",
    data: function () {
        return {
            form: [],
        }
    },
    methods: {
        sliderUploadSuccess(imgData) {
            // post slider
            const url = bkUrl + "/theme-option/save-slider";
            let csName = $("#csname").val();
            let csToken = $("#cstoken").val();

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    image: imgData,
                    [csName]: csToken,
                },
                dataType: "json",
                success: (response) => {
                    if (response.error === 0) {
                        this.form.unshift(response.data)
                        SwalAlert.fire({
                            icon: "success",
                            title: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi...",
                            text: response.message,
                        });
                    }
                }
            })
        },
        onSubmit (index){
            const url = bkUrl + "/theme-option/save-slider";
            let csName = $("#csname").val();
            let csToken = $("#cstoken").val();
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    ...this.form[index],
                    [csName]: csToken,
                },
                dataType: "json",
                success: function(response) {
                    if (response.error === 0) {
                        $("#card"+index).CardWidget('collapse')
                        SwalAlert.fire({
                            icon: "success",
                            title: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi...",
                            text: response.message,
                        });
                    }
                }
            })

        },
        vImgSource(image){
            return base_url+'/'+image;
        },
        getSliderList() {
            const url = bkUrl + "/theme-option/get-slider";
            $.ajax({
                url: url,
                method: "GET",
                dataType: "json",
                success: (response) => {
                    if (response.error === 0) {
                        this.form = response.data;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi...",
                            text: response.message,
                        });
                    }
                }
            })
        },
        sliderDelete(index) {
            const url = bkUrl + "/theme-option/delete-slider";
            let csName = $("#csname").val();
            let csToken = $("#cstoken").val();
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    ...this.form[index],
                    [csName]: csToken,
                },
                dataType: "json",
                success: (response) => {
                    if (response.error === 0) {
                        this.form.splice(index, 1);
                        SwalAlert.fire({
                            icon: "success",
                            title: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi...",
                            text: response.message,
                        });
                    }
                }
            })
        }
    },
    mounted() {
        this.getSliderList();
    }
};