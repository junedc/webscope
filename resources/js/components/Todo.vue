<template>
  <div>
    <div>
      <line-chart :chart-data="datacollection"></line-chart>
    </div>

    <div>
      <b-button v-b-modal.modalAdd @click="clearTodo">Add Task</b-button>

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
          <tr v-for="todo in todos" :key="todo.id">
            <td>{{todo.title}}</td>
            <td>{{todo.description}}</td>
            <td>{{todo.status}}</td>

            <td>
              <b-button v-b-modal.modalEdit @click="editTodo(todo)">Edit</b-button>
            </td>
          </tr>
        </tbody>
      </table>

      <b-modal id="modalEdit" title="Edit Task" @ok="handleUpdate" ref="modal">
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

      <b-modal id="modalAdd" title="Add Task" @ok="handleSave" ref="modaladd">
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
    </div>
  </div>
</template>

<script>
import LineChart from "../LineChart.js";

export default {
  components: {
    LineChart
  },
  data() {
    return {
      todo: { id: null, title: "", description: "", status: "" },
      todos: [],
      //csrf_token: window.axios.defaults.headers.common["X-CSRF-TOKEN"],
      datacollection: null,
      chartLabels: [],
      chartData: []
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.loadTodos();
  },
  methods: {
    loadTodos() {
      //list all todos for login user
      axios.get("/todos").then(response => {
        console.log(response.data.todos);
        this.todos = response.data.todos;
        this.fillData();
      });
    },
    handleSave(evt) {
      evt.preventDefault();
      if (this.todo.title == "" || this.todo.description == "") {
        alert("Please enter title and/or description");
      } else {
        console.log("saving todos");

        axios
          .post("/todos", this.todo)
          .then(response => {
            this.todos.push(response.data.todo);
            this.fillData();
            this.$refs.modaladd.hide();
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    editTodo(todo) {
      this.todo.id = todo.id;
      this.todo.title = todo.title;
      this.todo.description = todo.description;
      this.todo.status = todo.status;
    },
    handleUpdate(evt) {
      // Prevent modal from closing
      console.log("handleok");
      evt.preventDefault();
      if (this.todo.title == "" || this.todo.description == "") {
        alert("Please enter title and/or description");
      } else {
        console.log("updating todos");
        axios
          .put("/todos/" + this.todo.id, this.todo)
          .then(response => {
            console.log("successfully updated todo");
            this.loadTodos();
            this.$refs.modal.hide();
            this.fillData();
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    fillData() {
      let taskCount = 0;
      let allTaskCount = 0;
      let pendingTaskCount = [];
      let allTask = [];
      let chartLabels = [];

      _.each(this.todos, function(key, value) {
        console.log(moment(key.created_at).format("HH:mm:ss"));
        chartLabels.push(moment(key.created_at).format("HH:mm:ss"));
        allTaskCount++;
        allTask.push(allTaskCount);
        if (key.status === "Active") {
          taskCount++;
          pendingTaskCount.push(taskCount);
        } else {
          pendingTaskCount.push(0);
        }
      });

      this.datacollection = {
        labels: chartLabels,
        datasets: [
          {
            label: "Pending Tasks",
            pointRadius: 10,
            fill: false,
            data: pendingTaskCount
          }
        ]
      };
    },
    clearTodo() {
      this.todo.id = null;
      this.todo.title = "";
      this.todo.description = "";
      this.todo.status = "";
    }
  }
};
</script>

