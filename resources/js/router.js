import { createRouter, createWebHistory } from "vue-router";

import _404 from './components/_404.vue'
import MainBody from './components/MainBody.vue'
import Login from './components/auth/Login.vue'
import Register from './components/register/Index.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
    	{
    		path: "/",
            name: 'home',
    		component: MainBody,
    	},
    	{
    		path: "/login",
            name: 'login',
    		component: Login
    	},
        {
            path: '/register',
            name: 'register',
            component: Register
        },
	    {
	    	path: "/:pathMatch(.*)",
	    	name: 'not found',
	    	component: _404
	    }
	]
})

export default router
