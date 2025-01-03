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
            <button type="submit" class="btn btn-primary mt-2">Register</button>
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
            role:''
        }

    },
    methods:{
        async register(){
            try{
                const response = await axios.post('/register',{
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    role:this.role
                });

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
