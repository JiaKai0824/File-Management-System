<template>
  <div>
    <md-card>
      <md-card-header>
        <h4 class="title">Login</h4>
      </md-card-header>
      <md-card-content>
        <md-field>
          <label>Username</label>
          <md-input v-model="username" placeholder="Enter your username"></md-input>
        </md-field>
        <md-field>
          <label>Password</label>
          <md-input type="password" v-model="password" placeholder="Enter your password"></md-input>
        </md-field>
        <md-button class="md-raised md-primary" @click="login">Login</md-button>
      </md-card-content>
    </md-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      username: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost/file-management-system/login.php', {
          username: this.username,
          password: this.password
        });

        if (response.data.success) {
          // Save user data to localStorage
          localStorage.setItem("user", JSON.stringify(response.data.user));

          // Now safely navigate to dashboard
          this.$router.push('/dashboard').catch(() => {});
        } else {
          alert("Login failed: " + response.data.message);
        }
      } catch (error) {
        console.error("An error occurred while logging in:", error);
        alert("Network error. Please try again.");
      }
    }

  },
};
</script>
