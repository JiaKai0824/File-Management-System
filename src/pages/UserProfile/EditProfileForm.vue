<template>
  <form @submit.prevent="updateProfile">
    <md-card>
      <md-card-header :data-background-color="dataBackgroundColor">
        <h4 class="title">Welcome {{ username }}</h4>
        <p class="category">Update your account details</p>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>Username</label>
              <md-input v-model="username" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>Email</label>
              <md-input v-model="email" type="email"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>New Password</label>
              <md-input
                v-model="password"
                type="password"
                placeholder="Leave blank to keep current"
              ></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-size-100 text-right">
            <md-button type="submit" class="md-raised md-success">Update Profile</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>

    <!-- Admin-only section to create users -->
    <md-card v-if="isAdmin" class="mt-4">
      <md-card-header data-background-color="purple">
        <h4 class="title">Admin: Create New User</h4>
        <p class="category">Add a new user account</p>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>New Username</label>
              <md-input v-model="newUsername" type="text" />
            </md-field>
          </div>
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>New Email</label>
              <md-input v-model="newEmail" type="email" />
            </md-field>
          </div>
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>New Password</label>
              <md-input v-model="newPassword" type="password" />
            </md-field>
          </div>
          <div class="md-layout-item md-size-50">
            <md-field>
              <label>User Role</label>
              <md-select v-model="newRole">
                <md-option :value="1">Admin</md-option>
                <md-option :value="3">User</md-option>
              </md-select>
            </md-field>
          </div>
          <div class="md-layout-item md-size-100 text-right">
            <md-button class="md-raised md-primary" @click="createUser">Create User</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>
  </form>
</template>

<script>
import axios from "axios";

export default {
  name: "edit-profile-form",
  props: {
    dataBackgroundColor: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      id: null,
      username: "",
      email: "",
      password: "",
      role: 0, 
      // New user form fields
      newUsername: "",
      newEmail: "",
      newPassword: "",
      newRole: 3, // Default new user role to 'User' (role 3)
    };
  },
  computed: {

    isAdmin() {
      return this.role === 1; // 1 is admin
    },
  },
  mounted() {
    const user = JSON.parse(localStorage.getItem("user"));
    if (user) {
      this.id = user.id;
      this.username = user.username;
      this.email = user.email;
      this.role = user.role_id; // Assuming this is an integer role_id
    }
  },
  methods: {
    async updateProfile() {
      try {
        const response = await axios.post("http://localhost/file-management-system/update_profile.php", {
          id: this.id,
          username: this.username,
          email: this.email,
          password: this.password,
        });

        if (response.data.success) {
          alert("Profile updated successfully");
        } else {
          alert("Update failed: " + response.data.message);
        }
      } catch (error) {
        console.error("Error updating profile:", error);
      }
    },
    async createUser() {
      if (!this.newUsername || !this.newEmail || !this.newPassword) {
        alert("Please fill in all required fields.");
        return;
      }

      try {
        const response = await axios.post("http://localhost/file-management-system/create_user.php", {
          username: this.newUsername,
          email: this.newEmail,
          password: this.newPassword,
          role: this.newRole, // Use role 1 (Admin) or 3 (User)
        });

        if (response.data.success) {
          alert("User created successfully.");
          // Clear form
          this.newUsername = "";
          this.newEmail = "";
          this.newPassword = "";
          this.newRole = 3; // Default to 'User' for new users
        } else {
          alert("User creation failed: " + response.data.message);
        }
      } catch (error) {
        console.error("Error creating user:", error);
        alert("An error occurred.");
      }
    },
  },
};
</script>
