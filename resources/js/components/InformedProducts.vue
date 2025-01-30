<template>
    <h3 class="text-center">Informed product</h3>
    <hr>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product name</th>
                <th>product owner</th>
                <th>inform type</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in informedProducts" :key="product.id">
                <td>{{product.product.title}}</td>
                <td>{{product.product.user.name}}</td>
                <td>{{product.inform}}</td>
                <td>
                    <a href="" class="btn btn-danger mr-2" @click="deleteProduct(product.product_id)">Delete product</a>
                    <a href="" class="btn btn-danger" @click="deleteUser(product.product.user.id)">Delete user</a>
                </td>
            </tr>
        </tbody>
    </table>

</template>
<script>
import axios from '@/axios';
export default{
    data(){
        return{
            informedProducts:null
        }
    },
    methods:{
        async getInformedProducts(){
            const response = await axios.get('/informedProducts');
            this.informedProducts = response.data;
        },
        async deleteProduct(id){
            try{
                const response = await axios.delete(`/product/${id}`);
                console.log(response.data);
            }
            catch(error){
                console.error('Error while delete product ', error);
            }
        },
        async deleteUser(id){
            try{
                const response = await axios.delete(`/user/${id}`);
                console.log(response.data);
            }
            catch(error){
                console.error('Error while delete user ', error);
            }
        }
    },
    created(){
        this.getInformedProducts();
    }
}
</script>
