import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App';

// Import routes from your routes file
import routes from './routes/routes';

// Import Vue Material and other plugins
import GlobalComponents from './globalComponents';
import GlobalDirectives from './globalDirectives';
import Notifications from './components/NotificationPlugin';
import MaterialDashboard from './material-dashboard';
import Chartist from 'chartist';

// Configure Vue Router
const router = new VueRouter({
  routes, // Routes configuration
  linkExactActiveClass: 'nav-item active',
});

Vue.prototype.$Chartist = Chartist;

Vue.use(VueRouter);
Vue.use(MaterialDashboard);
Vue.use(GlobalComponents);
Vue.use(GlobalDirectives);
Vue.use(Notifications);

// Create and mount the Vue instance
new Vue({
  el: '#app',
  render: (h) => h(App),
  router, // Inject the router into the Vue instance
  data: {
    Chartist: Chartist,
  },
});
