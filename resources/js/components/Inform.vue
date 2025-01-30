<template>
    <div class="text-center div">
        <h2>Report about the product</h2>
        <hr>
        <img :src="product.watermark_path" alt="product image" width="175" height="175"><br>
        <b>{{product.title}}</b>
        <select class="form-control mt-2" name="inform" v-model="inform">
            <option value="The product is fake or not original">The product is fake or not original</option>
            <option value="Offensive or inappropriate content">Offensive or inappropriate content</option>
            <option value="Unfair pricing">Unfair pricing</option>
            <option value="Invalid or broken links">Invalid or broken links</option>
            <option value="The product does not match the description">The product does not match the description</option>
            <option value="Copyright infringement (e.g., intellectual property rights)">Copyright infringement (e.g., intellectual property rights)</option>
            <option value="Issues with the quality of the image or book">Issues with the quality of the image or book</option>
            <option value="Duplication or copying from another product">Duplication or copying from another product</option>
            <option value="Technical issues with downloading the product">Technical issues with downloading the product</option>
            <option value="The product contains prohibited content">The product contains prohibited content</option>
        </select>
        <button class="btn btn-primary mt-2" @click="addInform()">Send</button>
    </div>
</template>

<script>
import axios  from '@/axios';
import {mapGetters} from 'vuex';
export default{
    data(){
        return{
            product:{},
            inform:'',
        }
    },
    computed:{
        ...mapGetters(['authUser'])
    },
    methods:{
        async getProduct(){
            const product_id = parseInt(this.$route.params.id);
            const response = await axios.get(`/product/${product_id}`);
            this.product = response.data;
        },
        async addInform(){
            const response = await axios.post(`/inform/${this.product.id}`,{
                inform:this.inform,
                user_id:this.authUser.id
            });
            console.log(response.data);
            this.$router.push('/');
        }
    },
    created(){
        this.getProduct();
    }
}
</script>

<style scoped>
.div{
    width: 75%;
    margin: auto;
    margin-top: 4px;
    padding: 7px;
    background-color: #AAA;
}
select {
    height: 50px; /* ارتفاع محدد */
    overflow-y: auto; /* تمكين التمرير العمودي */
}

@media (max-width: 254px) and (min-width: 100px){
    img{
        width: 120px;
        height: 120px;
    }
}
</style>
