<template>
    <v-app>
        <v-navigation-drawer
            v-model="drawer"
            app
        >
            <v-list-item :to="{name: 'userManagement'}" v-if="currentUser" :title="currentUser.name" :subtitle="currentUser.email"></v-list-item>
            <v-divider></v-divider>
            <v-list-item v-for="item in items" link :title="item.title" :to="item.to">

            </v-list-item>
        </v-navigation-drawer>

        <v-app-bar
        app
        color="primary"
        dark
        >
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>{{$route.meta.displayName}}</v-toolbar-title>
            <v-spacer/>
            <v-btn @click="logout">logout</v-btn>
        </v-app-bar>
        <v-main>
            <router-view/>
        </v-main>
    </v-app>
</template>

  <script>
  export default {
    data: () => ({
        title: 'Home',
      drawer: false,
      items: [
        // { title: 'User management', to: '/user-management' },
        // Add more items as needed
      ],
    }),
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
        },
        loadingKeys() {
            return this.$store.getters.loadingKeys;
        }
    }
  };
  </script>

  <style scoped>
  /* Add any custom styles here */
  </style>
