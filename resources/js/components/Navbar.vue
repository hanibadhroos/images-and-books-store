<template>
    <nav>
        <div class="nav-container">
          <div class="logo">
            <router-link to="/" class="navbar-brand">
              <i class="text-white"><b>PicBook</b></i>
            </router-link>
          </div>

          <button class="menu-btn" @click="isMenuOpen = !isMenuOpen">
            ☰
          </button>
          <div class="nav-links" :class="{ 'show': isMenuOpen }">
            <router-link to="/images" class="m-2 text-white">Images</router-link>
            <router-link to="/books" class="m-2 text-white">Books</router-link>
            <div  v-if="authUser && authUser.role === 'admin'">
                <router-link to="/categories" class="m-2 text-white" id="adminLinks">Categories</router-link>
                <router-link to="/informed-product" class="m-2 text-white" id="adminLinks">Informs</router-link>

            </div>

            <router-link to="/login" v-if="!isLoggedIn" class="btn btn-primary mr-2">Login</router-link>
            <router-link to="/register" v-if="!isLoggedIn" class="btn btn-primary">Register</router-link>

            <router-link :to="{name:'profile', params:{id: authUser.id}}" v-if="isLoggedIn && authUser && authUser.role === 'seeler'" class="mr-2 text-white">My account</router-link>
            <router-link to="/myCart" v-if="isLoggedIn && authUser && authUser.role === 'user' && created" class="mr-2 text-white">
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
            isMenuOpen: false,
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
            if(this.authUser && this.authUser.role === 'user'){
                    const response = await axios.get(`/cart/isCreated/${this.authUser.id}`);
                    this.created=response.data;
             }
        }
    },
    created(){
        this.isCreated()
    },
    watch:{
        authUser:{
            immediate:true,
            handler(newAuthUser){
                if(newAuthUser && newAuthUser.id){
                    this.isCreated();
                }
            }
        }
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
#adminLinks{
    line-height: 40px;
}

.router-link-exact-active{
    color: rgb(128, 47, 0);
}


.logo{
    width:20%;
    float: left;
}

/* ترتيب العناصر باستخدام flexbox */
.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 60px;
    position: relative;
    padding: 0 15px;
  }

.menu-btn {
    display: none;
    font-size: 24px;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
  }

  .nav-links {
    display: flex;
    gap: 30px;
    position: absolute;
    right: 20px;
  }

  @media (max-width: 768px) {
    .menu-btn {
      display: block;
    }

    .nav-links {
      display: none;
      flex-direction: column;
      position: absolute;
      background-color: #0a0a0a;
      width: 100%;
      left: 0;
      top: 60px;
      padding: 10px;
    }

    .nav-links.show {
      display: flex;
      z-index: 9999;
    }
  }
</style>
