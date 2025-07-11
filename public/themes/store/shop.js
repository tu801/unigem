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
        verify_code: "",
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
    // Generate unique verify code
    generateVerifyCode() {
      const timestamp = Date.now().toString();
      const random = Math.random().toString(36).substring(2, 8);
      return timestamp + random;
    },

    // Initialize verify code
    initVerifyCode() {
      if (!this.order.verify_code) {
        this.order.verify_code = this.generateVerifyCode();
        this.saveVerifyCodeToStorage();
      }
    },

    // Save verify code to localStorage
    saveVerifyCodeToStorage() {
      try {
        localStorage.setItem(
          "UnigemVerifyCode_" + this.order.lang_id,
          this.order.verify_code
        );
      } catch (error) {
        console.error("Error saving verify code to localStorage:", error);
      }
    },

    // Get verify code from localStorage
    getVerifyCodeFromStorage() {
      try {
        const verifyCode = localStorage.getItem(
          "UnigemVerifyCode_" + this.order.lang_id
        );
        return verifyCode;
      } catch (error) {
        console.error("Error getting verify code from localStorage:", error);
        return null;
      }
    },

    // Clear cart and verify code
    clearCart() {
      try {
        // Clear cart data
        localStorage.removeItem("UnigemCart_" + this.order.lang_id);
        localStorage.removeItem("UnigemOrder_" + this.order.lang_id);
        localStorage.removeItem("UnigemCartVoucher_" + this.order.lang_id);
        localStorage.removeItem("UnigemVerifyCode_" + this.order.lang_id);

        // Reset data
        this.carts = [];
        this.voucher = null;
        this.order.note = "";
        this.order.voucher_code = "";
        this.order.verify_code = "";

        // Generate new verify code for next session
        this.initVerifyCode();

        this.charge();

        console.log("Cart cleared successfully");
      } catch (error) {
        console.error("Error clearing cart:", error);
      }
    },

    // Check URL params for verify_code (for success page)
    checkUrlForVerifyCode() {
      const urlParams = new URLSearchParams(window.location.search);
      const verifyCodeFromUrl = urlParams.get("verify_code");

      if (verifyCodeFromUrl) {
        // Verify if the code matches the stored one
        const storedVerifyCode = this.getVerifyCodeFromStorage();
        console.log("Verify code from URL:", verifyCodeFromUrl);
        console.log("Stored verify code:", storedVerifyCode);
        if (verifyCodeFromUrl === storedVerifyCode) {
          this.clearCart();
        }
      }
    },

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
          "UnigemOrder_" + this.order.lang_id
        );
        if (orderData) {
          this.order.note = JSON.parse(orderData).note || "";
        }

        // retrieve cart voucher data
        const voucherData = localStorage.getItem(
          "UnigemCartVoucher_" + this.order.lang_id
        );
        if (voucherData) {
          this.voucher = JSON.parse(voucherData);
        }

        // retrieve verify code
        const verifyCode = this.getVerifyCodeFromStorage();
        if (verifyCode) {
          this.order.verify_code = verifyCode;
        }

        const localCartData = localStorage.getItem(
          "UnigemCart_" + this.order.lang_id
        );
        return localCartData ? JSON.parse(localCartData) : [];
      } catch (error) {
        console.error("Error parsing cart data from localStorage:", error);
        // Clear corrupted data and return empty array
        localStorage.removeItem("UnigemCart_" + this.order.lang_id);
        return [];
      }
    },
    setCartLocalStorage(carts) {
      try {
        localStorage.setItem(
          "UnigemCart_" + this.order.lang_id,
          JSON.stringify(carts)
        );

        // handle order data
        localStorage.setItem(
          "UnigemOrder_" + this.order.lang_id,
          JSON.stringify({ note: this.order.note })
        );

        // save verify code
        this.saveVerifyCodeToStorage();
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

      // Initialize verify code if cart is empty or no verify code exists
      if (productListID.length === 0 || !this.order.verify_code) {
        this.initVerifyCode();
      }

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
    calculateFinalProductPrice(product) {
      // Calculate the final price of a product based on its discount
      const priceDiscount = Number(product.price_discount);
      const price = Number(product.price);
      const finalPrice =
        priceDiscount > 0 && priceDiscount < price ? priceDiscount : price;

      return finalPrice;
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
        const finalPrice = this.calculateFinalProductPrice(item);
        const quantity = Number(item.quantity);

        total += finalPrice * quantity;
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
          "ajax/order/apply-voucher?voucher_code=" +
          this.order.voucher_code,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "GET",
        success: (response) => {
          if (response.code === 200) {
            if (this.order.currency !== response.voucher.currency) {
              SwalAlert.fire({
                icon: "error",
                title: messages.invalidVoucherCurrency,
              });
            } else {
              this.voucher = response.voucher;
              toastr.success(
                shopMessages.voucherAppliedSuccess +
                  response.voucher.voucher_code
              );
              this.charge();
            }
          } else {
            toastr.error(response.message);
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
      const price = this.calculateFinalProductPrice(product);

      return this.formatCurrency(price * product.quantity);
    },
    checkout(event) {
      // Get the checkout URL from the clicked element's href attribute
      const checkoutUrl = event.target.href || event.currentTarget.href;

      if (this.order.customer_id === 0) {
        toastr.warning(shopMessages.loginToCheckout);
        return;
      }

      window.location.href = checkoutUrl; // Redirect to checkout page
    },
    getCustomer() {
      $.ajax({
        url: site_url + "ajax/customer/get-customer",
        dataType: "json",
        contentType: false,
        processData: false,
        type: "GET",
        success: (response) => {
          if (response.code === 200) {
            const customer = response.customerData;
            console.log("Customer data:", customer);
            this.order.customer_id = customer.customer_id;
            this.order.full_name = customer.full_name;
            this.order.phone = customer.phone;
            this.order.email = customer.email;
          }
        },
        error: (xhr) => {
          toastr.error(shopMessages.voucherError);
        },
      });
    },
  },
  mounted() {
    // init default order values FIRST before recovering cart
    this.order.lang_id = lang_id;
    this.order.currency = currency;
    this.order.exchange_rate = exchange_rate;

    // Check URL for verify_code (for order success page)
    this.checkUrlForVerifyCode();

    // check if customer is logged in
    this.getCustomer();

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
          "UnigemOrder_" + this.order.lang_id,
          JSON.stringify({ note: this.order.note })
        );
      } catch (error) {
        console.error("Error saving cart data to localStorage:", error);
        toastr.error(shopMessages.cartSavingError);
      }
    },
    voucher: function (newValue) {
      // Update voucher data in localStorage
      try {
        localStorage.setItem(
          "UnigemCartVoucher_" + this.order.lang_id,
          JSON.stringify(newValue)
        );
      } catch (error) {
        console.error("Error saving voucher data to localStorage:", error);
        toastr.error(shopMessages.cartSavingError);
      }
    },
  },
});
