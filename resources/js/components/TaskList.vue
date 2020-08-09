<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Tasks ({{ auth ? auth.name: "null" }})</div>
          <div class="card-body">
            <ul>
              <li v-for="task in tasks" :key="task.id">
                {{task.name}}
                <button
                  style="background:none;border:none;padding:4px;color:red;"
                  @click="deleteTask(task.id)"
                >Delete</button>
              </li>
            </ul>
            <!-- <input type="text" :v-model="task" @keypress.enter="addTask" /> -->
            <div>
              <input type="text" v-model="task" @keypress.enter="addTask" />
            </div>
            <span style="color:#ccc">{{ task }}</span>
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
      tasks: [],
      task: "",
    };
  },
  mounted() {
    console.log("Tasklist mounted.");
    this.listTask(); //// TO Get and list out data
  },
  methods: {
    //   list, items
    listTask() {
      axios.get("/task").then((_res) => {
        // console.log(_res.data);
        this.tasks = _res.data;
      });
    },

    // Create New item
    addTask() {
      console.log(this.task, "Added");
      axios.post("task", { name: this.task }).then((_res) => {
        // console.log(_res.data);
        this.tasks.push(_res.data);
      });
      this.task = null;
    },

    // Delete Item
    deleteTask(_id) {
      //   console.log(_id);
      axios.delete("task/" + _id).then((_res) => {
        // console.log(_res.data);
        this.tasks = this.tasks.filter((el) => el.id != _id);
      });
    },
  },
  props: {
    auth: Object,
  },
};
</script>
