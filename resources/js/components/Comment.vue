<template>
    <div class="parent-div">
        <div class="p-2" style="width:440px">
            <form @submit.prevent="sendComment">
                <textarea class="col-12 m-auto" cols="30" rows="2" placeholder="Write your comment" v-model="comment"></textarea> <br>
                <button type="submit" class="mt-1">Submit</button>

            </form>
        </div>

        <ul class="mt-4 bg-light p-2 text-center">
            <li class="bg-opacity-100 w-50 bg-white ml-auto mr-auto mb-2" v-for="review in allReview" :key="review.id">
                <i class="float-left ml-1">Hani</i><br>
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
                console.log(response.data);
            }
            catch(error)
            {
                console.error("an error accurred while add new comment===> ", error);
            }
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

</style>
