<template>
  <div>
    <md-field>
      <label>Search</label>
      <md-input v-model="searchQuery" placeholder="Search by filename..."></md-input>
    </md-field>
    <md-table :value="filteredUsers" :table-header-color="tableHeaderColor">
      <md-table-row>
        <md-table-head md-label>No</md-table-head>
        <md-table-head md-label>Name</md-table-head>
        <md-table-head md-label>File Type</md-table-head>
        <md-table-head md-label>Size</md-table-head>
        <md-table-head md-label>Uploaded Date</md-table-head>
        <md-table-head md-label>Actions</md-table-head>
      </md-table-row>
      <md-table-row v-for="(item, index) in filteredUsers" :key="item.id">
        <md-table-cell>{{ index + 1 }}</md-table-cell>
        <md-table-cell>{{ simplifyFileName(item.name) }}</md-table-cell>
        <md-table-cell>{{ getFileExtension(item.name) }}</md-table-cell>
        <md-table-cell>{{ item.size }} KB</md-table-cell>
        <md-table-cell>{{ item.uploaded_at }}</md-table-cell>
        <md-table-cell>
          <!-- Only show preview if the user has 'read' or 'write' permission -->
          <md-button class="md-icon-button md-primary" @click="previewFile(item)" >
            <md-icon>visibility</md-icon>
          </md-button>
          <!-- Only show download button if user has 'read' or 'write' permission -->
          <md-button class="md-icon-button md-accent" @click="downloadFile(item.id)">
            <md-icon>download</md-icon>
          </md-button>
          <!-- Only show delete button if user has 'write' permission -->
          <md-button v-if="isAdmin" class="md-icon-button md-accent" @click="deleteFile(item.id)">
            <md-icon>delete</md-icon>
          </md-button>
          
          
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "FileTable",
  data() {
    return {
      users: [],
      tableHeaderColor: "black",
      searchQuery: "",
      role: 0, 
      sortKey: "id",
      sortAsc: true,
    };
  },
  computed: {
    filteredUsers() {
      const filtered = this.users.filter(file =>
        file.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );

      return filtered.sort((a, b) => {
        let result = 0;
        if (a[this.sortKey] < b[this.sortKey]) result = -1;
        if (a[this.sortKey] > b[this.sortKey]) result = 1;
        return this.sortAsc ? result : -result;
      });
    },
    isAdmin() {
      return this.role === 1;
    },
  },
  mounted() {
    this.fetchFiles();
    const user = JSON.parse(localStorage.getItem("user"));
    if (user) {
      this.id = user.id;
      this.username = user.username;
      this.email = user.email;
      this.role = user.role_id; // Assuming this is an integer role_id
    }
  },
  methods: {
    async fetchFiles() {
      try {
        const response = await axios.get("http://localhost/file-management-system/get_files.php");
        this.users = response.data.files || [];
      } catch (error) {
        console.error("Error fetching files:", error);
      }
    },
   
    async deleteFile(fileId) {
      const confirmed = confirm("Are you sure you want to delete this file?");
      if (!confirmed) return;

      try {
        const response = await axios.post("http://localhost/file-management-system/delete.php", {
          id: fileId,
        });
        this.$notify({
          message: response.data.message || "File deleted successfully",
          type: "success",
          position: "bottom-center",
          timeout: 3000,
        });

        this.fetchFiles();
      } catch (error) {
        this.$notify({
          message: "Failed to delete the file",
          type: "error",
          position: "bottom-center",
          timeout: 3000,
        });
      }
    },
    downloadFile(fileId) {
      const downloadUrl = `http://localhost/file-management-system/download.php?id=${fileId}`;
      window.location.href = downloadUrl;
    },
    previewFile(file) {
      const previewUrl = `http://localhost/file-management-system/preview_file.php?id=${file.id}`;
      window.open(previewUrl, '_blank');
    },
    simplifyFileName(fileName) {
      const nameWithoutExtension = fileName.split('.').slice(0, -1).join('.');
      return nameWithoutExtension.length > 20 ? nameWithoutExtension.substring(0, 20) + '...' : nameWithoutExtension;
    },
    getFileExtension(fileName) {
      const fileExtension = fileName.split('.').pop().toLowerCase();
      return `.${fileExtension}`;
    },
  },
};
</script>
