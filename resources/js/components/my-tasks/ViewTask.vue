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
                <v-card-actions v-if="task.status == 'todo'">
                    <v-btn @click="start">start task</v-btn>
                </v-card-actions>
            </v-card>
        </v-card>

        <form @submit.prevent="submitFile" v-if="task && task.status != 'todo'">
            <v-card>
                <v-card-text>
                    <v-text-field
                        v-model="remarks"
                        label="Remarks"
                        variant="outlined"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <input type="file" @change="handleFileUpload" />
                    <v-spacer></v-spacer>
                    <button type="submit">Upload</button>
                </v-card-actions>
            </v-card>
        </form>
    </v-container>
</template>

<script>

export default {
    data() {
        return {
            task: null,
            file: null,
            filePath: null,
            remarks: ''
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
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        submitFile() {
            const formData = new FormData();
            formData.append('file', this.file);
            formData.append('remarks', this.remarks);

            axios.post(`/api/tasks/${this.taskId}/submit`, formData, {
                headers: {
                'Content-Type': 'multipart/form-data',
                },
            }).then((res) => {
                this.task.task_attachments.push(res.data)
                this.file = null
                this.remarks = null;
            });
        },
        start() {
            if(confirm("Start this task?")) {
                this.$store.dispatch('post', {
                    url: `/api/tasks/${this.taskId}/start-task`
                }).then(res => {
                    this.task = res.data
                })
            }
        }
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
