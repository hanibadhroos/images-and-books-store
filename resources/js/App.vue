<template>
    <Navbar/>
        <div class="text-center  bg-dark p-2"> <i class="m-auto font-weight-bold text-white">Wellcom to our books and images store</i></div>
    <div id="app">
        <router-view/>
    </div>
</template>
<script>
import Navbar from './components/Navbar.vue';
import {mapActions} from 'vuex';
import axios from '@/axios';
export default {

    data(){
        return {
            user:null,
        }
    },
    components:{
        Navbar
    },
    name: 'App',

    methods:{
        ...mapActions(['fetchAuthUser']),
    },

    mounted(){
        axios.get('/getUser',{
            headers:{
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        }).then(response => {
            this.fetchAuthUser(response.data);
        }).catch(error=>{
            console.error('Error fetching user ', error);
        })

    }

};
</script>

<style>
li{
    list-style-type: none;;
}
a{
    text-decoration: none;
}
</style>




<!-- <template>
    <div>

    </div>
</template>

<script>
export default {
    name: 'App',
};
</script> -->
