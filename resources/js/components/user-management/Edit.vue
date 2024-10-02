<template>
    <v-container>
        <form @submit.prevent="submit">
            <v-card title="Update profile" max-width="400">
                <v-divider></v-divider>
                <v-card-text>
                    <v-text-field
                        v-model="formData.name"
                        label="Name"
                        variant="outlined"
                        :error-messages="errors.get('name')"
                    />
                    <v-text-field
                        v-model="formData.email"
                        label="Email"
                        variant="outlined"
                        :error-messages="errors.get('email')"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-btn type="submit" color="primary" :loading="loadingKeys.hasAny('save-user-self')">Save</v-btn>
                    <v-btn :to="{name: 'userManagement'}">close</v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </v-container>
</template>
<script>
export default {
    data: () => {
        return {
            formData: {
                name: null,
                email: null
            }
        }
    },
    methods: {
        submit() {
            this.$store.dispatch('post', {
                url: '/api/user-management/update-self',
                tag: 'save-user-self',
                formData: this.formData
            }).then((res) => {
                this.$store.commit('setUser', res.data.user , {root: true})
                this.$router.push({name: 'userManagement'})
            });
        }
    },
    watch: {
        currentUser: {
            handler(newVal) {
                if(newVal) {
                    this.formData.name = newVal.name;
                    this.formData.email = newVal.email;
                } else {
                    this.formData.name = null;
                    this.formData.email = null;
                }
            },
            immediate: true
        }
    },
    computed: {
        loadingKeys() {
            return this.$store.getters.loadingKeys;
        },
        currentUser() {
            return this.$store.getters.getCurrentUser;
        },
        errors() {
            return this.$store.getters.getErrors;
        }
    }
}
</script>
