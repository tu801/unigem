const vConfigAttach = {
  props: [
    "imgData",
    "imgDesc",
    "selectImgType",
    "renderType",
    "configData",
    "oldData",
    "inputName",
    "returnImg",
    "imgAttachMetaId",
  ],
  template: "#vConfig-img-component",
  data() {
    return {
      showModal: false,
      selectedImg: [],
      showImg: 0,
      attImgs: "",
    };
  },
  mounted() {
    if (this.renderType == "config") {
      if (this.oldData != "") {
        this.selectedImg = JSON.parse(this.oldData);
      }
    } else {
      if (this.imgData) {
        let dataType = "single";
        // if select multiple image
        if (this.selectImgType == 2) dataType = "multi";

        let url =
          bkUrl +
          "/config/get-custom-attach?atts=" +
          this.imgData +
          "&dataType=" +
          dataType;
        $.ajax({
          url: url,
          type: "GET",
          dataType: "json",
          success: function (response) {
            if (response.error == 0) {
              this.selectedImg = response.data;
              this.showImg = 1;
              this.attImgs = "";
              for (var i = 0; i < response.data.length; i++) {
                if (i == response.data.length - 1) {
                  this.attImgs += response.data[i].id;
                } else {
                  this.attImgs += response.data[i].id + ";";
                }
              }
            } else console.log(response.message);
          }.bind(this),
        });
      }
    }
  },
  methods: {
    showGallery() {
      this.showModal = true;
      $("#vGalleryModal").modal("show");
    },
    hideGallery() {
      this.showModal = false;
      $("#vGalleryModal").modal("hide");
    },
    deleteUploadFile(images) {
      this.selectedImg = images;
    },
    setAttachFiles(images) {
      this.selectedImg = images;
      this.showImg = 1;
      this.attImgs = "";
      for (var i = 0; i < images.length; i++) {
        if (i == images.length - 1) {
          if (this.returnImg == "id") this.attImgs += images[i].id;
          else if (this.returnImg == "url")
            this.attImgs += images[i].full_image;
        } else {
          if (this.returnImg == "id") this.attImgs += images[i].id + ";";
          else if (this.returnImg == "url")
            this.attImgs += images[i].full_image + ";";
        }
      }
      this.hideGallery();
    },
    removeFile(img) {
      for (var i = 0; i < this.selectedImg.length; i++) {
        if (this.selectedImg[i].id == img.id) {
          this.selectedImg.splice(i, 1);
        }
      }
      if (this.selectedImg.length === 0) {
        this.showImg = 0;
      }

      this.setAttachFiles(this.selectedImg);
    },
    renderImgUrl(image) {
      var returnImg = "";
      if (typeof image.thumb_image != "undefined") {
        returnImg = base_url + "/" + image.thumb_image;
      } else {
        returnImg = base_url + "/" + image.image;
      }
      return returnImg;
    },
    imgUrl(image) {
      var returnImg = "";
      if (typeof image.full_image != "undefined") {
        returnImg = image.full_image;
      } else {
        returnImg = image.image;
      }
      return returnImg;
    },
  },
};
