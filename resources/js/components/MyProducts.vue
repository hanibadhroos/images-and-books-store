<template>
  <div v-if="authUser.role === 'seeler'" class="my-products">
    <div class="text-center bg-info py-2 mb-4 rounded">
      <b class="fs-4 text-white">My Products <i class="fa-solid fa-cart-shopping text-dark"></i></b>
    </div>

    <ul class="d-flex flex-wrap gap-4 justify-content-center">
      <li
        class="product-card fade-in m-2"
        v-for="product in products"
        :key="product.id"
      >
        <router-link
          :to="{ name: 'productDetails', params: { id: product.id } }"
          class="text-decoration-none text-dark"
        >
          <img
            :src="product.watermark_path"
            alt="Product image"
            class="product-image object-fit-fill"
          />
          <div class="p-2">
            <b class="d-block text-center">{{ product.title }}</b>
            <i class="text-success d-block text-center">{{ product.price }} $</i>
            <div class="text-center small text-muted">
              <span>المبيعات: {{ product.sales || 0 }}</span>
            </div>
          </div>
        </router-link>

        <div class="d-flex justify-content-center gap-2 mb-2">
          <button class="btn btn-danger btn-sm mr-2" @click="deleteProduct(product.id)">حذف</button>
          <router-link
            :to="{ name: 'editProduct', params: { id: product.id } }"
            class="btn btn-success btn-sm"
          >تعديل</router-link>
        </div>
      </li>
    </ul>
  </div>
  <div v-else class="text-center mt-5">
    <h2 class="alert alert-danger">
      لا يمكنك الوصول - <i>أنت مستخدم مشتري فقط</i>
    </h2>
  </div>
</template>

<script>
import axios from '@/axios';
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      products: [],
    };
  },

  computed: {
    ...mapGetters(['authUser']),
  },

  watch: {
    authUser: {
      immediate: true,
      handler(newUser) {
        if (newUser && newUser.id) {
          this.fetchData();
        }
      },
    },
  },

  async mounted() {
    if (this.authUser && this.authUser.id) {
      await this.fetchData();
    }
  },

  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`/myProducts/${this.authUser.id}`);
        this.products = response.data;
      } catch (error) {
        console.error('فشل تحميل المنتجات:', error);
      }
    },

    async deleteProduct(id) {
      try {
        await axios.delete(`/product/${id}`);
        this.products = this.products.filter((p) => p.id !== id);
        alert('تم حذف المنتج بنجاح');
      } catch (error) {
        console.error('خطأ أثناء حذف المنتج:', error);
      }
    },
  },
};
</script>

<style scoped>
.my-products {
  padding: 20px;
}

.product-card {
  width: 220px;
  border: 2px solid #28a745;
  border-radius: 8px;
  overflow: hidden;
  background: #fefefe;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.product-image {
  height: 180px;
  width: 100%;
  object-fit: cover;
}

/* Fade in animation */
.fade-in {
  animation: fadeIn 0.6s ease forwards;
  opacity: 0;
}

@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 576px) {
  .product-card {
    width: 100%;
  }
}
</style>
