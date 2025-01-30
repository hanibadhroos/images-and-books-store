<template>
     <ul class="mt-2">
            <li v-for="book in books" :key="book.id" class="ml-2 mb-2">
               <router-link :to="{ name:'productDetails', params:{ id: book.id }}">

                <i class="position-absolute  btn btn-success">{{book.price}} $</i>
                <img :src="book.watermark_path" alt="Image product" style="width:100%; height:200px; object-fit:fill">
                <b>{{book.title}}</b>
                <hr>
                <div class="col-12">
                    <router-link :to="{ name:'productDetails', params:{ id: book.id }}" class="text-right">Read More</router-link>
                </div>

               </router-link>
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

    ul{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        align-content: center;
        justify-content: center;
        padding: 0px;
    }
    img{
        width: 200px;
        height: 200px;
    }
    li{
        float: left;
        border: 3px solid;
        height: 290px;
        overflow: hidden;
    }
    li .description{
        width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    b{
        overflow: hidden;
        text-overflow: ellipsis;
    }
    b, div{
        color: black;
    }
    div{
        float: left;
    }


    @media  (min-width: 100px)and (max-width: 292px) {
        li{
            width: 200px;
        }

    }
    @media (min-width: 501px){
        li{
            width: 30%;
        }
    }

    @media (min-width: 292px)and (max-width: 500px){
        li{
            width: 200px;

        }
    }

</style>
