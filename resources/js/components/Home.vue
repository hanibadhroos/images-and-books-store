<template>
    <div v-if="isLoggedIn">
        <router-link class="btn btn-primary mt-2 ml-4 float-left" v-if="isLoggedIn &&authUser&& authUser.role==='seeler'" to="/add" >Add new product</router-link>
        <router-link class="btn btn-primary mt-2 ml-4 float-left" v-if="!isLoggedIn && authUser.role==='seeler'" to="/login" >Add new product</router-link>
    </div>

    <form class="mt-2 text-center" @submit.prevent="search">
        <input type="search" @input="search" v-model="query" class="searchInp p-2" placeholder="Search for product">
        <button type="submit" class="btn btn-success">Search</button>
    </form>


    <!-- The most seeling -->
            <hr>
            <h2 class="most-selling-header text-center">Most Selling</h2>
            <div class="most-selling  text-center m-1" style="overflow-y:hidden">
                <ul class="seeling-ul">
                    <li v-for="product in products" :key="product.id" class="m-1  border-1">

                    <!-- Link to show the details  -->
                    <router-link :to="{ name:'productDetails', params:{ id: product.id }}">
                        <!-- Product Type -->
                        <i class=" btn btn-success border-dark border-5" v-if="product.type === 'image'">{{ product.type }}</i>
                        <i class=" btn btn-primary border-5 border-dark" v-else-if="product.type === 'book'">{{ product.type }}</i>

                        <!-- If the product type is image -->
                        <img :src="product.watermark_path" alt="Product image" style="width:100%; height:200px; object-fit:fill">
                        <!--  If the  product is book  -->

                        <!-- <b>{{product.file_path}}</b> -->
                        <div class="row">

                            <b class="float-left col-md-8 text-center text-dark" style="white-space:nowrap;">{{product.title}}</b> <span class="col-md-4 text-success">{{ product.price }} $</span>
                        </div>
                    </router-link>
                    </li>
                </ul>
            </div>

    <div class="row" style="margin-right:auto">
       <!-- Categories -->
        <!-- Filtering -->
        <select name="category" class="form-control col-md-3" v-model="selectedFilter" @change="filtering">
            <option value="all">All</option>
            <option :value="category.name" v-for="category in categories" :key="category.id">{{category.name}}</option>
        </select>


        <!-- New products -->
       <div class="new-products col-md-8 m-2">
            <h2 class=" text-center">
                All Products
             </h2>

            <ul>
                <li v-for="product in productWithLikes" :key="product.id" class="bg-dark m-2 text-white text-center">
                <router-link :to="{ name:'productDetails', params:{ id: product.id }}">
                    <div class="row">
                        <div class="col-md-10">
                            <!-- If the product type is image -->
                            <img :src="product.watermark_path" alt="" style="width:100%; height:200px; object-fit:fill">
                        </div>
                        <div class=" col-md-2 ">
                            <i class="text-success bg-white">{{product.price}}$</i>
                        </div>
                    </div>

                        <hr class="bg-light mt-2 mb-2">
                    <b>{{product.title}}</b>
                    {{ product.likes_count }}
                </router-link>
                <div class="text-end row" >

                    <!-- Do for Loop into folowers -->
                    <div class="col-md-4 text-left" >
                        <!-- Show the product seeler -->
                        <span class="follow" v-if="authUser.id !== product.user_id">{{product.user.name}}</span>
                        <span class="follow btn-primary"  v-else-if="authUser.id === product.user_id">You</span>

                     </div>
                     <div class="col-md-8 text-right">
                            <!-- Inform about the product -->
                            <router-link :to="{name:'inform',params:{id:product.id}}" class="mr-2 bg-dark border-0">
                                <i class="fa-solid fa-flag text-danger fa-2x"></i>
                            </router-link>
                            <!-- Comment Button -->
                            <router-link :to="{name:'comment',params:{id:product.id}}" class="mr-2 bg-dark border-0">
                                <i class="fa-solid fa-message fa-2x"></i>
                            </router-link>
                            <!-- Like Button -->
                            <button style="background: none; border: none;" @click="toggleLike(product.id)">
                                <span class="bg-gray">
                                  <i :style="getLikeButtonStyle(product.id)" class="fa-solid fa-thumbs-up fa-2x"></i>
                                </span>
                                 {{ product.productLikes }}
                            </button>

                     </div>

                    <!--    operations Buttons   -->
                    <div class="col-md-8" v-if="authUser.id === product.user_id">
                        <router-link class="btn btn-info mr-2" :to="{name:'editProduct', params:{id:product.id}}">Edit</router-link>
                        <a @click="deleteProduct(product.id)" class="btn btn-danger">Delete</a>
                    </div>

                </div>
                </li>
            </ul>
       </div>
    </div>
