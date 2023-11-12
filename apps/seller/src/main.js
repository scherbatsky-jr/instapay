import App from './App.vue'
import configPlugin from "./config";
import { createApp } from 'vue'
import { createPinia } from "pinia";
import { ErrorMessage, Field, Form } from "./vee-validate";
import { Icon } from "@iconify/vue";
import Notifications from "@kyvg/vue3-notification";
import router from "./router";

import './style.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(configPlugin);
app.use(Notifications);

app.component("Icon", Icon);
app.component("ErrorMessage", ErrorMessage);
app.component("Field", Field);
app.component("Form", Form);

app.mount("#app");
