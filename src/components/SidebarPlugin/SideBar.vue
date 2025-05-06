<template>
  <div
    class="sidebar"
    :style="sidebarStyle"
  >
    <div class="logo" style="padding-top: 60px">
      <a href="#" class="simple-text logo-mini">
        <div>
          <p></p>
        </div>
      </a>

      <a href="#" target="_blank" class="simple-text logo-normal">
        <!-- Logo content here -->
      </a>
    </div>
    <div class="sidebar-wrapper">
      <slot name="content"></slot>
      <md-list class="nav">
        <slot>
          <sidebar-link
            v-for="(link, index) in sidebarLinks"
            :key="link.name + index"
            :to="link.path"
            :link="link"
          >
          </sidebar-link>
        </slot>
      </md-list>
    </div>
  </div>
</template>

<script>
import SidebarLink from "./SidebarLink.vue";

export default {
  components: {
    SidebarLink,
  },
  props: {
   
    autoClose: {
      type: Boolean,
      default: true,
    },
  },
  provide() {
    return {
      autoClose: this.autoClose,
    };
  },
  computed: {
    sidebarStyle() {
      return {
        backgroundColor: this.sidebarItemColor,
      };
    },
  },
};
</script>

<style>
.sidebar {
  width: 250px;
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  background-color: var(--sidebar-background-color, #085caa); 
}
</style>
