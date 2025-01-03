
<template>
  <div class="payment-page">
    <h1>Payment</h1>

    <!-- تفاصيل الطلب -->
    <section class="order-details">
      <h2>Order Details</h2>
      <table class="order-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in orderItems" :key="item.id">
            <td>{{ item.product_name }}</td>
            <td>{{ item.quantity }}</td>
            <td>{{ formatCurrency(item.price) }}</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"><b>Total </b></td>
            <td> <b>{{ formatCurrency(totalAmount) }} </b></td>
          </tr>
        </tfoot>
      </table>
    </section>

    <!-- خيارات الدفع -->
    <section class="payment-methods">
      <h2>اختر طريقة الدفع</h2>
      <div v-for="method in paymentMethods" :key="method.id" class="payment-method">
        <input
          type="radio"
          :id="method.id"
          :value="method.id"
          v-model="selectedPaymentMethod"
        />
        <label :for="method.id">{{ method.name }}</label>
      </div>
    </section>

    <!-- أزرار التحكم -->
    <section class="actions">
      <button @click="confirmPayment" :disabled="!selectedPaymentMethod" class="btn btn-success">
        تأكيد الدفع
      </button>
      <button @click="goBack" class="back-btn">رجوع</button>
    </section>
  </div>
</template>

<script>
import axios from '@/axios';
export default {
  data() {
    return {
      orderItems: [], // بيانات الطلب
      totalAmount: 0, // الإجمالي
      paymentMethods: [
        { id: "paypal", name: "PayPal" },
        { id: "credit_card", name: "بطاقة ائتمان" },
        { id: "bank_transfer", name: "تحويل بنكي" },
      ],
      selectedPaymentMethod: null, // الطريقة المختارة
    };
  },
  mounted() {
    this.fetchOrderDetails();
  },
  methods: {
    async fetchOrderDetails() {
        const order_id =parseInt(this.$route.params.order_id);
        console.log(order_id);
      // جلب تفاصيل الطلب من API
      // البيانات هنا للعرض فقط

     const response = await axios.get(`/orderDetails/${order_id}`);
     console.log(response.data);
      this.orderItems = response.data;
      this.totalAmount = this.orderItems.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
      );
    },
    formatCurrency(amount) {
      return `${amount.toFixed(2)} ريال`;
    },
    confirmPayment() {
      // إرسال الطلب وتأكيد الدفع
      const payload = {
        paymentMethod: this.selectedPaymentMethod,
        totalAmount: this.totalAmount,
      };
      // طلب إلى السيرفر
      this.$http
        .post("/api/payments", payload)
        .then((response) => {
          alert("تم الدفع بنجاح!");
          this.$router.push("/order-confirmation");
        })
        .catch((error) => {
          alert("حدث خطأ أثناء الدفع.");
          console.error(error);
        });
    },
    goBack() {
      this.$router.push("/mycart");
    },
  },
};
</script>

<style scoped>
.payment-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}
h1,
h2 {
  text-align: center;
  color: #333;
}
.order-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}
.order-table th,
.order-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}
.order-table th {
  background-color: #f4f4f4;
}
.payment-methods {
  margin-bottom: 20px;
}
.payment-method {
  margin: 10px 0;
}
.actions {
  text-align: center;
}
.confirm-btn {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}
.confirm-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
.back-btn {
  background-color: #f44336;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  margin-left: 10px;
}
</style>
