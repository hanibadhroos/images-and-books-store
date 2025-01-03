import { createRouter, createWebHashHistory } from "vue-router";
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Home from './components/Home.vue';
import AddProduct from './components/AddProduct.vue';
import UserProfile from './components/UserProfile.vue';
import productDetails from './components/ProductDetails.vue';
import Images from './components/Images.vue';
import Books from './components/Books.vue';
import myProducts from './components/MyProducts.vue';
import EditProduct from './components/EditProduct.vue';
import MyCart from './components/MyCart.vue';
import Payment from './components/Payment.vue';
import Comment from './components/Comment.vue';
import Categories from './components/Categories.vue';
import EditCategory from './components/EditCategory.vue';


const routes = [
    {
        path:'/Login',
        name:'Login',
        component: Login,
        meta: {public: true}
    },
    {
        path:'/Register',
        name:'Register',
        component: Register,
        meta: {public: true}
    },
    {
        path:'/',
        name:'Home',
        component: Home,
        meta: {public: false}
    },
    {
        path:'/add',
        name:'Add',
        component:AddProduct,
        meta:{public:false}
    },
    {
        path:'/profile',
        name:'profile',
        component:UserProfile,
        meta:{public:false}
    },
    {
        path:'/product/details/:id',
        name:'productDetails',
        component:productDetails,
        meta:{public:false},
        props:true
    },
    {
        path:'/images',
        name:'images',
        component:Images,
        props:{public:false}
    },
    {
        path:'/books',
        name:'books',
        component:Books,
        props:{public:false}
    },
    {
        path:'/myProducts',
        name:'myProducts',
        component:myProducts,
        props:{public:false}
    },
    {
        path:'/editProduct/:id',
        name:'editProduct',
        component:EditProduct,
        meta:{public:false},
        props:true
    },
    {
        path:'/myCart',
        name:'myCart',
        component:MyCart,
        meta:{public:false},
        props:true
    },
    {
        path:'/pay',
        name:'pay',
        component:Payment,
        meta:{public:false},
        props:true
    },
    {
        path:'/comment/:id',
        name:'comment',
        component:Comment,
        meta:{public:false},
        props:true
    },
    {
        path:'/categories',
        name:'categories',
        component:Categories,
        meta:{public:false},
    },
    {
        path:'/edit-category/:id',
        name:'editCategory',
        component:EditCategory,
        meta:{public:false},
        props:true
    },
]


const router = createRouter({
    history: createWebHashHistory(),
    routes
})

router.beforeEach((to, from, next)=>
{
    const isAuthenticated = !!localStorage.getItem("token");

    if(!to.meta.public && !isAuthenticated)
    {
        next({name: 'Login'});
    }
    else
    {
        next();
    }
});

export default router;
