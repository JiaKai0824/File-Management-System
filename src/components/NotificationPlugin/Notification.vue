<template>
  <div
    @click="close()"
    data-notify="container"
    class="alert open alert-with-icon"
    role="alert"
    :class="[verticalAlign, horizontalAlign, alertType]"
    :style="customPosition"
    data-notify-position="bottom-center"
  >
    <button
      type="button"
      aria-hidden="true"
      class="close"
      data-notify="dismiss"
      @click="close"
    >
      ×
    </button>
    <i data-notify="icon" class="material-icons">{{ icon }}</i>
    <span data-notify="message" v-html="message"></span>
  </div>
</template>

<script>
export default {
  name: "notification",
  props: {
    message: String,
    icon: String,
    verticalAlign: {
      type: String,
      default: "top", // default top alignment
    },
    horizontalAlign: {
      type: String,
      default: "center", // default center alignment
    },
    type: {
      type: String,
      default: "info", // Default notification type
    },
    timestamp: {
      type: Date,
      default: () => new Date(),
    },
  },
  data() {
    return {
      elmHeight: 0, // Store height of the notification element
    };
  },
  computed: {
    alertType() {
      return `alert-${this.type}`; // dynamic class based on type
    },
    customPosition() {
      
      return {
        position: "fixed",
        top: "5%",
        left: "50%",
        transform: "translate(-50%, -50%)",
        zIndex: 100,
      };
      
    },
  },
  methods: {
    close() {
      this.$emit("on-close", this.timestamp);
    },
  },
  mounted() {
    this.elmHeight = this.$el.clientHeight;

    // Auto-close after 3 seconds
    setTimeout(() => {
      this.close();
    }, 3000);
  },
};
</script>

<style lang="scss" scoped>
.alert {
  z-index: 100;
  cursor: pointer;
  position: absolute;
  width: 41%;
  /* Centering notification by default */
  left: 0;
  right: 0;
  margin-left: auto;
  margin-right: auto;
  margin: 0 auto;

  &.center {
    margin: 0 auto;
  }
  &.left {
    left: 20px;
  }
  &.right {
    right: 20px;
  }

  /* You can add your custom styles for the close button and icons here */
  .close {
    font-size: 20px;
    cursor: pointer;
  }
  .material-icons {
    font-size: 24px;
  }
  
}
</style>
