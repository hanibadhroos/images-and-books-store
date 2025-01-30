<template>
 <ul class="m-2">
        <li v-for="image in images" :key="image.id" class="ml-2 mb-2">
            <router-link :to="{ name:'productDetails', params:{ id: image.id }}">
                <i class="position-absolute  btn btn-success">{{image.price}} $</i>
                <img :src="'storage/'+ image.file_path" alt="Image product" class="w-100">
                <b>{{image.title}}</b>
                <hr>
                <div class="col-12">
                    <router-link :to="{ name:'productDetails', params:{ id: image.id }}" class="text-right">Read More</router-link>
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
            images:[]
        }
    },

    async mounted(){
        const response = await axios.get('/images');
        this.images = response.data;
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
        height: 300px;
        overflow: hidden;
        width:30%;
    }
    li div{
        width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    b{
        overflow: hidden;
        text-overflow: ellipsis;
        color:black;
    }

    @media (max-width: 292px) {
        li{
            width: 200px;
        }

    }

    @media (min-width: 800px){
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
