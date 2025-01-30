<template>
    <div class="text-center">
        <h2>payment successfully</h2>
        <p> You can download the following products:</p>
        <div class="row">
            <div class="col-md-4 p-4 bg-dark text-white"  v-for="item in items" :key="item.id">
                <img :src="'storage/' + item.product.file_path" alt="product image" v-if="item.product.type === 'image'"  width="100" height="100">
                <img :src="item.product.cover" alt="product image" v-if="item.product.type === 'book'"  width="100" height="100"><br>

                <b>Title: {{item.product.title}}</b><br>
                <i>Tyep: {{item.product.type}}</i> <br>
                <!-- زر التحميل -->
                <a :href="'http://127.0.0.1:8000/api/download/' + item.product.file_path" download>
                    <button>Download</button>
                </a>
            </div>
        </div>

    </div>
</template>

<script>
import axios  from '@/axios';
export default{
    data(){
        return {
            items:[]
        }
    },
    methods:{
        async getItems(cartId){
            const response = await axios.get(`/userItems/${cartId}`);
            this.items = response.data;
        },

        async downloadFile(path) {
            try{
                const response = await axios.get(`/download/${path}`);
                console.log(response.data);
            }

            catch(error){
                console.error('Error while download the file ', error);
            }
        }
    },

    created(){
        const cartId = parseInt(this.$route.params.id);
        this.getItems(cartId);
    }
}
</script>
