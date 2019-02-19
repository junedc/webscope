<template>
    <div>
        <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#exampleModal"
        >Add Todo
        </button>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="todo in todos">
                <td>{{todo.title}}</td>
                <td>{{todo.description}}</td>
                <td>{{todo.status}}</td>

                <td>
                    <b-button v-b-modal.modalEdit @click="editTodo(todo)">Edit</b-button>
                </td>
            </tr>
            </tbody>
        </table>

        <b-modal id="modalEdit" title="Edit Task" @ok="handleOk" ref="modal">
            <form>
                <div class="form-group">
                    <label for="title" class="col-form-label">Title:</label>
                    <input type="text" class="form-control" id="title" v-model="todo.title">
                </div>
                <div class="form-group">
                    <label for="description-text" class="col-form-label">Description:</label>
                    <textarea class="form-control" id="description-text" v-model="todo.description"></textarea>
                </div>

                <div class="form-group">
                    <label class="mr-sm-2" for="inlineFormCustomSelect">Status</label>
                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" v-model="todo.status">
                        <option value="Active">Active</option>
                        <option value="Done">Done</option>
                    </select>
                </div>

            </form>
        </b-modal>

        <div
                class="modal fade"
                id="exampleModal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Todo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" id="title" v-model="todo.title">
                            </div>
                            <div class="form-group">
                                <label for="description-text" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="description-text"
                                          v-model="todo.description"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button
                                type="button"
                                class="btn btn-primary"
                                @click="saveTodos"
                                data-dismiss="modal"
                        >Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                todo: {id: null, title: "", description: "", status: ""},
                todos: [],
                csrf_token: window.axios.defaults.headers.common["X-CSRF-TOKEN"]
            };
        },
        mounted() {
            console.log("Component mounted.");
            this.loadTodos();
        },
        methods: {
            loadTodos() {
                axios.get("/todos").then(response => {
                    console.log(response.data.todos);
                    this.todos = response.data.todos;
                });
            },
            saveTodos() {
                console.log("saving todos");
                this.todo.status = "Active";
                axios
                    .post("/todos", this.todo)
                    .then(response => {
                        this.todos.push(response.data.todo);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            editTodo(todo) {
                this.todo.id = todo.id;
                this.todo.title = todo.title;
                this.todo.description = todo.description;
                this.todo.status = todo.status;
            },
            updateTodos(todo) {
                axios
                    .put("/todos/" + todo.id, this.todo)
                    .then(response => {
                        console.log("successfully updated todo");
                        this.loadTodos();
                        this.$refs.modal.hide();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            handleOk(evt) {
                // Prevent modal from closing
                console.log("handleok");
                evt.preventDefault();
                if (!this.todo.title) {
                    alert("Please enter title");
                } else {
                    this.updateTodos(this.todo);
                }
            }
        }
    };
</script>

