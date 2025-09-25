<template>
    <div v-if="authUser.email === 'picbookadmin@gmail.com'">
        <a class="btn btn-primary m-2" @click="showAddCategory=true" v-if="!showAddCategory">Add new category</a>
        <!-- Add category component  -->
         <transition >
            <add-category v-if="showAddCategory" @hide-form="hideForm" @refresh-categories="fetchData"/>
         </transition>
        <hr>
        <h3 class="text-center">All Categories</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <td>Category name:</td>
                    <td>Description:</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in allCategories" :key="category.id">
                    <td>{{category.name}}</td>
                    <td>{{category.description}}</td>
                    <td>
                        <router-link :to="{name:'editCategory',params:{id:category.id}}" class="btn btn-success mr-2">Edit</router-link>
                        <a class="btn btn-danger" @click="deleteCategory(category.id)"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-else>
        <h2 class="text-center alert alert-danger">Forbidden</h2>
    </div>
</template>

<script>
import axios from "@/axios";
import { mapGetters } from "vuex";
import AddCategory from './AddCategory.vue';
export default {
    data(){
        return {
            allCategories:[],
            showAddCategory:false,
            counter:0
        }
    },
    components:{
        'add-category':AddCategory
    },
    methods:{
        async fetchData(){
            const response = await axios.get('/category');
            this.allCategories = response.data;
        },
        hideForm(){
            this.showAddCategory = false;
        },
        async deleteCategory(category_id){
            try{
                const response = await axios.delete(`/category/${category_id}`);
                this.allCategories =  this.allCategories.filter(category=> category.id !== category_id);
            }
            catch(error){
                console.error("Error accurred while delete a category ", error);
            }
        }
    },
    computed:{
        ...mapGetters(['authUser'])
    },
    created(){
        this.fetchData();
    },
}
</script>
