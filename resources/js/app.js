import './bootstrap';
import '../css/app.css'
import {createApp} from "vue";
import app from "./layout/App.vue";
import vuetify from "./vuetify.js";
import "vuetify/dist/vuetify.min.css";
import "vuetify/dist/vuetify.min.js";

createApp(app)
    .use(vuetify)
    .mount("#app");