<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
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
        <test />
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
    this.connect_live_broadcast(); //// Listen ECHO and Pusher for new Event
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
        // this.tasks.push(_res.data);
        console.log("Added.");
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

    connect_live_broadcast() {
      console.log("Listening on channel 'Task' ");
      Echo.channel("task").listen(".newTask", (e) => {
        this.tasks.push(e.task);
      });
    },
  },
  props: {
    auth: Object,
  },
};
</script>
