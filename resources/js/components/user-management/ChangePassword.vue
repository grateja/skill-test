<template>
    <v-container>
        <form @submit.prevent="submit">
            <v-card title="Update profile" max-width="400">
                <v-divider></v-divider>
                <v-card-text>
                    <v-text-field
                        v-model="formData.currentPassword"
                        type="password"
                        label="Current password"
                        variant="outlined"
                        :error-messages="errors.get('currentPassword')"
                    />

                    <v-text-field class="my-3" v-model="formData.newPassword" label="Password" variant="outlined"
                    :error-messages="errors.get('newPassword')"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    @click:append="showPassword = !showPassword"
                    @keydown="loadingKeys.remove('newPassword')"  />

                <v-expand-transition>
                    <v-text-field class="my-3" v-if="!showPassword" v-model="formData.newPassword_confirmation" label="Confirm Password" variant="outlined" type="password" />
                </v-expand-transition>
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
            showPassword: false,
            formData: {
                currentPassword: null,
                newPassword: null,
                newPassword_confirmation: null
            }
        }
    },
    methods: {
        submit() {
            if(this.showPassword) {
                this.formData.newPassword_confirmation = this.formData.newPassword
            }

            this.$store.dispatch('post', {
                url: '/api/user-management/change-password',
                tag: 'save-user-self',
                formData: this.formData
            }).then((res) => {
                // this.$store.commit('setUser', res.data.user , {root: true})
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
