<template>
    <v-container>
        <v-card v-if="task">
            <v-card :title="task.title">
                <template v-slot:text>
                    {{ task.description }}
                    <v-list lines="2">
                        <v-list-item
                            v-for="item in task.task_attachments"
                            :title="item.file_name"
                            :key="item.id"
                        >
                            <template v-slot:subtitle>
                                <p>{{item.remarks}}</p>
                                <p>{{$moment(item.created_at).format('MMM DD, YY, hh:mm a')}}</p>
                            </template>
                        </v-list-item>
                    </v-list>
                </template>
                <v-card-actions v-if="task.status == 'done'">
                    <v-btn @click="archive" v-if="!task.archive">Archive</v-btn>
                    <v-btn @click="restore" v-if="task.archive">Restore</v-btn>
                </v-card-actions>
            </v-card>
        </v-card>
    </v-container>
</template>

<script>

export default {
    data() {
        return {
            task: null,
            file: null,
            filePath: null,
        };
    },
    methods: {
        loadTask(taskId) {
            this.$store.dispatch('get', {
                url: `/api/tasks/${taskId}`
            }).then((res) => {
                this.task = res.data;
            })
        },
        archive() {
            if(confirm("Archive this task")) {
                axios.post(`/api/tasks/${this.task.id}/archive`).then((res, rej) => {
                    this.task = res.data;
                    // this.items = this.items.filter(item => item.id !== task.id);
                });
            }
        },
        restore() {
            if(confirm("Restore this task")) {
                axios.post(`/api/tasks/${this.task.id}/restore`).then((res, rej) => {
                    this.task = res.data;
                    // this.items = this.items.filter(item => item.id !== task.id);
                });
            }
        },
    },
    computed: {
        taskId() {
            return this.$route.params.taskId;
        }
    },
    watch: {
        taskId: {
            handler(newVal) {
                if(newVal) {
                    this.loadTask(newVal)
                }
            },
            immediate: true
        }
    }
};
  </script>
