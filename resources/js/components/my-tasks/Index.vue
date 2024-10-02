<template>
    <v-container>
        <router-view v-if="$route.params.taskId" />
        <v-row v-else>
            <v-col cols="12" sm="4" v-for="task in items">
                <v-card :title="task.title" :description="task.description" @click="openTask(task)">

                </v-card>
            </v-col>
        </v-row>
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
            this.debouncedLoadData()
        },
        loadData() {
            this.loading = true;
            axios.get('/api/tasks', {
                params: {
                    keyword: this.keyword,
                    page: this.page
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
