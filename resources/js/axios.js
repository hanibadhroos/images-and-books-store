import axios  from "axios";
// get token
const token = localStorage.getItem("token");

const instance = axios.create({
    baseURL:'http://127.0.0.1:8000/api',
    withCredentials:true,
    headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
    }
});
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

// إضافة Interceptor لحقن التوكين في كل الطلبات
// instance.interceptors.request.use(
//     (config) => {
//         const token = localStorage.getItem("token");
//         if (token) {
//             config.headers.Authorization = `Bearer ${token}`;
//         }
//         return config;
//     },
//     (error) => {
//         return Promise.reject(error);
//     }
// );

// if (token) {
//     instance.defaults.headers.common["Authorization"] = `Bearer ${token}`;
// }

export default instance;
