<template>
    <div class="text-center h-8 mb-2 bg-info">
        <b>Your products</b>
    </div>
    <ul>
        <li class="ml-2" v-for="product in products" :key="product.id">
            <router-link :to="{name:'productDetails', params:{ id: product.id}}">
                <img :src="'storage/'+product.file_path" alt="Product image" v-if="product.type ==='image'">
                <img :src="product.watermark_path" alt="Product image" v-else-if="product.type === 'book'">
                <b>{{product.title}}</b><br>
                <i class="text-success">{{product.price}}$</i><br>
                <span >Sales:</span>
            </router-link>
            <div class="mb-1 ml-1">
                <a class="btn btn-danger" @click="deleteProduct(product.id)">Delete</a>
                <router-link class="btn btn-success ml-1" :to="{name:'editProduct', params:{id:product.id}}">Edit</router-link>
            </div>

        </li>
    </ul>
</template>

<script>
import axois from '@/axios';
import {mapGetters} from 'vuex';
export default {
    data(){
        return {
            products:[]
        }
    },
    computed:{
        ...mapGetters(['authUser'])
    },

    watch:{
        authUser: {
            immediate: true, // استدعاء المعالج فوراً عند التحميل
            handler(newAuthUser) {
                if (newAuthUser && newAuthUser.id) {
                    this.fetchData();
                }
            },
        },
    },
    async mounted(){
        // إذا كانت authUser جاهزة، قم بجلب البيانات مباشرةً
        if (this.authUser && this.authUser.id) {
            await this.fetchData();
        }
    },
    methods:{
        deleteProduct(id){
            try{
                axois.delete(`/produc/${id}`);
                this.products = this.products.filter(product=> product.id !== id);
            }
            catch(erroe){
                console.error("an erroe while delete the prodcut in my products component: ", erroe);
            }
        },
        async fetchData(){
            const response = await axois.get(`/myProducts/${this.authUser.id}`);
            console.log(response.data);
            this.products = response.data;
        }
    }
}
</script>

<style scoped>
li{
    width: 200px;
    float: left;
    border: 3px solid;
    list-style-type: none;
}
li img{
    height: 200px;
    object-fit: cover;
    width: 100%;
}
h2{
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
