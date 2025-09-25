<template>
  <div class="parent-div">
    <!-- Form for add new comment -->
    <div class="mt-1 w-100">
      <form @submit.prevent="sendComment" class="w-50 m-2">
        <textarea class="m-auto form-control" cols="30" rows="2" placeholder="Write your comment" v-model="comment"></textarea>
        <button type="submit" class="mt-2 btn btn-success">Submit</button>
      </form>
    </div>

    <!-- Comments List -->
    <ul class="mt-4 p-2 text-center comment-list">
      <li
        v-for="review in allReview"
        :key="review.id"
        class="bg-white mb-2 shadow animated-comment"
      >
        <div class="row px-2 py-2">
          <div class="col-md-8 col-sm-8 col-8 text-start">
            <b>{{ review.user.name }}</b><br />
          </div>
          <div
            class="col-md-4 col-sm-4 col-4 text-end delete"
            @click="deleteComment(review.id)"
            v-if="authUser.id === review.user_id"
          >
            <i class="fa-solid fa-trash text-danger m-2"></i>
          </div>
        </div>
        <i>{{ review.comment }}</i>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "@/axios";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      comment: "",
      allReview: [],
    };
  },

  computed: {
    ...mapGetters(["authUser"]),
  },

  async created() {
    this.getComments();
  },

  methods: {
    async getComments() {
      this.comment = "";
      const product_id = parseInt(this.$route.params.id);
      const response = await axios.get(`/reviews/productComments/${product_id}`);
      this.allReview = response.data;
    },

    async sendComment() {
      try {
        await axios.post("/review", {
          user_id: this.authUser.id,
          product_id: parseInt(this.$route.params.id),
          comment: this.comment,
          rating: null,
        });
        this.getComments();
      } catch (error) {
        console.error("حدث خطأ أثناء إضافة التعليق:", error);
      }
    },

    async deleteComment(reviewId) {
      try {
        await axios.delete(`/review/${reviewId}`);
        this.getComments();
      } catch (error) {
        console.error("خطأ أثناء حذف التعليق:", error);
      }
    },
  },
};
</script>

<style scoped>
.parent-div {
  display: flex;
  flex-direction: column;
  align-items: center;
}

ul {
  width: 70%;
  padding-left: 0;
}

ul li {
  width: 90%;
  border-radius: 10px;
  list-style-type: none;
  background-color: #fff;
  transition: all 0.4s ease;
}

/* زر الحذف */
.delete {
  cursor: pointer;
}

/* أنيميشن fade-in */
.animated-comment {
  animation: fadeIn 0.7s ease-in-out;
}

/* إعدادات النص */
b {
  font-weight: 600;
  color: #333;
}

/* استجابة التصميم */
@media (max-width: 474px) {
  b {
    left: 22%;
    position: absolute;
  }
}

@media (min-width: 475px) {
  b {
    left: 20%;
    position: absolute;
  }
}

/* Keyframes */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
