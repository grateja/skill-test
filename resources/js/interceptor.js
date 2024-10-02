import store from './store/store.js'
import router from './router.js'

axios.interceptors.response.use((res, rej) => {
    return res;
}, err => {
    if(err.status == 403) {
        alert('Forbidden! You are not allowed to perform this action')
    }
    return Promise.reject(err);
});

store.dispatch('get', {
    tag: 'check',
    url: '/api/auth/check',
}).then((res, rej) => {
    store.commit('setUser', res.data, {root: true})
}).catch(err => {
    if(err.response && err.response.status == 401) {
        router.push({ name: 'login'})
    }
});
