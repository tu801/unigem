/**
 * @author brianha289
 */

/**
 * Do next action when page ready
 */
$(document).ready(function () {
  $(document).on("shown.bs.modal", "#formConfigureAdd", function () {
    $("#title").trigger("focus");
  });

  $(".btnConfigEdit").click(function () {
    const configId = $(this).data("id");
    const title = $(this).data("title");
    const key = $(this).data("keyword");
    const value = $(this).data("configvalue");
    const groupId = $(this).data("groupid");

    $("#configId").val(configId);
    $("#group_id").val(groupId);
    $("#group_id").attr("readonly", true);

    $("#configId").attr("readonly", true);
    $("#configTitle").val(title);
    $("#configKeyword").val(key);
    $("#configKeyword").attr("readonly", true);
    $("#configValue").val(value);
    $("#formConfigEdit").modal("show");
  });
});
