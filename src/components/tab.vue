<template>
  <div>
    <div id="post-body-content">
      <div class="meta-box-sortables ui-sortable">
        <div class="postbox">
          <br />
          <strong>Custom Text</strong>
          <div class="inside">
            <p v-if="!editor">
              {{ custom_txt }}
            </p>
          </div>
          <div :class="spinner"></div>
          <div class="form">
            <textarea
              v-model="custom_txt"
              v-if="editor"
              cols="80"
              rows="10"
              class="large-text"
            >
.large-text</textarea
            ><br />
            <button @click="btnClick" type="button" class="button-primary">
              {{ btn_txt }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <vue-snotify></vue-snotify>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "App",
  props: ["product_id"],
  data() {
    return {
      editor: false,
      custom_txt: "",
      btn_txt: "Edit",
      spinner: "spinner",
    };
  },
  methods: {
    btnClick() {
      if (this.editor === false) {
        this.editor = true;
        this.btn_txt = "update";
      } else if (this.editor === true) {
        this.spinner = "spinner is-active";
        axios
          .post("/wp-json/wctb/v1/wctb-post-data", {
            custom_txt: this.custom_txt,
            product_id: this.product_id,
          })
          .then((response) => {
            this.$snotify.success("Data Updated !!", "Great!");
            console.log(response.data);
          })
          .catch((error) => {
            console.log(error);
          });
        this.spinner = "spinner";
      }
    },
  },
  mounted() {
    axios
      .post("/wp-json/wctb/v1/get-wctb-post-data", {
        product_id: this.product_id,
      })
      .then((response) => {
        this.custom_txt = response.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style scoped>
strong {
  padding: 8px;
  font-size: 16px;
  font-weight: 700;
}
textarea {
  margin: 2px;
  margin-top: 13px;
}
button {
  margin: 12px !important;
}
</style>
