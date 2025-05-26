/**
 * Quick View Product Functionality
 */
(function ($) {
  "use strict";

  /* Quick View Product
    -------------------------------------------------------------------------*/
  var quickViewProduct = function () {
    if ($(".quickview").length) {
      $(".quickview").on("click", function (e) {
        console.log("clicked test");
        var productId = $(this).data("product-id");
        var quickViewModal = $("#quick_view");
        var loading = quickViewModal.find(".tmt-spinner");
        var wraperContent = quickViewModal.find(".tmt-wrap");

        // Show loading state
        $(loading).removeClass("d-none");
        wraperContent.addClass("d-none");

        // Fetch product data from API
        $.ajax({
          url: "/ajax/product/get-product/" + productId,
          type: "GET",
          dataType: "json",
          success: function (response) {
            if (response && response.status === 200) {
              var product = response.data;
              console.log(product);
              // Update modal content with product data
              // Product title
              quickViewModal
                .find(".tf-product-info-title h5 a")
                .text(product.pd_name);
              quickViewModal
                .find(".tf-product-info-title h5 a")
                .attr("href", product.url || "product-detail.html");

              // Product price
              var price =
                // Update modal content with product data
                // Product title
                quickViewModal
                  .find(".tf-product-info-title h5 a")
                  .text(product.pd_name);
              quickViewModal
                .find(".tf-product-info-title h5 a")
                .attr("href", product.url || "product-detail.html");

              // Product price
              var price =
                product.price_discount > 0 &&
                product.price_discount < product.price
                  ? product.price_discount
                  : product.price;

              quickViewModal
                .find(".tf-product-info-price .price")
                .text(formatCurrency(price, product.lang.locale));

              // Product description
              quickViewModal
                .find(".tf-product-description p")
                .text(product.description || "");

              // product size
              if (product.pd_size !== null) {
                quickViewModal.find(".tf-product-size p").removeClass("d-none");
                quickViewModal.find("#tmt_pd_size").text(product.pd_size);
              } else
                quickViewModal.find(".tf-product-size p").addClass("d-none");

              // product weight
              if (product.pd_weight !== null) {
                quickViewModal
                  .find(".tf-product-weight p")
                  .removeClass("d-none");
                quickViewModal.find("#tmt_pd_weight").text(product.pd_weight);
              } else
                quickViewModal.find(".tf-product-weight p").addClass("d-none");

              // product cut_angle
              if (product.pd_cut_angle !== null) {
                quickViewModal
                  .find(".tf-product-cut_angle p")
                  .removeClass("d-none");
                quickViewModal
                  .find("#tmt_pd_cut_angle")
                  .text(product.pd_cut_angle);
              } else
                quickViewModal
                  .find(".tf-product-cut_angle p")
                  .addClass("d-none");

              // Product images
              var swiperWrapper = quickViewModal.find(".swiper-wrapper");
              swiperWrapper.empty();

              if (product.imageData && product.imageData.length > 0) {
                product.imageData.forEach(function (image) {
                  var slide = $(
                    '<div class="swiper-slide"><div class="item"><img src="' +
                      image +
                      '" alt="' +
                      product.pd_name +
                      '"></div></div>'
                  );
                  swiperWrapper.append(slide);
                });
              } else if (product.feature_image) {
                var slide = $(
                  '<div class="swiper-slide"><div class="item"><img src="' +
                    product.feature_image.original +
                    '" alt="' +
                    product.pd_name +
                    '"></div></div>'
                );
                swiperWrapper.append(slide);
              }

              // Reinitialize swiper if needed
              if (
                typeof Swiper !== "undefined" &&
                quickViewModal.find(".tf-single-slide").length
              ) {
                new Swiper(".tf-single-slide", {
                  slidesPerView: 1,
                  spaceBetween: 0,
                  navigation: {
                    nextEl: ".single-slide-prev",
                    prevEl: ".single-slide-next",
                  },
                });
              }

              // Update product price
              quickViewModal
                .find(".tf-qty-price.total-price")
                .text(product.display_price);

              // Update "View full details" link
              quickViewModal
                .find(".tf-product-info-list > div:last-child a")
                .attr("href", product.url || "product-detail.html");

              // Hide loading state
              $(loading).addClass("d-none");
              wraperContent.removeClass("d-none");

              // Show the modal
              quickViewModal.modal("show");
            } else {
              console.error("Failed to load product data");
            }
          },
          error: function (xhr, status, error) {
            console.error("Error fetching product data:", error);
          },
          complete: function () {
            // Remove loading state
            $(".quickview.loading").removeClass("loading");
          },
        });
      });
    }
  };

  // Helper function to format currency
  function formatCurrency(amount, locale) {
    // Default to 'en' if no locale is provided
    locale = locale || "en";

    if (locale === "vi") {
      // Format for Vietnamese currency (VND)
      return (
        parseFloat(amount)
          .toFixed(0)
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ"
      );
    } else {
      // Format for US currency (USD)
      return (
        "$" +
        parseFloat(amount)
          .toFixed(2)
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
      );
    }
  }

  // Make the function available globally
  window.initQuickViewProduct = quickViewProduct;
})(jQuery);
