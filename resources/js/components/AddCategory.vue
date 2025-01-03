<template>
    <h2>Add new category</h2>
    <hr>
    <button class="btn btn-info m-1" @click="hideForm()">Close <i class="fa-solid fa-close text-danger"></i></button>
<div>
    <form @submit.prevent="addCategory" class="w-75 m-auto">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" v-model="name" class="form-control">
        <label for="description">Description:</label>
        <textarea name="description" id="description" cols="30" rows="1" v-model="description" class="form-control"></textarea>
        <button type="submit" class="btn btn-success m-1">Add</button>
    </form>
</div>

</template>

<script>
import axios from '@/axios';
export default {
    data(){
        return{
            name:'',
            description:''
        }
    },
    methods:{
        async addCategory(){
            try{
                const response  = await axios.post('/category',{
                    name:this.name,
                    description:this.description
                });
                this.$emit('refresh-categories');

            }
            catch(error){
                console.error("an error accurred while add new category ", error);
            }
        },
        hideForm(){
            this.$emit('hide-form');
        }
    }

}
</script>

<style scoped>
form{
    border: 1px solid;
    border-radius: 7px;
    padding: 10px;
}
div{
    background-color: #aaa;
    padding: 10px 0px
}
</style>
