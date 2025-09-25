<template>
  <div class="container py-5 animated-page">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow border-0 animated-card">
          <!-- صورة المنتج -->
          <img
            :src="product.watermark_path"
            class="card-img-top fade-in-img"
            style="max-height: 400px; object-fit: fill"
            alt="Product Image"
          />

          <!-- تفاصيل المنتج -->
          <div class="card-body fade-in-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h3 class="card-title mb-0">{{ product.title }}</h3>
              <span class="badge bg-success fs-5">{{ product.price }} $</span>
            </div>

            <p class="card-text text-muted" v-if="product.description">
              {{ product.description }}
            </p>

            <hr />

            <!-- إضافة إلى السلة -->
            <div v-if="authUser.role === 'user'">
              <form @submit.prevent="addToCart">
                <div class="row g-3 align-items-center">
                  <div class="col-sm-4">
                    <label for="quantity" class="form-label">الكمية</label>
                    <input
                      type="number"
                      min="1"
                      v-model="quantity"
                      class="form-control"
                      id="quantity"
                      required
                    />
                  </div>
                  <div class="col-sm-8 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                      <span v-if="!loading">أضف إلى السلة</span>
                      <span v-else class="spinner-border spinner-border-sm"></span>
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- زر تعديل للبائع -->
            <div class="mt-3" v-if="authUser.role === 'saler' && product.user_id === authUser.id">
              <router-link
                :to="{ name: 'editProduct', params: { id: product.id } }"
                class="btn btn-warning"
              >
                تعديل المنتج
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      product: {},
      isCreated: null,
      quantity: 1,
      loading: false,
    };
  },

  computed: {
    ...mapGetters(["authUser"]),
  },

  methods: {
    async addToCart() {
      try {
        this.loading = true;
        const userId = this.authUser.id;
        // Check if the cart doesn't added
        if (!this.isCreated) {
          const cartResponse = await axios.post("/cart", {
            user_id: userId,
            status: false,
          });


          const userCart = await axios.get(`/userCart/${userId}`);
          const cart_id = userCart.data.id;

          const item = await axios.post("/cartItems", {
            cart_id,
            product_id: this.product.id,
            status: 0,
            quantity: this.quantity,
          });
            window.location.reload();

          if (item.data && cartResponse.data) {
            alert("تمت إضافة المنتج إلى السلة");
            this.$router.push("/");
          }
        }
        //// If the cart doesn't added then we will just add the items
        ///// We get the user's cart by its id.
        else {
          const userCart = await axios.get(`/userCart/${userId}`);
          const cart_id = userCart.data.id;

          await axios.post("/cartItems", {
            cart_id,
            product_id: this.product.id,
            quantity: this.quantity,
          });

          alert("تمت إضافة المنتج بنجاح");
          this.$router.push("/");
        }
      } catch (error) {
        console.error("حدث خطأ أثناء إضافة المنتج إلى السلة:", error);
      } finally {
        this.loading = false;
      }
    },
  },

  async mounted() {
    const product_id = parseInt(this.$route.params.id);
    try {
      const response = await axios.get(`product/${product_id}`);
      this.product = response.data;

      const cartCreated = await axios.get(`/cart/isCreated/${this.authUser.id}`);
      this.isCreated = cartCreated.data;
    } catch (error) {
      console.error("فشل تحميل تفاصيل المنتج:", error);
    }
  },
};
</script>

<style scoped>
/* العناوين والتفاصيل */
.card-title {
  font-size: 1.5rem;
}
.card-text {
  font-size: 1rem;
  line-height: 1.6;
}
@media (max-width: 768px) {
  .card-title {
    font-size: 1.2rem;
  }
}

/* أنيميشن عام للصفحة */
.animated-page {
  animation: fadeInUp 0.6s ease forwards;
}

/* أنيميشن للبطاقات والمحتوى */
.animated-card {
  animation: zoomIn 0.7s ease forwards;
}
.fade-in-content {
  animation: fadeIn 1s ease forwards;
  opacity: 0;
  animation-delay: 0.3s;
}
.fade-in-img {
  animation: fadeIn 1s ease forwards;
  opacity: 0;
  animation-delay: 0.2s;
}

/* keyframes */
@keyframes fadeInUp {
  0% {
    transform: translateY(40px);
    opacity: 0;
  }
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes zoomIn {
  0% {
    transform: scale(0.95);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes fadeIn {
  to {
    opacity: 1;
  }
}
</style>