</template>

<script>
import axios from '@/axios';
import { all } from 'axios';
import {mapState} from 'vuex';
import {mapGetters} from 'vuex';
export default {
    data(){
        return {
            query:'',
            selectedFilter:null,
            products:[],
            categories:[],
            followers:[],
            addedToCart:[],
            reviews: [],  // قائمة المراجعات
            likedProducts: [], // قائمة المنتجات التي حصلت على إعجاب من المستخدم
            allLikes:[],
        }
    },

    computed:{
        ...mapState(['isLoggedIn']),
        ...mapState(['authUser']),
        ...mapGetters(['authUser']),
        productWithLikes(){
            return this.products.map(product=>{
                const productLikes = this.allLikes.reduce((count, like)=>{
                    return like.product_id === product.id ? count + 1 : count;
                },0);
                return {
                    ...product,
                    productLikes
                };
            });
        },
    },


     methods:{

        async fetchData(){
            const response = await axios.get('/product');
            this.products = response.data.products;
            this.allLikes = response.data.likes;

        },
        ////// Search method
        async search(){
            //// First we get all product form database.
            const response = await axios.get('/product');
            if(this.query === ''){
                this.products = response.data.products;
            }
            else{
                this.products = response.data.products.filter(product =>
                product.title.includes(this.query) ||
                product.description.includes(this.query)
                );

            }

            // تحديث بيانات الإعجابات أيضًا حتى يتم تحديث productWithLikes
            this.allLikes = response.data.likes;

        },
        async filtering(){
            //// First we get all product form database.
            const response = await axios.get('/product');
            this.products = response.data.products;
            if(this.selectedFilter !== 'all'){
                this.products = this.products.filter(product=>product.category.name === this.selectedFilter);
            }
            this.allLikes = response.data.likes;
        },
        /////Delete the product
        async deleteProduct(id){
            try{
                //// Delete the product API
                const response = await axios.delete(`/product/${id}`);
                ///// Filtering the products without the deleted product.
                this.products = this.products.filter(product => product.id === id);

            }
            catch(error){
                console.error("an error while Delete the product: ", error);
            }
        },
        async setLikedProducts() {
            // تحديد المنتجات التي حصلت على إعجاب من قبل المستخدم بناءً على المراجعات
            const userId = this.authUser.id; // get user_id of authUser

            //// get the reviews
            const reviewResponse = await axios.get('/review');
            this.reviews = reviewResponse.data;

            this.likedProducts = this.reviews
                .filter(review => review.user_id === userId && review.rating ===1)
                .map(review => review.product_id);
        },
        getLikeButtonStyle(productId) {
            // تحديد لون زر الإعجاب بناءً على ما إذا كان المنتج قد حصل على إعجاب من قبل المستخدم
            // return this.likedProducts.includes(productId) ? 'background-color: blue; color: white;' : 'background-color: #343a40; border:none; color: white;';
            // return this.likedProducts.includes(productId) ? 'fa-solid fa-thumbs-up fa-2' : 'background-color: #343a40; border:none; color: white;';
            return this.likedProducts.includes(productId) ? 'color: blue;' : 'color: white;';

        },
        async toggleLike(productId) {
            // تغيير حالة الإعجاب (زر الإعجاب) عند الضغط عليه
            if (this.likedProducts.includes(productId)) {
                // إزالة الإعجاب
                this.likedProducts = this.likedProducts.filter(id => id !== productId);
                ///// First we need to get review
                const review = this.reviews.find(review => review.user_id === this.authUser.id && review.product_id === productId && review.rating === 1);
                const response = await axios.delete(`/review/${review.id}`);

                this.allLikes = this.allLikes.filter(like => like.user_id !== this.authUser.id);

            } else {
                // إضافة الإعجاب
                const response = await axios.post('/review',{
                    user_id:this.authUser.id,
                    product_id:productId,
                    rating:true,
                });

                // إضافة الإعجاب إلى allLikes
                this.allLikes.push({
                    user_id: this.authUser.id,
                    product_id: productId,
                    rating: 1, // أو القيمة التي تستخدمها لتعريف الإعجاب
                });



            }
            this.setLikedProducts();
            this.getLikeButtonStyle();
        },

     },

    //// I have comment it because i was use it in App.vue Component
    // async mounted() {
    // // console.log(localStorage.getItem('token'));
    //     try{
    //         const response = await axios.get('/getUser',{
    //             headers:{
    //                 Authorization: `Bearer ${localStorage.getItem('token')}`
    //             }
    //         });
    //         this.authUser = response.data;
    //     }
    //     catch(error){
    //         console.error("An error accurred while get the user: ",error);
    //     }
    // },

    async created(){
        try{
            this.fetchData();
            // Get categories by API
            const CategoriesResponse =  await axios.get("/category");
            this.categories = CategoriesResponse.data;

            //// get the reviews
            const reviewResponse = await axios.get('/review');
            this.reviews = reviewResponse.data;
            // تحديد المنتجات التي حصلت على إعجاب من قبل المستخدم
            this.setLikedProducts();


            ///// Get the cart items for check if the item is added to Auth user Cart.
            ///// First we get the active cart_id of carts table for search later by it into cart_items table
            //// this API first get cart_id then get userItems.
            const userCart = await axios.get(`/userCart/${this.authUser.id}`);
            const cart_id = userCart.data.id;

            ///// Now if cart_id no null, get user items by this cart_id
            if(cart_id){
                const items = await axios.get(`userItems/${cart_id}`);
                this.addedToCart=items;
            }

        }
        catch(error){
            console.error("An error accurred while fatching the data: ", error);
        }




    }
}
</script>

