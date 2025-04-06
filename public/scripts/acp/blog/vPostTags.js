/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
const vAutoList = {
  props: ["itemdata"],
  template: "#vautocomplete-item",
  computed: {
    tagtitle() {
      return this.itemdata.title;
    },
    tagslug() {
      return this.itemdata.slug;
    },
  },
  methods: {
    setTag(tag) {
      this.$emit("set-tag-cloud", tag);
      this.$emit("clear-tag-input");
    },
    removeCurrentTag() {
      $(this).parent().parent().remove();
    },
  },
};

const tagCloudItem = {
  template: "#vtag-cloud-item",
  props: ["tagdata"],
  methods: {
    setTagVal(tag) {
      return tag.slug;
    },
    removeTag(tag) {
      this.$emit("remove-tag-cloud", tag);
    },
  },
};

const posttags = {
  components: {
    "vauto-list": vAutoList,
    "vtagcloud-item": tagCloudItem,
  },
  data() {
    return {
      searchText: "",
      tagslist: [],
      tagtotal: 0,
      tagitem: [],
    };
  },
  props: ["limitlength"],
  template: "#vpostTags-template",
  mounted: function () {
    let modId = $("#inpModId").val();
    let modName = $("#inpModName").val();
    // let postUrl = base_url+'/acp/tags/get-post-tags/'+postID;
    let postUrl = base_url + "/acp/tags/get-tags/" + modId + "/" + modName;
    if (modId > 0) {
      //get tag items
      $.ajax({
        url: postUrl,
        type: "GET",
        dataType: "json",
        success: (response) => {
          if (response.error == 0) {
            for (var i = 0; i < response.tagList.length; i++) {
              this.tagitem.push(response.tagList[i]);
            }
          } else {
            Swal.fire({
              icon: "error",
              title: "Lỗi...",
              text: response.text,
            });
          }
        },
      });
    }
  },
  methods: {
    onKeyUp() {
      var url = base_url + "/acp/tags/get-data";
      if (this.searchText.length >= this.limitlength) {
        url = url + "?tag=" + this.searchText;

        //get tag data
        $.ajax({
          url: url,
          type: "GET",
          success: function (data) {
            var response = jQuery.parseJSON(data);
            this.tagslist = response.tagList;
            //console.log(response.total);
          }.bind(this),
        });
      }
    },
    addTag() {
      var url = base_url + "/acp/tags/add-tag";
      var csName = $("#csname").val();
      var csToken = $("#cstoken").val();
      var modName = $("#inpModName").val();

      if (this.searchText) {
        frmdata = new FormData();
        frmdata.append("title", this.searchText);
        frmdata.append("tag_type", modName);
        frmdata.append(csName, csToken);

        //add tag data
        $.ajax({
          url: url,
          data: frmdata,
          cache: false,
          contentType: false,
          processData: false,
          type: "POST",
          success: function (data) {
            var response = jQuery.parseJSON(data);

            if (response.error == 0) {
              var input =
                "<input type='hidden' class='tmt_inp_tagcloud' name='tagcloud[]' value='" +
                response.tagsdata.slug +
                "' >";
              var btnRemove =
                '<a class="tag-item-remove ml-2 text-light" href="#">x</a>';

              var html =
                '<li class="list-inline-item"><span class="badge badge-primary">' +
                response.tagsdata.title +
                btnRemove +
                "</span>" +
                input +
                "</li>";

              $("#tags-cloud").prepend(html);

              //clear the input
              this.searchText = null;

              SwalAlert.fire({
                icon: "success",
                title: response.text,
              });
            } else {
              // Swal.fire({
              //     icon: 'error',
              //     title: response.text,
              // });
              $(document).Toasts("create", {
                class: "bg-danger",
                title: "Thông Báo",
                autohide: true,
                delay: 1000,
                body: response.text,
              });
            }
          }.bind(this),
          statusCode: {
            404: function () {
              Swal.fire({
                icon: "error",
                title: "Page Not Found",
              });
            },
            403: function () {
              Swal.fire({
                icon: "error",
                title:
                  "Có lỗi xảy ra! Vui lòng refresh lại trình duyệt của bạn rồi tiếp tục.",
              });
            },
          },
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Vui lòng nhập tag",
        });
      }
    },
    clearTagInput() {
      this.searchText = null;
    },
    updateTag(tag) {
      this.tagitem.push(tag);
      this.tagslist = []; //clear the autocomplete list
    },
    deleteTag(tag) {
      for (var i = 0; i < this.tagitem.length; i++) {
        if (this.tagitem[i].id == tag.id) {
          this.tagitem.splice(i, 1);
        }
      }
    },
  },
};
