<template>
  <div class="container py-4">

    <!-- زر إضافة منتج -->
    <div class="mb-3 text-start">
      <router-link
        v-if="isLoggedIn && authUser && authUser.role === 'seeler'"
        to="/add"
        class="btn btn-success shadow"
      >
        + Add new product
      </router-link>
      <router-link
        v-else-if="!isLoggedIn && authUser.role === 'seeler'"
        to="/login"
        class="btn btn-primary shadow"
      >
        Login for add new product
      </router-link>
    </div>

    <!-- المنتجات الأكثر مبيعًا -->
    <section class="mb-5" v-if="most_selling.length > 0">
      <h2 class="text-center mb-4 text-success fw-bold section-title">The most selling</h2>
      <div class="d-flex gap-3 overflow-auto">
        <div
          class="card shadow-sm border-0 animated-card"
          style="min-width: 220px"
          v-for="item in most_selling"
          :key="item.product.id"
        >
          <router-link :to="{ name: 'productDetails', params: { id: item.product.id } }" class="text-decoration-none">
            <img
              :src="item.product.watermark_path"
              class="card-img-top"
              style="height: 160px; object-fit: fill"
              loading="lazy"
            />
            <div class="card-body text-center">
              <span
                class="badge mb-1"
                :class="item.product.type === 'image' ? 'bg-success' : 'bg-primary'"
              >
                {{ item.product.type }}
              </span>
              <div class="small text-muted">Sales: {{ item.total_sales }}</div>
              <h6 class="mt-2 text-dark">{{ item.product.title }}</h6>
              <div class="fw-bold text-success">{{ item.product.price }} $</div>
            </div>
          </router-link>
        </div>
      </div>
    </section>
    <!-- فلترة حسب التصنيف والبحث -->
    <div class="row align-items-center mb-4">
      <div class="col-md-4">
        <select
          v-model="selectedFilter"
          @change="filtering"
          class="form-select shadow-sm"
        >
          <option value="all">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.name">
            {{ category.name }}
          </option>
        </select>
      </div>
      <div class="col-md-8 mt-2 mt-md-0">
        <input
          type="search"
          v-model="query"
          @input="search"
          class="form-control shadow-sm"
          placeholder="Search for product..."
        />
      </div>
    </div>
    <!-- عرض كل المنتجات -->
    <div class="row g-4">
      <div
        v-for="(product, index) in productWithLikes"
        :key="product.id"
        class="col-md-4 animated-card mb-2"
        :style="{ animationDelay: (index * 0.1) + 's' }"
      >
        <div class="card h-100 border-0" style="box-shadow: 10px 10px 7px black;">
          <router-link
            :to="{ name: 'productDetails', params: { id: product.id } }"
            class="text-decoration-none text-dark"
          >
          <img
              :src="product.watermark_path"
              class="card-img-top"
              style="height: 200px; object-fit: fill"
              loading="lazy"
            />
            <div class="card-body">
              <h5 class="card-title">{{ product.title }}</h5>
              <div class="d-flex justify-content-between small">
                <span class="text-success fw-bold">{{ product.price }} $</span>
                <span><i class="fa fa-heart text-danger"></i> {{ product.productLikes }}</span>
              </div>
            </div>
          </router-link>

          <button class="btn btn-success type-btn" >{{product.type}}</button>

          <!-- أدوات التفاعل -->
          <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center px-3">
            <span class="text-muted">
              <template v-if="authUser && authUser.id !== product.user_id">
                {{ product.user.name }}
              </template>
              <template v-else>
                <span class="badge bg-success text-white">You</span>
              </template>

            </span>
            <!-- Inform btn -->
            <div class="d-flex gap-2 align-items-center">
              <router-link
                :to="{ name: 'inform', params: { id: product.id } }"
                class="btn btn-sm btn-outline-danger"
              >
                <i class="fa-solid fa-flag"></i>
              </router-link>
              <!-- Comment btn -->
              <router-link
                :to="{ name: 'comment', params: { id: product.id } }"
                class="btn btn-sm btn-outline-secondary m-1"
              >
                <i class="fa-solid fa-comment"></i>
              </router-link>
              <!-- Like Btn -->
            <button
            @click="toggleLike(product.id)"
            class="btn btn-sm btn-outline-success"
            :style="getLikeButtonStyle(product.id)"
            :disabled="likeInProgress[product.id]"
            >
            <i class="fa-solid fa-thumbs-up"></i>
            </button>

            </div>
          </div>

          <!-- أدوات البائع -->
          <div
            class="text-end p-2"
            v-if="authUser && authUser.id === product.user_id"
          >
            <router-link
              :to="{ name: 'editProduct', params: { id: product.id } }"
              class="btn btn-sm btn-info me-2 m-1"
            >
              تعديل
            </router-link>
            <button @click="deleteProduct(product.id)" class="btn btn-sm btn-danger">
              حذف
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/axios'
import { mapState, mapGetters } from 'vuex'

