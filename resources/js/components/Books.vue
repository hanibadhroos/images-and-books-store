<template>
     <ul class="m-2">
        <li v-for="book in books" :key="book.id" class="ml-2 mb-2">
            <i class="position-absolute  btn btn-success">{{book.price}} $</i>
            <img :src="'storage/'+ book.file_path" alt="Image product">
            <b>{{book.title}}</b>
            <hr>
            <div>
                {{ book.description }}
            </div>
        </li>
    </ul>
</template>

<script>
import axios from '@/axios';
export default {
    data(){
        return{
            books:[]
        }
    },
    //// Get the Books by API
    async mounted(){
        const response = await axios.get('/books');
        this.books = response.data;
    }
}
</script>


<style scoped>

    img{
        width: 200px;
        height: 200px;
    }
    li{
        float: left;
        border: 3px solid;
        height: 300px;
        overflow: hidden;
    }
    li div{
        width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    b{
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
