<template>
    <v-app>
        <NavigationDrawer/>
    </v-app>
</template>

<script>
import NavigationDrawer from './layout/NavigationDrawer.vue';
	export default {
        components: {
            NavigationDrawer
        },
        methods: {
            logout() {
                this.$store.dispatch('auth/logout').then((res, rej) => {
                    this.$router.push({
                        name: 'login'
                    })
                })
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.getCurrentUser;
            }
        }
	}
</script>