export default {
  data() {
    return {
        query: '',
        selectedFilter: 'all',
        products: [],
        categories: [],
        reviews: [],
        most_selling: [],
        likedProducts: [],
        allLikes: [],
        addedToCart: [],
        likeInProgress: {},
    }
  },

  computed: {
    ...mapState(['isLoggedIn', 'authUser']),
    ...mapGetters(['authUser']),

    productWithLikes() {
      return this.products.map(product => {
        const productLikes = this.allLikes.filter(like => like.product_id === product.id).length
        return {
          ...product,
          productLikes,
        }
      })
    },
  },

  methods: {
    async fetchData() {
      const res = await axios.get('/product');
      console.log(res.data);
      this.products = res.data.products;
      this.allLikes = res.data.likes;

      const best = await axios.get('/most-selling')
      this.most_selling = best.data
    },

    async search() {
      const res = await axios.get('/product')
      this.products = this.query
        ? res.data.products.filter(p =>
            p.title?.toLowerCase().includes(this.query.toLowerCase()) ||
            (p.description || '').toLowerCase().includes(this.query.toLowerCase())
          )
        : res.data.products

      this.allLikes = res.data.likes
    },

    async filtering() {
      const res = await axios.get('/product')
      this.products =
        this.selectedFilter === 'all'
          ? res.data.products
          : res.data.products.filter(p => p.category.name === this.selectedFilter)

      this.allLikes = res.data.likes
    },

    async deleteProduct(id) {
      try {
        await axios.delete(`/product/${id}`)
        this.products = this.products.filter(p => p.id !== id)
        alert('تم حذف المنتج بنجاح')
      } catch (e) {
        console.error('خطأ في حذف المنتج', e)
      }
    },

    async setLikedProducts() {
      const res = await axios.get('/review')
      this.reviews = res.data

      const userId = this.authUser.id
      this.likedProducts = this.reviews
        .filter(r => r.user_id === userId && r.rating === 1)
        .map(r => r.product_id)
    },

    getLikeButtonStyle(productId) {
      return this.likedProducts.includes(productId) ? 'color: blue;' : 'color: gray;'
    },

    async toggleLike(productId) {
  const userId = this.authUser.id;

  // تأكد أن الكائن reactive
  if (!this.likeInProgress) this.likeInProgress = {}

  // امنع النقر المتكرر
  if (this.likeInProgress[productId]) return;

  this.likeInProgress[productId] = true; // بدل this.$set

  try {
    const liked = this.likedProducts.includes(productId);

    if (liked) {
      const review = this.reviews.find(
        r => r.user_id === userId && r.product_id === productId && r.rating === 1
      );
      if (review) {
        await axios.delete(`/review/${review.id}`);
        this.allLikes = this.allLikes.filter(
          like => like.product_id !== productId || like.user_id !== userId
        );
      }
    } else {
      const res = await axios.get(`isLiked/${productId}/${userId}`);
      if (!res.data.isLiked) {
        await axios.post('/review', {
          user_id: userId,
          product_id: productId,
          rating: 1,
        });
        this.allLikes.push({
          user_id: userId,
          product_id: productId,
          rating: 1,
        });
      }
    }

    await this.setLikedProducts();
  } catch (err) {
    console.error('خطأ أثناء الإعجاب:', err);
  } finally {
    this.likeInProgress[productId] = false; // بدل this.$set
  }
}


  },

  async created() {
    try {
      await this.fetchData()
      const catRes = await axios.get('/category')
      this.categories = catRes.data

      const reviewRes = await axios.get('/review')
      this.reviews = reviewRes.data
      this.setLikedProducts()
    } catch (err) {
      console.error('حدث خطأ أثناء التحميل:', err)
    }
  },
}
</script>

<style scoped>


.card-title {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.type-btn{
    position: absolute;
    top: 1px;
    left: 3px;
}
/* ====== ANIMATION CSS ====== */
@keyframes fadeSlideUp {
  0% {
    opacity: 0;
    transform: translateY(40px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes zoomIn {
  0% {
    opacity: 0;
    transform: scale(0.95);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.animated-card {
  opacity: 0;
  animation: fadeSlideUp 0.6s ease forwards;
}

.section-title {
  opacity: 0;
  animation: zoomIn 0.7s ease forwards;
  animation-delay: 0.2s;
}
</style>
