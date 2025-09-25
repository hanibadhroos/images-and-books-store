<template>
    <div class="form-container">
        <form @submit.prevent="register" class="register-form w-50 mr-auto ml-auto mt-4">
            <input type="text" required v-model="name" placeholder="Your name:" class="form-control">
            <input type="email" required v-model="email"  placeholder="Your Email:" class="form-control">
            <input type="password" required v-model="password" placeholder="Password:" class="form-control">
            <select name="role" class="form-control" v-model="role">
                <option disabled>Select register type:</option>
                <option value="user">buyer</option>
                <option value="seeler">Seeler</option>
            </select>
            <button type="submit" class="btn btn-primary mt-2" v-if="!isLoading">Register</button>
            <button  class="btn btn-primary mt-2" v-else><div class="spinner"></div></button>
            <router-link to="/Login" class="mr-2"> I have already account? <b>Login</b></router-link>
        </form>
    </div>
</template>


<script>
import axios from '@/axios';
export default {
    data(){
        return {
            name:'',
            email:'',
            password:'',
            role:'',
            isLoading:false
        }

    },
    methods:{
        async register(){
            try{
                this.isLoading = true;
                const response = await axios.post('/register',{
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    role:this.role
                });

                console.log(response.data);

                // // إضافة التوكين في رأس الطلب
                // const token = response.data.token;
                // localStorage.setItem('token',token);
                // axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;


                if(response.data.token){
                    localStorage.setItem('token', response.data.token);
                    this.$store.commit('LOGIN',response.data.user);
                    // this.$router.push('/');
                }
                else{
                    this.loginError = 'Email or password not found';
                }
                //// Run Email Verification Route
                const emailVeri = await axios.post('/email/verification-notification');
                console.log(emailVeri.data);
                this.isLoading = false;
                this.$router.push('/login');
            }
            catch(error)
            {
                // console.error("An error accurred: ",error);
                if(error.response){
                    console.error("An error accurred: ",error.response);
                }
            }
        }
    },

}
</script>

<style scoped>

.spinner{
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0%{
        transform: rotate(0deg);
    }
    100%{
        transform: rotate(360deg);
    }
}
</style>
