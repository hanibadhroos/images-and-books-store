<template>
  <div class="cart-page">
    <!-- مؤشر التحميل -->
    <div class="loading-spinner" v-if="loading">
      <div class="spinner"></div>
    </div>

    <!-- جدول سلة المشتريات -->
    <div class="table-responsive fade-in-up" v-if="!loading">
      <table class="table table-hover cart-table">
        <thead class="table-dark">
          <tr>
            <th>عنوان المنتج</th>
            <th>السعر</th>
            <th>الكمية</th>
            <th>الإجمالي</th>
            <th>إجراء</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in cartItems" :key="item.id" class="cart-row fade-in-up">
            <td class="d-flex align-items-center">
              <img
                :src="item.product.watermark_path"
                alt="صورة المنتج"
                class="product-img me-2"
              />
              <span class="product-title">{{ item.product.title }}</span>
            </td>
            <td>{{ item.product.price }} $</td>
            <td>
              <span v-if="!item.editMode">{{ item.quantity }}</span>
              <input
                v-else
                type="number"
                min="1"
                class="form-control quantity-input"
                v-model="item.quantity"
              />
              <button class="btn btn-sm btn-outline-success ms-2" @click="editItem(item)">
                {{ item.editMode ? 'حفظ' : 'تعديل' }}
              </button>
            </td>
            <td>{{ item.product.price * item.quantity }} $</td>
            <td>
              <button class="btn btn-sm btn-outline-danger" @click="deleteItem(item.id)">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr class="table-success">
            <th>الإجمالي الكلي</th>
            <th colspan="4">{{ grandTotal }} $</th>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- زر الشراء -->
    <div class="buy-div fade-in-up" v-if="!loading">
      <button class="buy-button" @click="createOrder(grandTotal)">
        <span v-if="!buyLoading">شراء</span>
        <span v-else class="buy-spinner"></span>
      </button>
      <b class="total-text ms-3">{{ grandTotal }} $</b>
    </div>
  </div>
</template>


<script>
import {mapGetters} from 'vuex';
import axios from '@/axios';
export default {

    data(){
        return {
            cartItems:[],
            loading: true, // حالة التحميل
            buyLoading:false,
            cart_id:null,
        }
    },

    computed:{
        grandTotal(){
            return this.cartItems.reduce(
                (total, item)=>total+ item.product.price * item.quantity,
                0
            );
        },
        ...mapGetters(['authUser']),
    },
    methods:{
        ///// Method for get all items
       async fetchCartItems(){
            try{

                this.loading = true; // بدء حالة التحميل
                const usercart = await axios.get(`/userCart/${this.authUser.id}`);
                const cart_id = usercart.data.id;
                this.cart_id = usercart.data.id;
                axios.get(`/userItems/${cart_id}`).then((response)=>
                    {
                        this.cartItems= response.data.map((item)=>
                        ({
                            ...item,
                            editMode:false,
                        }));
                    }
                );

            }
            catch(error){
                console.error('an error accurred while fetching items: ',error);
            }
            finally{
                this.loading = false;
            }
        },

        editItem(item){
            ////// if editMode equal to True, Update the item.
            if(item.editMode){
                axios.put(`/cartItems/${item.id}`,{
                    quantity:item.quantity
                }).then(()=>{
                    item.editMode= false;
                });
            }
            else{
                item.editMode = true;
            }
        },

        ///// For delete the item
        deleteItem(id){
            axios.delete(`/cartItems/${id}`);
            this.cartItems = this.cartItems.filter(item => item.id !== id);
        },

        ///// For add new order
        async createOrder(total_price){

            try{
                const response = await axios.post('/orders',{
                    user_id:this.authUser.id,
                    total_price:total_price,
                    status:'hold', ///// hold, completed, canceled
                    cartItems:this.cartItems,

                });
                this.buyLoading=true;
                const order_id = response.data.order_id;
                ///// pay with paypal
                const payResponse = await axios.post('paypal/payment',{
                    amount: total_price, // المبلغ الإجمالي
                    order_id: order_id, // تمرير معرف الطلب
                    user_id:this.authUser.id,
                    cartId: this.cart_id
                });
                if (payResponse.data.links) {
                    const approveLink = payResponse.data.links.find(link => link.rel === 'approve');
                    if (approveLink) {
                        window.location.href = approveLink.href; // الانتقال إلى صفحة PayPal للموافقة
                    }
                }

            }
            catch(error){
                console.error('an error accurred while create new order', error);
            }

        },




    },
    async created(){
        // إذا كانت authUser جاهزة، قم بجلب البيانات مباشرةً
        if (this.authUser && this.authUser.id) {
            await this.fetchCartItems();
        }

    },
    watch:{
        authUser: {
            immediate: true, // استدعاء المعالج فوراً عند التحميل
            handler(newAuthUser) {
                if (newAuthUser && newAuthUser.id) {
                    this.fetchCartItems();
                }
            },
        },
    },

}
</script>

<style scoped>
.cart-page {
  padding: 20px;
  background-color: #f5f7fa;
}

.table-responsive {
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  animation: fadeIn 0.8s ease-in-out;
}

.cart-table th,
.cart-table td {
  vertical-align: middle;
  text-align: center;
}

.cart-row {
  transition: background-color 0.3s ease;
}

.cart-row:hover {
  background-color: #f0f0f0;
}

.product-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
}

.product-title {
  font-weight: 500;
  color: #333;
}

.quantity-input {
  width: 70px;
  display: inline-block;
}

.buy-div {
  margin-top: 20px;
  text-align: center;
}

.buy-button {
  background: linear-gradient(135deg, #3a86ff, #00b894);
  color: white;
  border: none;
  padding: 12px 40px;
  font-size: 18px;
  border-radius: 50px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.buy-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
}

.buy-spinner {
  width: 20px;
  height: 20px;
  border: 3px solid #fff;
  border-top: 3px solid transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  display: inline-block;
}

.total-text {
  font-size: 20px;
  font-weight: bold;
  color: #2d3436;
}

.loading-spinner {
  position: fixed;
  top: 0;
  left: 0;
  background: rgba(255, 255, 255, 0.85);
  width: 100%;
  height: 100%;
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner {
  border: 8px solid #ddd;
  border-top: 8px solid #3a86ff;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
}

/* Animations */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@keyframes fadeInUp {
  0% {
    transform: translateY(20px);
    opacity: 0;
  }
  100% {
    transform: translateY(0px);
    opacity: 1;
  }
}

.fade-in-up {
  animation: fadeInUp 0.7s ease forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>

