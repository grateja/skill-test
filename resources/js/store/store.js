import { createStore } from 'vuex'
import Mapper from '../helper/Mapper.js';
import LoadingKeys from '../helper/LoadingKeys.js';
import auth from './auth';
import msc from './msc.js';

const store = createStore({
    state () {
        return {
            errorKeys: Mapper,
            loadingKeys: new LoadingKeys(),
            currentUser: null,
            users: []
        }
    },
    mutations: {
        setLoading(state, tag) {
            state.loadingKeys.add(tag);
            // if(!state.loadingKeys.includes(tag)) {
            //     state.loadingKeys.push(tag)
            // }
        },
        setUsers(state, users) {
            state.users = users;
        },
        clearLoading(state, tag) {
            state.loadingKeys.remove(tag);
        },
        setErrors(state, errors) {
            state.errorKeys.errors = errors;
        },
        clearErrors(state, key) {
            state.errorKeys.clear(key);
        },
        setUser(state, user) {
            state.currentUser = user
        }
    },
    actions: {
        post(context, data) {
            context.commit('clearErrors');
            context.commit('setLoading', data.tag)
            return axios.post(data.url, data.formData).then((res) => {
                console.log("res", res);
                return res;
            }).catch(err => {
                context.commit('setErrors', err.response.data.errors);
                return Promise.reject(err);
            }).finally(() => {
                context.commit('clearLoading', data.tag)
            });
        },
        get(context, data) {
            context.commit('clearErrors');
            context.commit('setLoading', data.tag)
            return axios.get(data.url, {
                params: data.formData
            }).then((res, rej) => {
                return res;
            }).catch(err => {
                context.commit('setErrors', err.response.data.errors);
                return Promise.reject(err);
            }).finally(() => {
                context.commit('clearLoading', data.tag)
            });
        },
        loadUsers(context, data) {
            context.commit('setLoading', 'get-users')
            return axios.get('/api/user-management').then((res, rej) => {
                this.commit('setUsers', res.data);
                return res;
            }).catch(err => {
                return Promise.reject(err);
            }).finally(() => {
                context.commit('clearLoading', 'get-users')
            });
        }
    },
    getters: {
        getErrors(state) {
            return state.errorKeys;
        },
        loadingKeys(state) {
            return state.loadingKeys;
        },
        getCurrentUser(state) {
            return state.currentUser;
        },
        getUsers(state) {
            return state.users
        }
    },
    modules: {
        auth,
        msc
    }
})

export default store
