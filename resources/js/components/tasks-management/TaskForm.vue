<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} task</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.title" label="Title" variant="outlined" :error-messages="errors.get('title')"/>
                    <v-text-field class="my-4" v-model="formData.description" label="Description" variant="outlined" :error-messages="errors.get('description')"/>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field class="my-4" v-model="due_date" label="Due date" variant="outlined" :error-messages="errors.get('due_date')" type="date"/>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field class="my-4" v-model="due_time" label="time" variant="outlined" :error-messages="errors.get('due_date')" type="time"/>
                        </v-col>
                    </v-row>
                    <v-select :items="['low', 'medium', 'high']" variant="outlined" v-model="formData.priority_level" label="Priority" :error-messages="errors.get('priority_level')"></v-select>
                    <p class="text-red" v-if="errors.has('user_id')">{{errors.get('user_id')}}</p>
                    <v-btn @click="openSelectUserDialog = true">Assign to {{ userName }}</v-btn>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary" :loading="loadingKeys.hasAny('save-task')">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>
        <v-dialog v-model="openSelectUserDialog">
            <user-selector :model="user" @select="(data) => user = data" @close="openSelectUserDialog = false" />
        </v-dialog>
    </div>
</template>

<script>
import UserSelector from './UserSelector.vue'

export default {
    components: {UserSelector},
    props: ['task'],
    data() {
        return {
            action: 'create',
            openSelectUserDialog: false,
            formData: {
                priority_level: null,
                title: null,
                description: null,
            },
            user: null,
            due_date: null,
            due_time: null,
        }
    },
    methods: {
        submit() {
            let url = this.task ? `/api/tasks/${this.task.id}/${this.action}` : `/api/tasks/${this.action}`

            this.formData.due_date = `${this.due_date} ${this.due_time}`
            this.formData.user_id = this.user ? this.user.id : null

            this.$store.dispatch('post', {
                tag: 'save-task',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    task: res.data
                });
            });
        },
    },
    created() {
        if(this.users.length == 0) {
            this.$store.dispatch('loadUsers');
        }
    },
    computed: {
        errors() {
            return this.$store.getters.getErrors;
        },
        loadingKeys() {
            return this.$store.getters.loadingKeys
        },
        users() {
            return this.$store.getters.getUsers;
        },
        userName() {
            return this.user ? this.user.name : '...'
        }
    },
    watch: {
        task: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.title = newVal.title;
                    this.formData.description = newVal.description;
                    this.formData.priority_level = newVal.priority_level;
                    this.formData.due_date = newVal.due_date
                    this.formData.priority_level = newVal.priority_level
                    this.action = 'update'
                } else {
                    this.formData.title = null;
                    this.formData.description = null;
                    this.formData.priority_level = null;
                    this.formData.due_date = null
                    this.formData.priority_level = null
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
