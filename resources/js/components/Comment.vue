<template>
    <div class="parent-div">
        <!-- Form for add new comment -->
        <div class="mt-1">
            <form @submit.prevent="sendComment">
                <textarea class="m-auto" cols="30" rows="2" placeholder="Write your comment" v-model="comment"></textarea> <br>
                <button type="submit" class="mt-1">Submit</button>

            </form>
        </div>

        <ul class="mt-4 p-2 text-center">
            <li class="bg-opacity-100  bg-white ml-auto mr-auto mb-2" v-for="review in allReview" :key="review.id">
                <div class="row">
                    <div class="col-md-8 col-sm-8 col-8">
                        <b>{{ review.user.name }}</b> <br>
                    </div>
                    <div class="col-md-4 col-sm-4 col-4 text-right delete" @click="deleteComment(review.id)" v-if="authUser.id === review.user_id">
                        <i class="fa-solid fa-trash text-danger m-2"></i>
                    </div>
                </div>
                <i>{{review.comment}}</i>
            </li>
         </ul>

    </div>
</template>

<script>
import axios from "@/axios";
import {mapGetters} from 'vuex';
export default {
    data(){
        return{
            comment:'',
            allReview:[]
        }
    },

    async created(){
            const product_id = parseInt(this.$route.params.id);
            const response = await axios.get(`/reviews/productComments/${product_id}`);
            this.allReview= response.data
    },
    computed:{
        ...mapGetters(['authUser'])
    },
    methods:{
        async getComments(){
            this.comment='';
            const product_id = parseInt(this.$route.params.id);
            const response = await axios.get(`/reviews/productComments/${product_id}`);
            this.allReview= response.data
        },
        async sendComment(){
            try{
                const response = await axios.post('/review',{
                    user_id:this.authUser.id,
                    product_id: parseInt(this.$route.params.id),
                    comment: this.comment,
                    rating:null
                });
                this.getComments();
            }
            catch(error)
            {
                console.error("an error accurred while add new comment===> ", error);
            }
        },
        async deleteComment(reviewId){
            const response = await axios.delete(`/review/${reviewId}`);
            this.getComments();
        }
    }


}
</script>


<style scoped>
.parent-div{
    display: flex;
    flex-direction: column;
    align-items: center;
}

ul{
    width: 70%;
}
ul li{
    width: 90%;
    border-radius: 10px;
}

.delete{
    cursor:pointer;
}

@media (min-width: 475px) {
    b{
        left: 20%;
        position: absolute;
    }
}

@media (min-width:200px) and (max-width: 474px){
    b{
        left: 22%;
        position: absolute;
    }
}
</style>
