<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header">Tasks ({{ auth ? auth.name: "null" }})</div>
          <div class="card-body">
            <ul>
              <li v-for="task in tasks" :key="task.id" @dblclick="editTask(task)">
                <span v-if="task.editMode">
                  <input type="text" :value="task.name" @blur="task.editMode = false" />
                </span>
                <span v-else>{{task.name}}</span>

                <button
                  style="background:none;border:none;padding:4px;color:green; margin-left:20px"
                  v-if="task.editMode"
                  @click="updateTask(task)"
                >Save</button>
                <button
                  style="background:none;border:none;padding:4px;color:gray; margin-left:20px"
                  v-if="task.editMode"
                  @click="(task.editMode = false)"
                >Cancel</button>

                <button
                  style="background:none;border:none;padding:4px;color:red; margin-left:20px"
                  v-if="!task.editMode"
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
        console.log("Post APi fired for addition.");
        // console.log(_res.data);
        // this.tasks.push(_res.data); // Added in Event
      });
      this.task = null;
    },

    // Delete Item
    deleteTask(_id = null) {
      //   console.log(_id);
      if (_id) {
        axios.delete("task/" + _id).then((_res) => {
          //   console.log(_res.data);
          this.tasks = this.tasks.filter((el) => el.id != _id);
        });
      }
    },

    editTask(_task) {
      this.$set(_task, "editMode", true);
      this.taskEditing = _task;
      console.log(_task);
    },

    updateTask(_task) {
      console.log("Update::", _task.name);
      console.log(this.taskEditing);
    },

    connect_live_broadcast() {
      console.log("Listening on channel 'Task' ()() ");
      Echo.channel("task")
        .listen(".task.created", (_e) => {
          console.log(
            "broadcast receiver::::" + "channel(task) and listen(.task.created)"
          );
          this.tasks.push(_e.task);
        })
        .listen(".task.deleted", (_e) => {
          //   console.log(_e.task, _e.task.id);
          this.tasks = this.tasks.filter((el) => el.id != _e.task.id);
        });
    },
  },
  props: {
    auth: Object,
  },
};
</script>
