import Vue from "vue";
import Snotify, { SnotifyPosition } from "vue-snotify";

const SnotifyOptions = {
  toast: {
    position: SnotifyPosition.centerCenter,
  },
};

Vue.component("tab", require("./components/tab.vue").default);
Vue.use(Snotify, SnotifyOptions);
const app = new Vue({
  el: "#app",
});
