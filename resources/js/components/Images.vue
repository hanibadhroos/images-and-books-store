<template>
 <ul class="m-2">
        <li v-for="image in images" :key="image.id" class="ml-2 mb-2">
            <i class="position-absolute  btn btn-success">{{image.price}} $</i>
            <img :src="'storage/'+ image.file_path" alt="Image product" class="w-100">
            <b>{{image.title}}</b>
            <hr>
            <div>
                {{ image.description }}
            </div>
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
