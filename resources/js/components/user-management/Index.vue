<template>
    <v-container>
        <v-card title="User details">
            <v-card-text>
                <p>Name</p>
                <p class="text-h5">{{currentUser.name}}</p>
                <p>Email</p>
                <p class="text-h5">{{currentUser.email}}</p>
            </v-card-text>
            <v-card-actions>
                <v-btn :to="{name: 'editSelf'}">Edit</v-btn>
                <v-btn :to="{name: 'changePassword'}">Change password</v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>
<script>
export default {
    computed: {
        currentUser() {
            return this.$store.getters.getCurrentUser;
        }
    }
}
</script>
