<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Update Category</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="update">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" v-model="category.title">
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" v-model="category.description">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "update-category",
    data() {
        return {
            category: {
                title: "",
                description: "",
                _method: "patch"
            }
        }
    },
    mounted() {
        this.showCategory()
    },
    methods: {
        showCategory() {
            axios.get(`/api/category/edit/${this.$route.params.id}`).then(response => {
                const { title, description } = response.data
                this.category.title = title
                this.category.description = description
            }).catch(error => {
                console.log(error)
            })
        },
        update() {
            axios.post(`/api/category/update/${this.$route.params.id}`, {
                title: this.category.title,
                description: this.category.description
            }).then(response => {
                this.$router.push({ name: "categoryList" })
            }).catch(error => {
                console.log(error)
            })
        }
    }
}
</script>
