/**
 * @author tmtuan
 * created Date: 8/22/2023
 * project: unigem
 */

const app = Vue.createApp({
  components: {
    "feature-img": featureImg,
  },
  data() {
    return {
      file: "",
      demofile: "",
      showImg: 0,
    };
  },
  mounted() {
    if (typeof post_img !== "undefined" && post_img != "") {
      this.post_img = jQuery.parseJSON(post_img);
      this.demofile = this.post_img;
    }
  },
  method: {
    deleteUploadFile(images) {
      this.selectedImg = images;
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
app.mount("#postApp");

$(document).ready(function () {
  $("#cus_datepicker").datepicker({
    todayHighlight: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    endDate: new Date(),
  });
});
