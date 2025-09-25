<template>
    <form @submit.prevent="submitData" class="mr-auto ml-auto mt-4 bg-white">
        <label for="product">Uploade the product:</label>
        <input type="file" class="form-control" id="product" name="product" @change="uploadProduct">
        <p v-if="errors.product" class="text-danger">{{errors.product}}</p>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" class="form-control" v-model="title" >
        <p v-if="errors.title" class="text-danger">{{errors.title}}</p>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" class="form-control" v-model="description" >
        <!-- <p v-if="errors.description" class="text-danger">{{errors.description}}</p> -->

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" class="form-control" v-model="price" >
        <p v-if="errors.price" class="text-danger">{{errors.price}}</p>

        <label for="type">Product Type:</label>
        <select name="type" id="type" class="form-control" v-model="type">
            <option value="book">Book</option>
            <option value="image">Image</option>
        </select>
        <p v-if="errors.type" class="text-danger">{{errors.type}}</p>

        <div v-if="type === 'book'">
            <label for="cover">Select Book Cover</label>
            <input type="file" name="cover" id="cover" class="form-control" @change="uploadCover">
            <p v-if="errors.cover" class="text-danger">{{errors.cover}}</p>
        </div>


         <label for="category">Category:</label>
        <select name="category" id="category" class="form-control" v-model="category_id" size="5">
            <option v-for="category in Allcategories" :key="category.id" v-bind:value="category.id">
                {{category.name}}
                <i>{{category.description}}</i>
            </option>
        </select>
        <p v-if="errors.category" class="text-danger">{{errors.category}}</p>

        <button type="submit" class="btn btn-success mt-2">Add</button>

    </form>
</template>

<script>
import axios from '@/axios';
import {mapGetters} from 'vuex';
export default {
    data(){
        return {
            product:'',
            title:'',
            description:null,
            price:'',
            type:'',
            cover:'',
            category_id:'',
            errors:{},
            Allcategories:[],
        }
    },
    computed:{
        ...mapGetters(['authUser'])
    },
    async created(){
            //For Getting the categories by API.
            const CategoryResponse = await axios.get('http://127.0.0.1:8000/api/category');
            this.Allcategories = CategoryResponse.data;
    },
    methods:{
        validateInput(){
            const errors = {};
            if(!this.title)errors.title = "Title is required";
            // if(!this.description)errors.description = "Description is required";
            if(!this.price || isNaN(this.price))errors.price = "Price is required and must be number";
            if(!this.type)errors.type = "Type is required";
            if(!this.category_id)errors.category_id = "Category is required";
            if(!this.product)errors.product = "Product is required";
            if(!this.cover && this.type==='book')errors.cover = "Book Cover is required";

            return errors;
        },
        uploadProduct(e){
            this.product = e.target.files[0];
        },
        uploadCover(e){
            this.cover = e.target.files[0]
        },
        async submitData()
        {
            //If there are Errors
            const errors = this.validateInput();
            if(Object.keys(errors).length > 0){
            this.errors = errors;
            return;
        }

        try{
          const response = await axios.post('/product',{
                file_path:this.product,
                title:this.title,
                description:this.description,
                price:this.price,
                type:this.type,
                category_id:this.category_id,
                user_id: this.authUser.id,
                cover: this.cover

            },{
                headers:{
                    "Content-Type":"multipart/form-data",
                }
            }
            );
            alert("Product Added successfully");
            this.$router.push('/');
        }
        catch(error){
            console.error("An error accurred while Adding new product: " , error.response.data);
        }
    }
}
}
</script>


<style scoped>

form{
    width: 50%;
    padding: 40px;
}
@media (min-width:200px)and (max-width:1300px){

    form{
        width: 100%;
    }

}
</style>
