<template>
    <v-container>
        <v-text-field
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            v-model="keyword"
            @input="onInput"
        ></v-text-field>

        <v-btn @click="openAddEdit(null)" color="primary" class="my-4">Create new</v-btn>

        <v-tabs
            v-model="tab" bg-color="primary">
            <v-tab value="0">On going</v-tab>
            <v-tab value="1">Archived</v-tab>
        </v-tabs>

        <v-data-table
            :headers="header"
            hide-default-footer
            :items="items"
            :loading="loading"
        >

            <template v-slot:item.status="{item}">
                <v-chip variant="outlined" :color="getStatusColor(item.status)">{{item.status}}</v-chip>
            </template>
            <template v-slot:item.priority_level="{item}">
                <v-chip variant="outlined" :color="getPriorityColor(item.priority_level)">{{item.priority_level}}</v-chip>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    class="me-2"
                    size="small"
                    @click="openAddEdit(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    class="me-2"
                    size="small"
                    v-if="item.status == 'done' && !item.archive"
                    @click="archive(item)"
                >
                    mdi-archive-check
                </v-icon>
                <v-icon
                    class="me-2"
                    size="small"
                    v-if="item.archive"
                    @click="restore(item)"
                >
                mdi-file-restore
                </v-icon>
              </template>
        </v-data-table>
        <v-btn block @click="loadMore">load more</v-btn>
        <v-dialog v-model="openAddEditDialog" max-width="500" persistent>
            <task-form @close="openAddEditDialog = false" @save="updateItems" :task="currentTask" />
        </v-dialog>
    </v-container>
</template>
<script>
import TaskForm from './TaskForm.vue';
export default {
    components: {
        TaskForm
    },
    data: () => {
        return {
            tab: 'on-going',
            currentTask: null,
            openAddEditDialog: false,
            page: 1,
            reset: true,
            keyword: '',
            loading: false,
            items: [],
            header: [
                {
                    sortable: false,
                    title: 'User',
                    key: 'user_name'
                },
                {
                    sortable: false,
                    title: 'Title',
                    key: 'title'
                },
                {
                    sortable: false,
                    title: 'Description',
                    key: 'description'
                },
                {
                    sortable: false,
                    title: 'Due date',
                    key: 'due_date'
                },
                {
                    sortable: false,
                    title: 'Status',
                    key: 'status'
                },
                {
                    sortable: false,
                    title: 'Priority',
                    key: 'priority_level'
                },
                {
                    sortable: false,
                    title: "Actions",
                    key: 'actions'
                }
            ]
        }
    },
    methods: {
        onInput() {
            this.debouncedLoadData()
        },
        loadData() {
            this.loading = true;

            axios.get(`/api/tasks`, {
                params: {
                    keyword: this.keyword,
                    page: this.page,
                    archive: this.tab
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
        openAddEdit(task) {
            this.currentTask = task;
            this.openAddEditDialog = true;
        },
        archive(task) {
            if(confirm("Archive this task")) {
                axios.post(`/api/tasks/${task.id}/archive`).then((res, rej) => {
                    this.items = this.items.filter(item => item.id !== task.id);
                });
            }
        },
        restore(task) {
            if(confirm("Restore this task")) {
                axios.post(`/api/tasks/${task.id}/restore`).then((res, rej) => {
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
                    return 'gray';
            }
        },
        getStatusColor(status) {
            switch (status) {
                case 'done':
                    return 'green';
                default:
                    return 'gray';
            }
        }
    },
    created() {
        this.debouncedLoadData = this.$debounce(this.loadData, 500);
        this.loadData();
    },
    watch: {
        tab(newVal) {
            if(newVal) {
                this.loadData();
            }
        }
    }
}
</script>
