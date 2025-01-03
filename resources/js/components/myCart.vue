<template>
    <div>
        <div class="loading-spinner" v-if="loading">
            <div class="spinner"></div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Title</th>
                    <th>Product Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in cartItems" :key="item.id">
                    <td>{{item.product.title}}</td>
                    <td>{{item.product.price}} $</td>
                    <td>
                        <!-- If editMode equal to true ==> Item Quantity -->
                        <span v-if="!item.editMode">{{item.quantity}}</span>

                        <!-- If editMode equal to false ====> show input fild -->
                        <input type="number" min="1" class="w-25" v-else v-model="item.quantity">

                        <!-- Edit Button -->
                        <button class="btn btn-success ml-3" @click="editItem(item)">{{item.editMode?'Save':'Edit'}}</button>
                    </td>
                    <td>
                        {{item.product.price * item.quantity}} $
                    </td>
                    <td>
                        <!-- Delete Button -->
                        <button class="btn btn-danger mr-1 ml-1" @click="deleteItem(item.id)"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-dark text-white">
                    <th> Total Price </th>
                    <th colspan="4">{{grandTotal}} $</th>
                </tr>
            </tfoot>
        </table>

        <!-- <ul>
            <li class="m-2 p-2" v-for="item in cartItems" :key="item.id">
                <h3>{{item.product.title}}</h3>
                <p>Price: {{item.product.price}}</p>
                <p>Quantity:

                 <span v-if="!item.editMode">{{item.quantity}}</span>

                 <input type="number" min="1" v-else v-model="item.quantity">
                 </p>
                <p>Total: {{item.product.price * item.quantity}}</p>

                <button class="btn btn-success" @click="editItem(item)">{{item.editMode?'Save':'Edit'}}</button>
                <button class="btn btn-danger mr-1 ml-1" @click="deleteItem(item.id)"><i class="fa-solid fa-trash"></i></button>

            </li>
        </ul> -->

        <div class="buy-div">
            <!-- Buy Button -->
            <button class="btn btn-info" @click="createOrder(grandTotal)">
                <span v-if="!buyLoading">Buy</span>
                <span v-else>
                        <div class="spinner"></div>
                </span>
            </button>
            <b class="ml-2">{{ grandTotal }} <span class="fa-solid fa-dollar"></span></b>
        </div>


    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import axios from '@/axios';
export default {

    data(){
        return {
            cartItems:[],
            loading: true, // حالة التحميل
            buyLoading:false
        }
    },

    computed:{
        grandTotal(){
            return this.cartItems.reduce(
                (total, item)=>total+ item.product.price * item.quantity,
                0
            );
        },
        ...mapGetters(['authUser']),
    },
    methods:{
        ///// Method for get all items
       async fetchCartItems(){
            try{
                this.loading = true; // بدء حالة التحميل
                const usercart = await axios.get(`/userCart/${this.authUser.id}`);
                const cart_id = usercart.data.id;
                axios.get(`/userItems/${cart_id}`).then((response)=>
                    {
                        this.cartItems= response.data.map((item)=>
                        ({
                            ...item,
                            editMode:false,
                        }));
                    }
                );
            }
            catch(error){
                console.error('an error accurred while fetching items: ',error);
            }
            finally{
                this.loading = false;
            }
        },

        editItem(item){
            ////// if editMode equal to True, Update the item.
            if(item.editMode){
                axios.put(`/cartItems/${item.id}`,{
                    quantity:item.quantity
                }).then(()=>{
                    item.editMode= false;
                });
            }
            else{
                item.editMode = true;
            }
        },

        ///// For delete the item
        deleteItem(id){
            axios.delete(`/cartItems/${id}`);
            this.cartItems = this.cartItems.filter(item => item.id !== id);
        },

        ///// For add new order
        async createOrder(total_price){

            try{
                const response = await axios.post('/orders',{
                    user_id:this.authUser.id,
                    total_price:total_price,
                    status:'hold', ///// hold, completed, canceled
                    cartItems:this.cartItems,

                });
                // const order_id = response.data.order_id;
                // if(order_id){
                //      this.cartItems.forEach(item => {
                //       const response = axios.post('/orderDetails',{
                //             order_id:order_id,
                //             product_id: item.product.id,
                //             quantity: item.quantity,
                //             price: item.product.price,
                //         })
                //         console.log(response.data);
                //     });


                    ///// Here we need to write function for to do Payment logic.
                    // return this.$router.push({name:'pay',params:{order_id:order_id}});



                // }

            }
            catch(error){
                console.error('an error accurred while create new order', error);
            }
            try{
                    this.buyLoading=true;

                    const payResponse = await axios.get('paypal/payment');
                    if (payResponse.data.links) {
                        const approveLink = payResponse.data.links.find(link => link.rel === 'approve');
                        if (approveLink) {
                            window.location.href = approveLink.href; // الانتقال إلى صفحة PayPal للموافقة
                        }
                    }


            }
            catch(error){
                console.error('An Error accurred while pay ', error);
            }
        },




    },
    async created(){
        // إذا كانت authUser جاهزة، قم بجلب البيانات مباشرةً
        if (this.authUser && this.authUser.id) {
            await this.fetchCartItems();
        }

    },
    watch:{
        authUser: {
            immediate: true, // استدعاء المعالج فوراً عند التحميل
            handler(newAuthUser) {
                if (newAuthUser && newAuthUser.id) {
                    this.fetchCartItems();
                }
            },
        },
    },

}
</script>

<style scoped>

/* تصميم دائرة التحميل */
.loading-spinner {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
}

.spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
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


ul{
    margin-left: -40px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    background-color: #afa3a3;
}
li{
    width: 270px;
    background-color: #DDD;
}

.buy-div{
    background-color: #9ccba5;
    text-align: center;
    padding: 10px;
    margin-top: -16px;
}
</style>
