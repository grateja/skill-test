<template>
    <v-container>
        <v-row>
            <v-alert color="info" cols="12" sm="4" v-if="data.length == 0">
                No users with a task
            </v-alert>
            <v-col cols="12" sm="4" v-for="user in data">
                <v-card :title="user.name">
                    <template v-slot:text>
                        {{ user.email }}
                        <h4>{{ user.total_tasks_count }} total task(s)</h4>
                    </template>
                    <v-card-actions>
                        <v-chip variant="outlined" color="warning">{{ user.tasks_todo_count }} todo</v-chip>
                        <v-chip variant="outlined" color="blue">{{ user.tasks_on_going_count }} on-going</v-chip>
                        <v-chip variant="outlined" color="success">{{ user.tasks_done_count }} done</v-chip>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data: () => {
        return {
            data: []
        }
    },
    methods: {
        loadAll() {
            this.$store.dispatch('get', {
                tag: 'load-dashboard',
                url: '/api/dashboard'
            }).then((res) => {
                this.data = res.data;
            })
        }
    },
    created() {
        this.loadAll();
    }
}
</script>
