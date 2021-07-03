<template >
  <div>
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
          <div class="modal-body">
            <select class="form-control" multiple @change="alerted">
              <option v-for="result in results" :key="result.id">{{ result.name }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="top-margin flex-container">
      <div class="card card-height">
        <div class="card-header">chat</div>

        <div class="order-comment">
          <ul class="list-group list-group-flush">
            <li
              class="list-group-item list-group-item-success"
              v-for="message in messages"
              :key="message.id"
            >
              <div v-if="message.status == 's'">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">{{ message.text }}</h5>
                  <!-- <small style="color:rebeccapurple;">{{ message.date }}</small> -->
                </div>
                <small>{{ message.from_name }}</small>
              </div>

              <div v-else>
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">{{ message.text }}</h5>
                  <!-- <small style="color:rebeccapurple;">{{ message.date }}</small> -->
                </div>
                <small style="float:right;">{{ message.from_name }}</small>
              </div>
            </li>
          </ul>
        </div>
        <!--  -->
        <!-- //   change here now -->
        <form @submit.prevent="onsubmit">
          <input
            type="text"
            v-model="query"
            v-html="query"
            class="form-control"
            style="width: 100%;"
            placeholder="Add Comment"
          />
          <button type="submit">send</button>
        </form>
      </div>
    </div>
    {{ user_name }} {{ user }}
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["Auser"],
  data() {
    return {
      query: "",
      results: [],
      title: "",
      user: [],
      user_name: [],
      messages: []
    };
  },
  watch: {
    query: function(newQuestion, oldQuestion) {
      const org = newQuestion.split(" ");
      let only = org.filter(s => s[0] == "@");
      this.check(only);
      newQuestion = newQuestion.split(" ");
      oldQuestion = oldQuestion.split(" ");
      newQuestion = newQuestion.filter(x => !oldQuestion.includes(x));
      newQuestion.map(word => {
        if (word.startsWith("@", 0)) {
          this.title = "";
          $("#exampleModal").modal("show");
        }
      });
    },
    title: function(newQuestion, oldQuestion) {
      if (newQuestion.length >= 2 && newQuestion.length < 5) {
        this.search();
      }
    }
  },
  mounted() {
    this.Authuser = JSON.parse(this.Auser);
    this.search();
    this.getmessage();
    this.listen();
  },
  methods: {
    search() {
      axios
        .get("ap/get-users")
        .then(res => {
          this.results = res.data;
        })
        .catch(err => console.log(err));
    },
    getmessage() {
      axios
        .get("ap/get-message")
        .then(res => {
          this.messages = res.data;
        })
        .catch(err => console.log(err));
    },
    alerted(id) {
      this.results.filter(x => {
        if (x.name == id.target.value) {
          var name = x.name
            .toString()
            .toLowerCase()
            .replace(/\s+/g, "-") // Replace spaces with -
            .replace(/&/g, "-and-") // Replace & with 'and'
            .replace(/[^\w\-]+/g, "") // Remove all non-word characters
            .replace(/\-\-+/g, "-") // Replace multiple - with single -
            .replace(/^-+/, "") // Trim - from start of text
            .replace(/-+$/, "");
          if (!this.user_name.includes(name)) {
            this.query = this.query.concat(name, " ");
            this.user_name.push(name);
            this.user.push(x.id);
          }
          $("#exampleModal").modal("hide");
        }
      });
    },
    onsubmit() {
      let data = {
        id: this.user,
        meg: this.query
      };
      axios
        .post("ap/send-message", data)
        .then(res => {
          this.user = [];
          this.query = "";
          this.messages.push(res.data);
        })
        .catch(err => console.log(err));
    },
    check(only) {
      var y = only.map(word => word.substring(1));
      var array3 = this.user_name.filter(function(item, index) {
        return !y.includes(item);
      });
      if (array3.length != 0) {
        array3.map(x => {
          var z = this.user_name.indexOf(x);
          console.log(z);
          this.user_name.splice(z, 1);
          this.user.splice(z, 1);
        });
      }
    },
    listen() {
    //   console.log(this.Authuser);
      if (this.Authuser) {
        Echo.channel("home." + this.Authuser.id).listen(
          "NewMessage",
          message => {
              console.log(message)
            this.messages.push(message);
          }
        );
      }
    }
  }
};
</script>

<style scoped>
.flex-container {
  padding: 0;
  margin: 0;
  list-style: none;
  display: flex;
  align-items: center;
  justify-content: center;
  /* height: 300px; */
}
html {
  scroll-behavior: smooth;
}
.order-comment {
  height: 262px;
  overflow: auto;
  border: 8px;
  padding: 2%;
}
#btn {
  margin-top: 32px;
}
</style>
