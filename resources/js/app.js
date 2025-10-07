import { createApp } from "vue";
import App from "./src/App.vue";
import router from "./src/router";
import storeInstance from "./src/store/store";
import { Vue3ProgressPlugin } from "@marcoschulte/vue3-progress";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { dom, library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { faLock, faEnvelope } from "@fortawesome/free-solid-svg-icons";
import { faFacebook, faGooglePlus } from "@fortawesome/free-brands-svg-icons";
import VueSweetalert2 from "vue-sweetalert2";
import Toaster from "@meforma/vue-toaster";
import { setupCalendar, Calendar, DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import $ from 'jquery';

import "admin-lte/plugins/jquery/jquery.min.js";
import "admin-lte/plugins/jquery-ui/jquery-ui.min.js"
import "admin-lte/dist/js/adminlte.min.js";
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js";
import "selectize/dist/js/standalone/selectize.min.js";

import "@marcoschulte/vue3-progress/dist/index.css";
import "admin-lte/dist/css/adminlte.min.css";
import "admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css";
import "admin-lte/plugins/dropzone/min/dropzone.min.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "selectize-bootstrap4-theme/dist/css/selectize.bootstrap4.css";
import "selectize/dist/css/selectize.css";

library.add(fas, fab, far, faLock, faEnvelope, faFacebook, faGooglePlus);
dom.watch();

const app = createApp(App);
app.config.globalProperties.$ = $;
app.config.globalProperties.jQuery = $;
app.component("font-awesome-icon", FontAwesomeIcon);
app.use(VueSweetalert2);
app.use(Toaster);
app.use(Vue3ProgressPlugin);
app.use(router);
app.use(storeInstance);
app.use(setupCalendar, {})
app.component('VCalendar', Calendar)
app.component('VDatePicker', DatePicker)
app.mount("#app");