<style scoped>
.new-products{
    background-color: #dad5d5;

}

.categories{
    background-color: antiquewhite;
}
ul{
    list-style-type: none;
    padding-left: 5px;
}
.seeling-ul{
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    width: max-content
}
li{
    position: relative; /* جعل العنصر li مرجعية لموضع العناصر المطلقة داخله */
    background-color: #CCC;
    padding: 5px;
}
.product-type {
    position: absolute; /* تثبيت العنصر في مكان محدد داخل العنصر li */
    top: 5px; /* المسافة من الأعلى */
    right: 5px; /* المسافة من اليمين */
    z-index: 10; /* التأكد من ظهور العنصر فوق المحتوى الآخر */
    background-color: #28a745;
    color: white;
    padding: 5px 10px;
    border-radius: 5px; /* إضافة زوايا دائرية */
}

li *{
   color: white;
}
.most-selling{
    background-color: #b5afaf;
    padding: 0px 2px;
    height: 248px;
    overflow: auto;
}

.most-selling i{
    position: absolute;
    top: 7px;
    right: 7px;
}
.most-selling b{
    overflow: hidden;
    text-overflow: ellipsis;
}
.most-selling-header{
    background-color: #b5afaf;
}
.description{
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-type{
    position: absolute;
    right: 30px;
}

a{
    text-decoration: none;
}

.follow{
    float: left;
}

button {
  padding: 5px 10px;
  border: 1px solid #ccc;
  cursor: pointer;
}
button:hover {
  opacity: 0.8;
}

@media  (min-width: 400px) {
    .most-selling ul li{
        width: 50%;
    }
    .searchInp{
        width: 25%;
        margin-top: 5px;
    }
}

@media (min-width: 800px){
    .most-selling ul li{
        width: 30%;
    }
}

@media (min-width: 200px)and (max-width: 500px){
    .most-selling ul li{
        width: 160px;
    }
    .searchInp{
        width:140px;
        margin: 5px auto;

    }
}
</style>
