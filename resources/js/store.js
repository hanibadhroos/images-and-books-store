import {createStore} from "vuex";
import router from './router';


export default createStore({
    state:{
        isLoggedIn: !!localStorage.getItem('token'),
        authUser: null
    },
    mutations:{
        LOGIN(state, user)
        {
            state.isLoggedIn = true,
            ////////
            state.authUser = user
        },
        LOGOUT(state)
        {
            state.isLoggedIn = false

        },
        setAuthUser(state, user){
            state.authUser = user
        }
    },
    actions:{

        fetchAuthUser({commit},user){
            commit('setAuthUser', user);

        },

        login({commit})
        {
            commit('LOGIN')


        },
        logout({commit, dispatch})
        {
            commit('LOGOUT');
            dispatch('navigateToLogin');
        },
        navigateToLogin()
        {
            router.push({name:'Login'});
        }
    },
    getters:{
        authUser: (state)=>state.authUser
    }

})
