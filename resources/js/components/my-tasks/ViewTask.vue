<template>
    <div>
        <form @submit.prevent="submitFile">
            <input type="file" @change="handleFileUpload" />
            <button type="submit">Upload</button>
        </form>
        <div v-if="filePath">
        <p>File uploaded to: {{ filePath }}</p>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            file: null,
            filePath: null,
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        submitFile() {
            const formData = new FormData();
            formData.append('file', this.file);

            axios.post(`/api/tasks/${this.taskId}/submit`, formData, {
                headers: {
                'Content-Type': 'multipart/form-data',
                },
            }).then((res) => {

            });
        },
    },
    computed: {
        taskId() {
            return this.$route.params.taskId;
        }
    }
};
  </script>
