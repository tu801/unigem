/**
 * @author tmtuan
 * created Date: 10/20/2021
 * project: basic_cms
 * Vuejs - select file and add file to component
 */

const gallery = {
  template: "#vgallery-template",
  props: ["attType", "selecteditem"],
  data() {
    return {
      allUploadData: [],
      vimgInfo: {},
      cardimgstt: 1,
      files: [],
      uploaderror: 0,
      demofile: 0,
      page: 0,
      loading: false,
      fetching: false,
      elm: "tmt-list-attach",
    };
  },
  methods: {
    fetchImages() {
      var upUrl = bkUrl + "/attach/get_upload_data?page=" + this.page;
      this.loading = true;
      var myVue = this;
      //get all image gallery data
      $.ajax({
        url: upUrl,
        type: "GET",
        success: function (response) {
          if (response.uploadData.length) {
            myVue.page = response.page;

            if (this.allUploadData.length > 0) {
              setTimeout(function () {
                response.uploadData.forEach((resp) => {
                  myVue.allUploadData.push(resp);
                });
              }, 500);
            } else {
              this.allUploadData = response.uploadData;
            }
          } else this.page = 0;

          myVue.loading = false;
          myVue.fetching = false;
        }.bind(this),
      });
    },
    showInfo(img) {
      this.cardimgstt = 1;
      this.vimgInfo = img;
    },
    removeCard() {
      this.cardimgstt = 0;
    },
    handleFileUpload() {
      this.files = [];
      this.uploaderror = 0;
      let uploadedFiles = this.$refs.images.files;
      /*
              Adds the uploaded file to the files array
            */
      for (var i = 0; i < uploadedFiles.length; i++) {
        this.files.push(uploadedFiles[i]);
      }
      this.demofile = 1;
    },
    submitFile() {
      if (this.files.length == 0) {
        $(document).Toasts("create", {
          class: "bg-danger",
          title: "Thông Báo",
          autohide: true,
          delay: 1000,
          body: "Vui lòng chọn ảnh để upload",
        });
        return;
      }
      let csName = $("#token").val();
      let csToken = $("#token-key").val();
      let url = bkUrl + "/attach/upload";
      let formData = new FormData();
      let file_title = $("#file_title").val();

      //handle multi files upload
      for (var i = 0; i < this.files.length; i++) {
        let img_title = "";
        let file = this.files[i];
        var imgData = "";

        if (i > 0) img_title = file_title + " " + i;
        else img_title = file_title;

        formData.append("images", file);
        formData.append("title", img_title);
        formData.append(csName, csToken);

        $.ajax({
          xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener(
              "progress",
              function (evt) {
                if (evt.lengthComputable) {
                  var PercentComplete = (evt.loaded / evt.total) * 100;
                  $(".progress-bar").width(PercentComplete + "%");
                  $(".progress-bar").html(PercentComplete + "%");
                }
              },
              false
            );
            return xhr;
          },
          url: url,
          data: formData,
          dataType: "json",
          contentType: false,
          processData: false,
          type: "POST",
          beforeSend: function () {
            $(".progress").show();
            $(".progress-bar").width("0%");
          },
          success: (response) => {
            if (response.code == 1) {
              this.uploaderror = 1;
              SwalAlert.fire({
                icon: "error",
                title: response.text,
              });
            } else {
              $(".progress").hide();
              //add img data to vue item
              this.allUploadData.unshift(response.imgData);
              //remove the file in upload
              this.files.splice(file, 1);

              SwalAlert.fire({
                icon: "success",
                title: response.text,
              });
            }
          },
          error: function (jqXHR, textStatus, errorThrow) {
            console.log(errorThrow);
            this.uploaderror = 1;
            SwalAlert.fire({
              icon: "error",
              title: "Có lỗi xảy ra khi upload! Vui lòng thử lại sau",
            });
          },
        });
      }
    },
    unsetFile(key) {
      this.files.splice(key, 1);
      if (this.files.length == 0) {
        //remove file input
        const input = this.$refs.images;
        input.type = "text";
        input.type = "file";
      }
    },
    removeImg(index) {
      for (var i = 0; i < this.allUploadData.length; i++) {
        if (this.allUploadData[i].id == index) this.allUploadData.splice(i, 1);
      }

      let images = this.selecteditem;
      for (var i = 0; i < images.length; i++) {
        if (images[i].id == index) images.splice(i, 1);
      }
      this.$emit("remove-file", images);
    },
    setSelectedFile() {
      let images = this.selecteditem;

      if (this.attType == 1) {
        images = [];
        images.push(this.vimgInfo);
      } else if (this.attType == 2) {
        let check = false;
        for (var i = 0; i < images.length; i++) {
          if (this.vimgInfo.id == images[i].id) check = true;
        }
        if (!check) images.push(this.vimgInfo);
        else {
          SwalAlert.fire({
            icon: "error",
            title: "Ảnh này đã được chọn! Vui lòng chọn ảnh khác",
          });
          return;
        }
      }

      this.$emit("show-file", images);
    },
    handleScroll(e) {
      let elm = e.target;
      // Kiểm tra đã gần cuối chưa
      if (elm.scrollTop + elm.clientHeight >= elm.scrollHeight - 50) {
        if (!this.fetching && !this.loading) {
          this.fetching = true;
          this.page++;
          this.fetchImages();
        }
      }
    },
  },
  mounted() {
    // Gắn sự kiện scroll chỉ 1 lần
    let elm = document.getElementById(this.elm);
    if (elm) {
      elm.addEventListener("scroll", this.handleScroll);
    }

    // Initially load image items for gallery.
    this.fetchImages();

    $("#vGalleryModal").modal("show");
  },
};

