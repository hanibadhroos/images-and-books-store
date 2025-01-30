<template>
    <div>
        <div class="row">
            <section class="col-md-9">
                <div class="row">
                    <div class="col-md-4 bg-primary m-2 text-center">
                        My Stock <br><br>
                        <i class="text-dark font-weight-bold ">{{totalStock}} $</i>
                    </div>
                    <div class="col-md-4 bg-info m-2 text-center">
                        My Products<br><br>
                        <b>{{productsNumber}}</b>
                    </div>
                    <div class="col-md-3 bg-success m-2 text-center">
                        My Flowers <br><br>
                        <b>1200</b>
                    </div>
                 </div>

            </section>

            <!-- Side bar  -->
            <aside class="col-md-3 bg-dark text-white text-center">
                <h1>{{authUser.name}}</h1>
                <address>{{authUser.email}}</address>
                <hr class="text-white">
                <br><br><br><br><br>
                <router-link class="btn btn-success" to="/myProducts">My Products</router-link>
                <router-link to="/add" class="btn btn-primary ml-2 mt-2">Add New Product</router-link>
            </aside>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import axios from '@/axios';
export default {

    data(){
        return{
            productsNumber:null,
            sellerStocks:[],
            totalStock:0
        }
    },

    name:'UserProfile',
    computed:{
        ...mapGetters(['authUser']),
    },

    async created(){
        const response = await axios.get(`/myProducts/${this.authUser.id}`);
        this.productsNumber = response.data.length;
        // this.getStock(this.authUser.id);
        const stocks = await axios.get(`/userStock/${this.authUser.id}`);
        this.sellerStock = stocks.data;
      // حساب المجموع الكلي لرصيد البائع
        const totalSellerAmount = this.sellerStock.reduce((total, item) => {
            return total + parseFloat(item.sellerAmount); // تحويل النص إلى رقم وجمعه
        }, 0);

        this.totalStock = totalSellerAmount;


    },

}
</script>

<style scoped>
aside{
    min-height: 525px;
    height: auto;
}
section{
    background-color: #554c4c;
}
</style>
