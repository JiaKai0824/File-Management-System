<template>
  <div class="wrapper" :class="{ 'nav-open': $sidebar.showSidebar }">
    <notifications></notifications>

    <side-bar>

      <sidebar-link to="/dashboard">
        <md-icon>dashboard</md-icon>
        <p>Dashboard</p>
      </sidebar-link>
      <sidebar-link to="/user">
        <md-icon>person</md-icon>
        <p>User Profile</p>
      </sidebar-link>
      <sidebar-link to="/table">
        <md-icon>content_paste</md-icon>
        <p>File list</p>
      </sidebar-link>
      <md-button class="md-raised md-warn logout-button" @click="logout">
        Logout
      </md-button>
    </side-bar>

    <div class="main-panel">
      <dashboard-content> </dashboard-content>
    </div>
  </div>
</template>

<script>


import DashboardContent from "./Content.vue";



export default {
  components: {
    DashboardContent,
  },
  data() {
    return {
      sidebarBackground: "green",
    };
  },
  methods: {
  logout() {
      localStorage.removeItem("user");
      this.$router.push('/login').catch(() => {});
    }
  },
};
</script>
<style scoped>
.logout-button {
  margin-top: 20px; 
  margin-left: 95px;
  width: 10%;
  position: fixed;    
  bottom: 20px;
  transform: translateX(-50%); 
  background-color: red!important;
}
</style>