const galleryImg = {
  props: ["attItem", "imgindex"],
  template: "#vgalleryImg-template",
  methods: {
    showImageInfo(img) {
      let imgclass = "img_" + this.attItem.id;
      $("#" + imgclass)
        .parent()
        .find("img")
        .removeClass("imgSelected");
      $("#" + imgclass)
        .find("img")
        .addClass("imgSelected");
      this.$emit("show-image-info", img);
    },
    deleteImg(url) {
      let html =
        "<p>" +
        this.attItem.file_name +
        "</p>" +
        '<img style="max-width: 100%;" src="' +
        base_url +
        "/" +
        this.attItem.thumb_image +
        '">';

      Swal.fire({
        title: "Bạn chắc chắn muốn xóa ảnh này chứ?",
        html: html,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: `Xóa Ảnh`,
        cancelButtonColor: "#20c997",
        confirmButtonColor: "#DD6B55",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax(
            url, // request url
            {
              success: (data) => {
                if (data.error == 0) {
                  //remove image
                  this.$emit("dell-img", this.attItem.id);
                  $(document).Toasts("create", {
                    class: "bg-success",
                    title: "Thông Báo",
                    autohide: true,
                    delay: 2000,
                    body: data.text,
                  });
                  return false;
                } else {
                  $(document).Toasts("create", {
                    class: "bg-danger",
                    title: "Error",
                    autohide: true,
                    delay: 2000,
                    body: data.text,
                  });
                  return false;
                }
              },
              error: function (xhr, textStatus, errorThrow) {
                if (xhr.status == 404) alert(xhr.statusText);
              },
            }
          );
        } else if (result.isDenied) {
          return false;
        }
      });
    },
  },
  computed: {
    vImgSource() {
      return base_url + "/" + this.attItem.thumb_image;
    },
    vImgDeleteUrl() {
      return bkUrl + "/attach/ajxdel/" + this.attItem.id;
    },
    imgItemClass() {
      return "img_" + this.attItem.id;
    },
  },
};

const imgInfor = {
  props: ["imgData", "cardstt"],
  template: "#vImgInfor-template",
  data() {
    return {};
  },
  methods: {
    hideCard() {
      this.$emit("hide-card-info");
    },
    selectImg() {
      this.$emit("set-selected-img");
    },
  },
  computed: {
    imgurl: function () {
      return base_url + "/" + this.imgData.full_image;
    },
    imgalt: function () {
      return this.imgData.file_title;
    },
  },
};

//component for gallery image review
const imgGalleryReview = {
  props: ["images", "attType"],
  template: "#vImageReview-template",
  methods: {
    imageUrl(img) {
      if (this.attType == 1) {
        return base_url + "/" + this.images[0].thumb_image;
      } else {
        // console.log(img);
        return base_url + "/" + img.thumb_image;
      }
    },
    removeAttach(img) {
      this.$emit("remove-file", img);
    },
  },
};

//component for file upload review
const fileReview = {
  props: ["images"],
  template: "#vFileReview-template",
  methods: {
    imageUrl(img) {
      let url = URL.createObjectURL(img);
      return url;
    },
    removeAttach(key) {
      this.$emit("remove-file-upload", key);
    },
  },
};
