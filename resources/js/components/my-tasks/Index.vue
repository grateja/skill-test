<template>
    <v-container>
        <router-view v-if="$route.params.taskId" />
        <v-row v-else>
            <v-alert color="info" cols="12" sm="4" v-if="items.length == 0">
                You do not have a task
            </v-alert>
            <v-col cols="12" sm="6" v-for="task in items">
                <v-card :title="task.title">
                    <template v-slot:text>
                        {{ task.description }}
                        <h4 class="text-grey">Created: {{ $moment(task.created_at).format('MMM DD, YY hh:mm a') }}</h4>
                        <h4 class="text-grey">Due on: {{ $moment(task.due_date).format('MMM DD, YY hh:mm a') }}</h4>
                    </template>
                    <v-card-actions>
                        <v-chip variant="outlined" :color="getPriorityColor(task.priority_level)">{{task.priority_level}} priority</v-chip>
                        <v-chip v-if="task.status == 'on-going'">On-going</v-chip>
                        <v-spacer></v-spacer>
                        <v-chip color="blue" v-if="task.status == 'done'">Submitted</v-chip>
                        <v-btn v-else @click="openTask(task)">Open</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-btn block @click="loadMore">load more</v-btn>
    </v-container>
</template>
<script>
export default {
    data: () => {
        return {
            currentTask: null,
            openAddEditDialog: false,
            page: 1,
            reset: true,
            loading: false,
            items: []
        }
    },
    methods: {
        onInput() {
            this.page = 1;
            this.debouncedLoadData()
        },
        loadData() {
            this.loading = true;
            axios.get('/api/tasks', {
                params: {
                    keyword: this.keyword,
                    page: this.page,
                    archive: 0
                }
            }).then((res, rej) => {
                if(this.reset) {
                    this.items = res.data.data;
                } else {
                    this.items = this.items.concat(res.data.data);
                }
            }).finally(() => {
                this.loading = false;
            })
        },
        loadMore() {
            this.page = this.page + 1;
            this.reset = false
            this.loadData()
        },
        openTask(task) {
            this.$router.push({
                name: 'viewTask',
                params: {
                    taskId: task.id
                }
            })
        },
        openDelete(task) {
            if(confirm("Delete this task")) {
                axios.delete(`/api/tasks/${task.id}`).then((res, rej) => {
                    this.items = this.items.filter(item => item.id !== task.id);
                });
            }
        },
        updateItems(data) {
            if(data.action == 'create') {
                this.items.push(data.task);
                this.currentTask = data.task;
            } else {
                let index = this.items.findIndex(item => item.id === data.task.id);

                if (index !== -1) {
                    this.items[index] = { ...this.items[index], ...data.task };
                }
            }
        },
        getPriorityColor(priorityLevel) {
            switch (priorityLevel) {
                case 'low':
                    return 'green';
                case 'medium':
                    return 'yellow';
                case 'high':
                    return 'red';
                default:
                    return 'gray'; // Fallback color
            }
        }
    },
    created() {
        this.debouncedLoadData = this.$debounce(this.loadData, 500);
        this.loadData();
    }
}
</script>
