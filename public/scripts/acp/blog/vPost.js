/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: basic_cms
 */
// start app
const app = Vue.createApp({
  components: {
    "feature-img": featureImg,
    "content-gallery": contentGallery,
  },
  data() {
    return {
      showModal: false,
      showAttachMeta: false,
      catlist: [],
      catselected: [],
      file: "",
      demofile: "",
      post_img: "",
      post_type: "",
      showImg: 0,
      attImgs: "",
    };
  },
  beforeCreate: function () {
    let postType = document
      .getElementById("postApp")
      .getAttribute("data-cattype");
    this.post_type = postType;
    let catUrl = bkUrl + "/category/list-cat/" + postType;

    //get categories item
    $.ajax({
      url: catUrl,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.error == 0) {
          for (var i = 0; i < response.data.length; i++) {
            this.catlist.push(response.data[i]);
          }
        } else console.log(response.text);
      }.bind(this),
    });
  },
  mounted() {
    var modId = $("#inpModId").val();
    if (typeof modId !== "undefined") {
      //set the category selected in post edit page
      let url = bkUrl + "/post/ajx-get-post/" + modId;
      $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
      }).then(function (response) {
        var catSelect = $("#slt2Cat");
        // manually trigger the `select2:select` event
        catSelect.trigger({
          type: "select2:select",
          params: {
            data: response.post.catData,
          },
        });
      });
    }

    if (typeof post_img !== "undefined" && post_img != "") {
      this.post_img = jQuery.parseJSON(post_img);
      this.demofile = this.post_img;
    }
  },
  methods: {
    showGallery() {
      this.showModal = true;
      $("#vContentGalleryModal").modal("show");
    },
    hideGallery() {
      this.showModal = false;
      $("#vContentGalleryModal").modal("hide");
    },
    deleteUploadFile(images) {
      this.selectedImg = images;
    },
    setAttachFiles(images) {
      this.selectedImg = images;
      this.showImg = 1;
      this.attImgs = "";
      for (var i = 0; i < images.length; i++) {
        if (this.returnImg == "id") this.attImgs += images[i].id + ";";
        else if (this.returnImg == "url")
          this.attImgs += images[i].full_image + ";";
      }
    },
    removeFile(img) {
      console.log(img);
      for (var i = 0; i < this.selectedImg.length; i++) {
        if (this.selectedImg[i].id == img.id) {
          this.selectedImg.splice(i, 1);
        }
      }
      if (this.selectedImg.length === 0) this.showImg = 0;
      this.setAttachFiles(this.selectedImg);
    },
  },
});
/**
 * import post slug component only on edit vue edit
 */
if (typeof postSlug !== "undefined") app.component("post-slug", postSlug);
/**
 * import post tags component only when it needed
 */
if (typeof posttags !== "undefined") app.component("vposttags", posttags);

app.mount("#postApp");
