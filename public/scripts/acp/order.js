const orderApp = Vue.createApp({
  data() {
    return {
      order: {
        country: 0,
        customer_id: 0,
        delivery_type: 1,
        full_name: "",
        phone: "",
        email: "",
        payment_status: 0,
        customer_paid: 0,
        voucher_code: "",
        exchange_rate: 1, // Default exchange rate
      },
      order_items: [],
      products_search: [],
      product_keyword_search: "",
      customers_search: [],
      customer_keyword_search: "",
      delay: null,
      bill: {
        sub_total: 0,
        total: 0,
        shipping_fee: 0,
        discount: 0,
        weight_product_total: 0,
        ship_fee_province: 0,
        ship_fee_on_weight: 0,
      },
      discount: {
        value: 0,
        type: "",
      },
    };
  },
  methods: {
    eventSearchCustomer() {
      if (this.delay) {
        clearTimeout(this.delay);
        this.delay = null;
      }

      this.delay = setTimeout(() => {
        this.searchCustomer();
      }, 800);
    },
    eventSearchProduct() {
      if (this.delay) {
        clearTimeout(this.delay);
        this.delay = null;
      }
      this.delay = setTimeout(() => {
        this.searchProduct();
      }, 800);
    },
    searchProduct() {
      let formData = new FormData();

      let csName = $("#csname").val();
      let csToken = $("#cstoken").val();

      // add a sign token
      formData.append(csName, csToken);
      formData.append("keyword_search", this.product_keyword_search);

      // send data to server
      $.ajax({
        url: search_product_url,
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "POST",
        success: (response) => {
          if (response.error === 1) {
            SwalAlert.fire({
              icon: "error",
              title: response.message,
            });
          } else {
            this.products_search = response.data;
          }
        },
      });
    },
    searchCustomer() {
      let formData = new FormData();

      let csName = $("#csname").val();
      let csToken = $("#cstoken").val();

      // add a sign token
      formData.append(csName, csToken);
      formData.append("keyword_search", this.customer_keyword_search);

      // send data to server
      $.ajax({
        url: search_customer_url,
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "POST",
        success: (response) => {
          if (response.error === 1) {
            SwalAlert.fire({
              icon: "error",
              title: response.message,
            });
          } else {
            this.customers_search = response.data;
          }
        },
      });
    },
    selectCustomer(index) {
      const customer = this.customers_search[index];
      this.order.full_name = customer.cus_full_name;
      this.order.phone = customer.cus_phone;
      this.order.email = customer.cus_email;
      this.order.country = customer.country_id;
      this.order.customer_id = customer.id;
      $("#customer-modal").modal("hide");

      $('[name="country_id"]').val(customer.country_id).change();

      if (
        customer.country_id == VietNamCountryId &&
        customer.province_id &&
        customer.district_id &&
        customer.ward_id
      ) {
        $('[name="province_id"]').val(customer.province_id).change();
        $('[name="district_id"]').val(customer.district_id).change();
        $('[name="ward_id"]').val(customer.ward_id).change();
        $('[name="address"]').val(customer.cus_address).change();
      }
      SwalAlert.fire({
        icon: "success",
        title: `Đã chon khách hàng ${customer.cus_full_name}`,
      });
    },
    addProduct(index) {
      const productID = this.products_search[index].id;
      const hasProduct = (element) => element.id == productID;
      const indexOrderItem = this.order_items.findIndex(hasProduct);
      if (indexOrderItem == -1) {
        this.order_items.push({
          ...this.products_search[index],
          ...{
            quantity: 1,
          },
        });
        SwalAlert.fire({
          icon: "success",
          title: messages.addItemToCartSuccess,
        });
      } else {
        this.order_items[indexOrderItem].quantity += 1;
        SwalAlert.fire({
          icon: "success",
          title: messages.increaseItemQuantity,
        });
      }
      this.charge();
    },
    minusQuantityProduct(index) {
      if (this.order_items[index].quantity > 1) {
        this.order_items[index].quantity -= 1;
      }
      this.charge();
    },
    plusQuantityProduct(index) {
      this.order_items[index].quantity += 1;
      this.charge();
    },
    deleteProduct(index) {
      this.order_items.splice(index, 1);
      this.charge();
      SwalAlert.fire({
        icon: "success",
        title: messages.deleteItemFromCartSuccess,
      });
    },
    charge() {
      let sub_total = 0;
      let total = 0;
      let shipping_fee = 0;
      let discount = 0;
      let weightProductTotal = 0;
      let shipFeeOnWeight = this.ship_fee_on_weight;
      let shipFeeProvince = this.ship_fee_province;
      // product bill
      this.order_items.forEach((item) => {
        const priceDiscount = Number(item.price_discount);
        const price = Number(item.price);
        const finalPrice =
          priceDiscount > 0 && priceDiscount < price ? priceDiscount : price;
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

      if (this.discount.type && this.discount.value) {
        if (this.discount.type == "percent") {
          discount = total * (this.discount.value / 100);
        }
        if (this.discount.type == "value") {
          discount = this.discount.value;
        }
      }

      this.bill.sub_total = sub_total * this.order.exchange_rate;
      this.bill.total = (total - discount) * this.order.exchange_rate;
      this.bill.shipping_fee = shipping_fee;
      this.bill.discount = discount * this.order.exchange_rate;
      this.bill.weight_product_total = weightProductTotal;
    },
    getShipFee() {
      let province_id = $('[name="province_id"]').val() ?? 1;
      $.ajax({
        url: get_shipping_fee_base_url + province_id,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "GET",
        success: (response) => {
          if (response.error === 1) {
            SwalAlert.fire({
              icon: "error",
              title: response.message,
            });
          } else {
            this.ship_fee_province = Number(response.data.ship_fee_province);
            this.ship_fee_on_weight = Number(response.data.ship_fee_on_weight);
            this.charge();
          }
        },
      });
    },
    applyVoucher() {
      $.ajax({
        url:
          base_url +
          "order/apply-voucher?voucher_code=" +
          this.order.voucher_code,
        dataType: "json",
        contentType: false,
        processData: false,
        type: "GET",
        success: (response) => {
          if (response.error === 1) {
            SwalAlert.fire({
              icon: "error",
              title: response.message,
            });
          } else {
            this.discount = response.data;
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
    getDisplayPrice(item) {
      const finalPrice =
        item.price_discount > 0 && item.price_discount < item.price
          ? item.price_discount
          : item.price;

      if (item.lang_id == 1) {
        return this.formatVnd(finalPrice);
      } else {
        return this.formatUsd(finalPrice);
      }
    },
  },
  mounted() {
    $('[name="province_id"]').change((data) => {
      this.getShipFee();
    });
    this.getShipFee();

    // Set initial values from old input
    this.order.voucher_code = voucherCode;
    this.order.full_name = full_name;
    this.order.phone = phone;
    this.order.email = email;
    this.order.payment_status = payment_status;
    this.order.customer_paid = customer_paid;
    this.order.exchange_rate = exchange_rate;
  },
});
