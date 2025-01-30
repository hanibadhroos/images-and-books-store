<template>
    <div>
        <form @submit.prevent="submitData">
        <div class="text-center">
            <b>Edit Your Product</b><br>
        </div>

        <label for="product">Uploade the product:  <img class="w-20" width="100" height="100" :src="'storage/'+product.file_path" alt="Product image">   </label>
        <input type="file" class="form-control" id="product" name="product" @change="uploadProduct">
        <p v-if="errors.product" class="text-danger">{{errors.product}}</p>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" class="form-control" v-model="product.title" >
        <p v-if="errors.title" class="text-danger">{{errors.title}}</p>

        <label for="description">Description:</label>
        <textarea type="text" id="description" name="description" class="form-control" v-model="product.description" ></textarea>
        <p v-if="errors.description" class="text-danger">{{errors.description}}</p>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" class="form-control" v-model="product.price" >
        <p v-if="errors.price" class="text-danger">{{errors.price}}</p>

        <label for="type">Product Type:</label>
        <select name="type" id="type" class="form-control" v-model="product.type">
            <option value="book">Book</option>
            <option value="image">Image</option>
        </select>
        <p v-if="errors.type" class="text-danger">{{errors.type}}</p>

         <label for="category">Category:</label>
        <select name="category" id="category" class="form-control"  v-model="product.category_id">
            <option v-for="category in Allcategories" :key="category.id" v-bind:value="category.id">{{category.name}}</option>
        </select>
        <p v-if="errors.category" class="text-danger">{{errors.category}}</p>

        <button type="submit" class="btn btn-success mt-2">Edit</button>
        </form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            errors:{},
            Allcategories:{},
            prodcut_id:'',
            product:{},

        }
    },

    async created(){
            //For Getting the categories by API.
            const CategoryResponse = await axios.get('http://127.0.0.1:8000/api/category');
            this.Allcategories = CategoryResponse.data;

               //// Get the product_id of the router params
            this.prodcut_id = this.$route.params.id;
            ////// Get the product by product_id
            const productResponse = await axios.get(`/api/product/${this.prodcut_id}`);
            this.product = productResponse.data;

    },

     methods:{
        validateInput(){
            const errors = {};
            if(!this.product.title)errors.title = "Title is required";
            if(!this.product.description)errors.description = "Description is required";
            if(!this.product.price || isNaN(this.product.price))errors.price = "Price is required and must be number";
            if(!this.product.type)errors.type = "Type is required";
            if(!this.product.category_id)errors.category_id = "Category is required";
            if(!this.product.file_path)errors.product = "Product is required";
            return errors;
        },
        uploadProduct(e){
            if(e.target.files[0]){
                this.product = e.target.files[0];
            }
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
            //// Update product API
          const response = await axios.put(`api/product/${this.prodcut_id}`,this.product,{
                headers:{
                    "Content-Type":"multipart/form-data",
                }
            }
            );
            console.log(response.data);
            alert("Product Updated successfully");
            // console.log(response.data);
            this.$router.push('/');
        }
        catch(error){
            console.error("An error accurred while Adding new product: " , error.response);
        }
    }
}
}
</script>


<style scoped>
form{
    width: 75%;
    margin: auto;
    border: 2px solid;
    padding: 10px;
    box-shadow: 10px 17px 12px;
    margin-top: 20px;
    margin-bottom: 20px;
}
</style>
