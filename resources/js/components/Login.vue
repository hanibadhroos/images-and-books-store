<template>
    <div class="form-container">
        <form @submit.prevent="login" class="login-from w-50 mr-auto ml-auto mt-4">
            <input
                type="email"
                placeholder="Enter your Email"
                v-model="email"
                required
                class="form-control"
            />
            <input
                type="password"
                placeholder="Enter Password"
                v-model="password"
                required
                class="form-control"
            />
            <div v-if="loginError" class="alert alert-danger mt-1">
                {{ loginError }}
            </div>

            <button type="submit" class="btn btn-primary mt-2">Login</button>
        </form>
    </div>
</template>

<script>

import axios from '@/axios';
import {mapActions} from 'vuex';
export default {
    data(){
        return{
            email:'',
            password:'',
            loginError:null
        }
    },

    methods:{
        async login(){
            try{
                const response =await axios.post("/login",{
                    email: this.email,
                    password: this.password
                });
                if(response.data.token){
                    localStorage.setItem('token', response.data.token);
                    this.$store.commit('LOGIN',response.data.user);
                    this.$router.push('/');
                }
                else{
                    this.loginError = 'Email or password not found';
                }


            }
            catch(error)
            {
                console.error("An Error accurred",error);
            }
        }
    }
}
</script>
