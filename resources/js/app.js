//import './bootstrap';
/*import {bootstrap} from './bootstrap.js'*/
import { createApp } from 'vue';
import store from "./store/index.js";
import App from './App.vue';
import router from "./router.js";
/*import '../css/bootstrap.min.css';
import '../css/bootstrap-icons.css';*/
import '../css/app.css';
import '../css/dashboard.css';
import mixins from "./utils/mixins.js";

const app = createApp(App);

app.use(store);
app.use(router);
app.mixin(mixins);


app.mount('#app')
