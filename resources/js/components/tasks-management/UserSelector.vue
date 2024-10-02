<template>
    <div>
        <v-card>
            <v-card-title>Select user</v-card-title>
            <v-card-text>
                <v-text-field
                    v-model="keyword"
                    append-inner-icon="mdi-magnify"
                    placeholder="Type customer name"
                    variant="outlined"
                    :loading="loadingKeys.hasAny('get-users')"
                    @input="onInput"
                />
                <v-list>
                    <v-list-item
                        v-for="(user, index) in items"
                        :title="user.name"
                        :subtitle="user.address"
                        :class="{ 'highlighted': model && user.id == model.id }"
                        :key="user.id"
                        @click="select(user)"
                    >
                    </v-list-item>
                </v-list>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="close">close</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
export default {
    props: [
        'model'
    ],
    data: () => {
        return {
            keyword: '',
            items: []
        }
    },
    methods: {
        onInput() {
            this.debouncedLoadData();
        },
        loadData() {
            this.$store.dispatch('get', {
                url: '/api/user-management',
                tag: 'get-users',
                formData: {
                    keyword: this.keyword
                }
            }).then((res, rej) => {
                this.items = res.data;
            });
        },
        select(data) {
            this.$emit('select', data);
            this.$emit('close');
        },
        close() {
            this.$emit('close');
        }
    },
    computed: {
        loadingKeys() {
            return this.$store.getters.loadingKeys;
        }
    },
    created() {
        this.debouncedLoadData = this.$debounce(this.loadData, 100);
        this.loadData();
    }
}
</script>

<style scoped>
.highlighted {
    background-color: #c4e8f5;
    font-weight: bold;
}
</style>
