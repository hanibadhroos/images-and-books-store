<template>
    <h3 class="text-center">Edit Category</h3>
    <hr>
    <form class="w-75 m-auto" @submit.prevent="editCategory()">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" v-model="category.name">
        <label for="description">Descripton:</label>
        <textarea name="description" id="description" cols="30" rows="1" class="form-control" v-model="category.description"></textarea>
        <button class="btn btn-success mt-1">Edit</button>
    </form>
</template>

<script>
import axios from "@/axios";
export default {
    data(){
        return{
            category:{}
        }
    },
    methods:{
        async getCategory(){
            const category_id = parseInt(this.$route.params.id);
            const response = await axios.get(`/category/${category_id}`);
            this.category = response.data;
        },
        async editCategory(){
            try{
                const category_id = parseInt(this.$route.params.id);
                const response = await axios.put(`/category/${category_id}`,{
                    name:this.category.name,
                    description:this.category.description
                });
            }
            catch(error){
                console.error("Error accurred while edit the category ", error);
            }
        }
    },
    created(){
        this.getCategory();
    }
}
</script>
