<template>
    <nav>
        <div class="row">
            <div class="col-md-8 col-sm-8">
            <router-link to="/">
                <i style="float:left" class="text-white"><b>PicBook</b> </i>
            </router-link>
                <div class="text-right">
                    <router-link to="/images" class="m-2 text-white">Images</router-link>
                    <router-link to="/books" class="m-2 text-white">Books</router-link>
                    <router-link v-if="authUser  && authUser.role === 'admin'" to="/categories" class="m-2 text-white">Categories</router-link>
                </div>

            </div>
            <div class="col-md-4 text-right col-sm-4">
                <router-link to="/login" v-if="!isLoggedIn" class="btn btn-primary mr-2">Login</router-link>
                <router-link to="/register" v-if="!isLoggedIn" class="btn btn-primary">Register</router-link>
                <!-- Show the account for seeler  -->
                <router-link to="/profile" v-if="isLoggedIn && authUser && authUser.role === 'seeler'" class="mr-2 text-white">
                    My account
                </router-link>

                <!-- Show the Cart for Buyer -->
                <router-link to="/myCart" v-if="isLoggedIn && authUser &&authUser.role === 'user' && created" class="mr-2 text-white">
                    <i class="fa-solid fa-cart-shopping"></i> My Cart
                </router-link>
                <a href="#" @click="logout" v-if="isLoggedIn" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>
</template>


<script>
import {mapState, mapActions, mapGetters} from 'vuex';
import axios from '@/axios';
export default {

    data(){
        return{
            created:null,
        }
    },

    computed:{
        ...mapState(['isLoggedIn']),
        ...mapGetters(['authUser'])
    },
    methods:{
        ...mapActions(['logout']),

        //// Check if the user id Buyer and he has a cart =====> True or False
        async isCreated(){
            if(this.authUser.role === 'user' ){
                    const response = await axios.get(`/cart/isCreated/${this.authUser.id}`);
                    this.created=response.data;
             }
        }
    },
    updated(){
        this.isCreated()
    }
}
</script>

<style scoped>
    nav{
        color: white;
        background-color: #0a0a0a;
        height: 60px;
        padding: 10px 15px ;

    }

.router-link-active{
    color: green;
}
</style>
