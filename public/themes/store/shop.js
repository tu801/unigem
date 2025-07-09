const ecomApp = Vue.createApp({
  data() {
    return {
      order: {
        customer_id: 0,
        lang_id: 0,
        delivery_type: 1,
        full_name: "",
        phone: "",
        email: "",
        note: "",
        payment_status: 0,
        customer_paid: 0,
        voucher_code: "",
        currency: "VND", // Default currency
        exchange_rate: 1, // Default exchange rate
      },
      carts: [],
      bill: {
        sub_total: 0,
        total: 0,
        shipping_fee: 0,
        discount: 0,
        weight_product_total: 0,
        ship_fee_province: 0,
        ship_fee_on_weight: 0,
      },
      voucher: null,
    };
  },
  methods: {
    // local storage
    addProductToCartLocalStorage(product_id) {
      let carts = this.getCartLocalStorage();
      const indexCartItem = carts.findIndex(
        (element) => element.product_id == product_id
      );
      if (indexCartItem == -1) {
        carts.push({ product_id: product_id, quantity: 1 });
      } else {
        carts[indexCartItem].quantity += 1;
      }
      this.setCartLocalStorage(carts);
    },
    plusProductCartLocalStorage(product_id) {
      let carts = this.getCartLocalStorage();
      const indexCartItem = carts.findIndex(
        (element) => element.product_id == product_id
      );
      if (indexCartItem != -1) {
        carts[indexCartItem].quantity += 1;
        this.setCartLocalStorage(carts);
      }
    },
    minusProductCartLocalStorage(product_id) {
      let carts = this.getCartLocalStorage();
      const indexCartItem = carts.findIndex(
        (element) => element.product_id == product_id
      );
      if (indexCartItem != -1 && carts[indexCartItem].quantity > 1) {
        carts[indexCartItem].quantity -= 1;
        this.setCartLocalStorage(carts);
      }
    },
    removeProductFromCartLocalStorage(product_id) {
      let carts = this.getCartLocalStorage();
      carts = carts.filter((carts) => carts.product_id != product_id);
      this.setCartLocalStorage(carts);
    },
    getCartLocalStorage() {
      try {
        // retrieve order data
        const orderData = localStorage.getItem(
          "UnigemcOrder_" + this.order.lang_id
        );
        if (orderData) {
          this.order.note = JSON.parse(orderData).note || "";
        }

        const localCartData = localStorage.getItem(
          "UnigemcCart_" + this.order.lang_id
        );
        return localCartData ? JSON.parse(localCartData) : [];
      } catch (error) {
        console.error("Error parsing cart data from localStorage:", error);
        // Clear corrupted data and return empty array
        localStorage.removeItem("UnigemcCart_" + this.order.lang_id);
        return [];
      }
    },
    setCartLocalStorage(carts) {
      try {
        localStorage.setItem(
          "UnigemcCart_" + this.order.lang_id,
          JSON.stringify(carts)
        );

        // handle order data
        localStorage.setItem(
          "UnigemcOrder_" + this.order.lang_id,
          JSON.stringify({ note: this.order.note })
        );
      } catch (error) {
        console.error("Error saving cart data to localStorage:", error);
        toastr.error(shopMessages.cartSavingError);
      }
    },
    // end local storage
    addCart(product_id) {
      const indexCartItem = this.carts.findIndex(
        (element) => element.id == product_id
      );
      if (indexCartItem == -1) {
        this.fetchProduct(product_id);
      } else {
        this.carts[indexCartItem].quantity += 1;
      }

      this.addProductToCartLocalStorage(product_id);
      // toastr.options.positionClass = "toast-top-center";
      toastr.success(shopMessages.addItemToCartSuccess);
    },
    // Handle event from modal
    handleAddToCartFromModal(event) {
      const productId = event.detail.productId;
      this.addCart(productId);
    },
    fetchProduct(product_id) {
      if (!Array.isArray(product_id)) {
        product_id = [product_id];
      }
      $.ajax({
        url: site_url + "ajax/order/get-products",
        data: { product_id: product_id },
        dataType: "json",
        type: "GET",
        success: (response) => {
          if (response.error === 1) {
            toastr.error(response.message);
          } else {
            // add to cart
            let cartStorage = this.getCartLocalStorage();
            response.data.forEach((item) => {
              const product_id = item.id;
              const indexCartItem = cartStorage.findIndex(
                (element) => element.product_id == product_id
              );
              const quantity =
                indexCartItem != -1 ? cartStorage[indexCartItem].quantity : 1;
              this.carts.push({ ...item, ...{ quantity: quantity } });
            });
            this.charge();
          }
        },
      });
    },
    minusQuantityProduct(index) {
      if (this.carts[index].quantity > 1) {
        this.carts[index].quantity -= 1;
      }
      this.minusProductCartLocalStorage(this.carts[index].id);
      this.charge();
    },
    plusQuantityProduct(index) {
      this.carts[index].quantity += 1;
      this.plusProductCartLocalStorage(this.carts[index].id);
      this.charge();
    },
    removeProduct(index) {
      this.removeProductFromCartLocalStorage(this.carts[index].id);
      this.carts.splice(index, 1);
      this.charge();
    },
    recoverCart() {
      let cartStorage = this.getCartLocalStorage();
      let productListID = cartStorage.map((item) => item.product_id);
      if (productListID.length > 0) {
        this.fetchProduct(productListID);
      }
    },
    getShipFee() {
      let province_id = $('[name="province_id"]').val() ?? 1;
      $.ajax({
        url: site_url + "ajax/get-shipping-fee?province_id=" + province_id,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "GET",
        success: (response) => {
          if (response.error === 1) {
            toastr.error(response.message);
          } else {
            this.bill.ship_fee_province = Number(
              response.data.ship_fee_province
            );
            this.bill.ship_fee_on_weight = Number(
              response.data.ship_fee_on_weight
            );
            this.charge();
          }
        },
      });
    },
    formatVnd(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },
    formatUsd(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
      }).format(value);
    },
    charge() {
      let sub_total = 0;
      let total = 0;
      let shipping_fee = 0;
      let discount = 0;
      let weightProductTotal = 0;
      let shipFeeOnWeight = this.bill.ship_fee_on_weight;
      let shipFeeProvince = this.bill.ship_fee_province;
      // product bill
      this.carts.forEach((item) => {
        const price = Number(
          item.price_discount > 0 && item.price_discount < item.price
            ? item.price_discount
            : item.price
        );
        const quantity = Number(item.quantity);

        total += price * quantity;
      });
      sub_total = total;
      if (this.order.delivery_type == HomeDeliveryType) {
        shipping_fee = weightProductTotal * shipFeeOnWeight + shipFeeProvince;
      } else {
        shipping_fee = 0;
      }

      total = total + shipping_fee;

      // handle voucher discount
      if (this.voucher !== null) {
        if (this.voucher.voucher_discount_type == "percentage") {
          discount = total * (this.voucher.voucher_discount_value / 100);
        }
        if (this.voucher.voucher_discount_type == "fixed_amount") {
          discount = this.voucher.voucher_discount_value;
        }
      }

      this.bill.sub_total = sub_total * this.order.exchange_rate;
      this.bill.total = (total - discount) * this.order.exchange_rate;
      this.bill.shipping_fee = shipping_fee;
      this.bill.discount = discount * this.order.exchange_rate;
      this.bill.weight_product_total = weightProductTotal;
    },
    applyVoucher() {
      $.ajax({
        url:
          site_url +
          "order/apply-voucher?voucher_code=" +
          this.order.voucher_code,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "GET",
        success: (response) => {
          if (response.error === 1) {
            toastr.error(response.message);
          } else {
            this.discount = response.data;
            this.charge();
          }
        },
        error: (xhr) => {
          toastr.error(shopMessages.voucherError);
        },
      });
    },
    formatCurrency(value) {
      if (this.order.lang_id == 1) {
        return this.formatVnd(value);
      } else {
        return this.formatUsd(value);
      }
    },
    showUnitPrice(product) {
      const price =
        product.price_discount > 0 && product.price_discount < product.price
          ? product.price_discount
          : product.price;

      return this.formatCurrency(price);
    },
    showUnitTotalPrice(product) {
      const price =
        product.price_discount > 0 && product.price_discount < product.price
          ? product.price_discount
          : product.price;

      return this.formatCurrency(price * product.quantity);
    },
    checkout() {
      if (this.order.customer_id === 0) {
        console.log("customer_id", this.order.customer_id);
        toastr.warning(shopMessages.loginToCheckout);
        return;
      }

      window.location.href = checkoutUrl; // Redirect to checkout page
    },
  },
  mounted() {
    // init default order values FIRST before recovering cart
    this.order.lang_id = lang_id;
    this.order.currency = currency;
    this.order.exchange_rate = exchange_rate;

    // now recover cart with correct lang_id
    this.recoverCart();

    $('[name="province_id"]').change((data) => {
      this.getShipFee();
    });

    // Listen for custom event from modal
    window.addEventListener(
      "addToCartFromModal",
      this.handleAddToCartFromModal
    );
  },

  beforeUnmount() {
    // Clean up event listener
    window.removeEventListener(
      "addToCartFromModal",
      this.handleAddToCartFromModal
    );
  },

  watch: {
    "order.note": function (newValue) {
      // Update note data in localStorage
      try {
        localStorage.setItem(
          "UnigemcOrder_" + this.order.lang_id,
          JSON.stringify({ note: this.order.note })
        );
      } catch (error) {
        console.error("Error saving cart data to localStorage:", error);
        toastr.error(shopMessages.cartSavingError);
      }
    },
  },
});
