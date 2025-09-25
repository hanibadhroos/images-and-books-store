<template>
  <ul class="m-2 image-gallery">
    <li v-for="image in images" :key="image.id" class="image-item fade-in">
      <router-link :to="{ name: 'productDetails', params: { id: image.id } }" class="d-block text-decoration-none">
        <i class="position-absolute btn btn-success price-badge">{{ image.price }} $</i>
        <img :src="'storage/' + image.file_path" alt="Image product" class="w-100 product-image object-fit-fill" />
        <b class="d-block mt-2 text-center">{{ image.title }}</b>
        <hr class="my-2" />
        <div class="col-12 text-center">
          <router-link :to="{ name: 'productDetails', params: { id: image.id } }" class="text-primary small">Show Details</router-link>
        </div>
      </router-link>
    </li>
  </ul>
</template>

<script>
import axios from '@/axios';
export default {
  data() {
    return {
      images: [],
    };
  },

  async mounted() {
    const response = await axios.get('/images');
    this.images = response.data;
  },
};
</script>

<style scoped>
/* التصميم العام */
.image-gallery {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding: 0;
  gap: 1rem;
}

.image-item {
  width: 250px;
  border: 2px solid #28a745;
  border-radius: 8px;
  padding: 10px;
  background: #f9f9f9;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* تأثير hover */
.image-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
}

/* صورة المنتج */
.product-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 4px;
}

/* شارة السعر */
.price-badge {
  top: 8px;
  left: 8px;
  font-size: 0.8rem;
  padding: 3px 7px;
  z-index: 10;
}

/* نص العنوان */
b {
  color: #333;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* أنيميشن Fade In */
.fade-in {
  animation: fadeInUp 0.6s ease forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(25px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* الريسبونسيف */
@media (max-width: 576px) {
  .image-item {
    width: 90%;
  }
}
</style>
