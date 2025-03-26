/**
 * @author tmtuan
 * created Date: 19-Jul-21
 */
const SwalAlert = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000
});

$(function () {

    $(".acpRmItem").on("click", function () {
        var url = $(this).attr("data-delete");
        var item = $(this).attr("data-id");
        var token = $('#token').val();
        var token_key = $('#token-key').val();
        const delete_message = $(this)
            .attr("data-delete-message")
            .replace(
                "{1}",
                $(this).attr("data-delete-content")
                    ? `"${$(this).attr("data-delete-content")}"`
                    : ""
            );
        const delete_button_title = $(this).attr("data-delete-button");
        const cancel_button_title = $(this).attr("data-cancel-button");

        Swal.fire({
            title: delete_message ?? "Bạn có chắc chắn muốn xóa item này??",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: delete_button_title ?? "Xoá",
            cancelButtonText: cancel_button_title ?? "Huỷ",
            cancelButtonColor: "#20c997",
            confirmButtonColor: "#DD6B55",
        }).then((result) => {
            /* Delete item */
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append("id", item);
                formData.append(token, token_key);
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
                            $(this).parent().parent().remove();
                            SwalAlert.fire({
                                icon: "success",
                                title: response.message,
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        SwalAlert.fire({
                            icon: "error",
                            title:
                                "Có lỗi xảy ra! Vui lòng refresh trình duyệt và thử lại sau",
                        });
                    },
                });
            } else if (result.isDenied) {
                return false;
            }
        });
        return false;
    });

    //keyboard navigation
    $(document.documentElement).keyup(function (event) {
        // handle cursor keys
        if (event.keyCode == 113) {
            document.getElementById('postSave').click();
        } else if (event.keyCode == 115) {
            document.getElementById('postSaveExit').click();
        }
    });

    $('#selectAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
});
