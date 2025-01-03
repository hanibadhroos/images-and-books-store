<template>
    <div class="mr-auto ml-auto mt-2 border-success p-3">
        <img :src="product.watermark_path" alt="product Image">
        <div class="row w-100">
            <div class="col-md-10">
                <h2>{{product.title}}</h2>
            </div>
            <div class="col-md-2">
                <b class="text-success">{{ product.price }}$ </b>
            </div>
        </div>
        <hr>
        <div class="description">{{product.description}}</div>
        <hr>
            <form v-if="authUser.role === 'user'" @submit.prevent="addToCart">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" ref="quantity" v-model="quantity" placeholder="Enter Quantiry:" class="form-control">
                <button type="submit" class="btn btn-success m-2" >
                    <span v-if="!loading">Add to Cart</span>
                    <span v-else>
                        <div class="spinner"></div>
                    </span>
                </button>
            </form>
            <router-link :to="{name:'editProduct', params:{ id: product.id}}" class="btn btn-success m-2" v-if="authUser.role === 'saler' && product.user_id === authUser.id">Edit</router-link>

    </div>
</template>

<script>
import axios from '@/axios';
import {mapGetters} from 'vuex';
export default {
    data(){
        return{
            product:{},
            isCreated:null,
            quantity:1,
            loading:false
        }
    },
    computed:{
         ...mapGetters(['authUser']),

    },
    methods:{
         async addToCart(){
            try{
                this.loading=true;
                ////where the user doesn't has cart with status = ture.
                if(!this.isCreated){
                    //// First we add new cart in  carts table
                    //// Create new cart
                    const cartResponse = await axios.post('/cart',{
                        user_id:this.authUser.id,
                        status: false
                    });

                    ////////// Then we add new item to cart_items table
                    ///// First we get cart_id from carts Table for add new item in cart_items table
                    const userCart = await axios.get(`/userCart/${this.authUser.id}`);
                    const cart_id = userCart.data.id;

                    ///// create new item to cart_items table API.
                    const item= await axios.post('/cartItems',{
                        cart_id: cart_id,
                        product_id:this.product.id,
                        status:0,
                        quantity: this.quantity,
                    });


                    if(item.data && cartResponse.data){
                        alert('Product added to cart successfully.');
                        this.$router.push('/');
                    }
                }
                else{

                    try{
                        /// the user has a cart with status = true.
                        /// First we get cart_id from carts Table
                        const userCart = await axios.get(`/userCart/${this.authUser.id}`);
                        const cart_id = userCart.data.id;
                        //// Now we add the product to cart_items table
                        axios.post('/cartItems',{
                            cart_id: cart_id,
                            product_id:this.product.id,
                            quantity: this.quantity,
                        });
                        alert("Product added successfully.");
                        this.$router.push('/');
                    }
                    catch(error)
                    {
                        console.error("An Error accurred while add new item==>", error);
                    }
                    finally{
                        this.loading = false;
                    }

                }
            }
            catch(error){
                console.error("Error while create new cart or item", error);
            }


         }
    },
    async mounted(){
        const product_id = parseInt(this.$route.params.id);
        try{
            const response = await axios.get(`product/${product_id}`);
            this.product = response.data;
            // const cartResponse = await axios.get()
            ///// Check if the cart was created
            const cartCreated = await axios.get(`/cart/isCreated/${this.authUser.id}`)
            this.isCreated = cartCreated.data;
        }
        catch(error){
            console.error("An Error accurred while show the details: ", error);
        }

    }

}
</script>

<style scoped>
    div{
        width: 25%;
        background-color: #DDD;
    }
    div img{
        height: 150px;
        width: 100%;
    }

    div .description{
        overflow: auto;
        width: 100%;
    }
    .spinner{
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;

    }
    @keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
