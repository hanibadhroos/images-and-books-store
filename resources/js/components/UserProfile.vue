<template>
  <div v-if="authUser && authUser.role === 'seeler'">
    <div class="row m-0 dashboard">
      <!-- الإحصائيات -->
      <section class="col-md-9 py-4 px-3 bg-light">
        <div class="row gap-3 justify-content-start">
          <div class="m-2 col-md-4 stat-card bg-primary text-white fade-in-up">
            <h5>Totale profits</h5>
            <div class="fs-4 mt-2">{{ totalStock }} $</div>
          </div>
          <div class="m-2 col-md-4 stat-card bg-info text-white fade-in-up">
            <h5>My Products</h5>
            <div class="fs-4 mt-2">{{ productsNumber }}</div>
          </div>
          <div class="m-2 col-md-3 stat-card bg-success text-white fade-in-up">
            <h5>Withdrowable</h5>
            <div class="fs-4 mt-2">{{ availableStock }} $</div>
          </div>
        </div>
      </section>

      <!-- الشريط الجانبي -->
      <aside class="col-md-3 sidebar text-white text-center d-flex flex-column align-items-center justify-content-between">
        <div>
          <h3>{{ authUser.name }}</h3>
          <address>{{ authUser.email }}</address>
          <hr class="w-100 border-light" />
        </div>
        <div class="d-flex flex-column gap-2 w-100 links">
          <router-link class="btn btn-outline-light" to="/myProducts">My Products</router-link>
          <router-link to="/add" class="btn btn-outline-primary">Add product</router-link>
          <button class="btn btn-outline-warning" @click="withdrawAmount">Withdrow</button>
        </div>
      </aside>
    </div>
  </div>

  <div v-else class="text-center mt-5">
    <h2 class="alert alert-danger"> Forbidden, <i>You are only Buyer, Not Seeler</i></h2>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import axios from '@/axios';

export default {
  name: 'UserProfile',

  data() {
    return {
      productsNumber: null,
      totalStock: 0,
      availableStock: 0,
    };
  },

  computed: {
    ...mapGetters(['authUser']),
  },

    watch: {
    authUser: {
        immediate: true,
        handler(newVal) {
        if (newVal && newVal.id) {
            this.loadUserData(newVal.id);
        }
        }
    }
    },

    methods: {
    async loadUserData(user_id) {
        try {
        const [productRes, stockRes, availableRes] = await Promise.all([
            axios.get(`/myProducts/${user_id}`),
            axios.get(`/userStock/${user_id}`),
            axios.get(`/available-stock/${user_id}`)
        ]);

        this.productsNumber = productRes.data.length;
        this.totalStock = stockRes.data;
        this.availableStock = availableRes.data;
        } catch (error) {
        console.error('Error loading profile data:', error);
        }
    },

    async withdrawAmount() {
        if (this.availableStock <= 0) {
        alert("رصيدك غير كافٍ للسحب.");
        return;
        }

        try {
        const response = await axios.post('/withdraw', {
            user_id: this.authUser.id,
            amount: this.availableStock,
        });

        if (response.data.success) {
            alert("تم إرسال طلب السحب بنجاح.");
            this.availableStock = 0;
        } else {
            alert("فشل السحب: " + response.data.message);
        }
        } catch (error) {
        console.error("Withdrawal Error:", error);
        alert("حدث خطأ أثناء معالجة السحب.");
        }
    }
    }



}
</script>

<style scoped>
.dashboard {
  background: #f4f4f4;
  min-height: 100vh;
}

.stat-card {
  padding: 20px;
  border-radius: 10px;
  transition: transform 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
  transform: translateY(-5px);
}

.sidebar {
  background-color: #343a40;
  min-height: 525px;
  border-top-left-radius: 15px;
  border-bottom-left-radius: 15px;
}

.fade-in-up {
  animation: fadeInUp 0.7s ease-out forwards;
  opacity: 0;
  transform: translateY(20px);
}

.links{
    position: absolute;
    top: 250px;
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    border-radius: 0;
    margin-top: 1rem;
  }
}
</style>
