<template>
    <div class="container">
       <div class="input-group flex-nowrap">
      <input
        type="text"
        v-model="query"
        class="form-control"
        style="width: 76%;"
        placeholder="Add Comment"

      />
    </div>
    {{ query }}

    </div>
</template>

<script>
    export default {
       data() {
    return {
      query: "",
      results: [],
      title: "",
      user: [],
      user_name: []
    };
  },
    }
</script>
