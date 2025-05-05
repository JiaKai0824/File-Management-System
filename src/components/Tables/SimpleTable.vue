<template>
  <div>
    <md-table v-model="users" :table-header-color="tableHeaderColor">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="ID">{{ item.id }}</md-table-cell>
        <md-table-cell md-label="Filename">{{ item.name }}</md-table-cell>
        <md-table-cell md-label="Type">{{ item.type }}</md-table-cell>
        <md-table-cell md-label="Size">{{ item.size }} KB</md-table-cell>
        <md-table-cell md-label="Date Uploaded">{{ item.uploaded_at }}</md-table-cell>
      </md-table-row>
       <!-- <td>
        <md-button class="md-icon-button md-accent" @click="deleteFile(file.id)">
        <md-icon>delete</md-icon>
        </md-button>
        <md-button class="md-icon-button" :href="file.url" target="_blank">
        <md-icon>cloud_download</md-icon>
        </md-button>
       </td> -->
    </md-table>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  name: "FileTable",
  data() {
    return {
      users: [],  // This will hold the file data
      tableHeaderColor: 'blue',  // Optional, customize table header color
    };
  },
  mounted() {
    this.fetchFiles();  // Fetch data when the component is mounted
  },
  methods: {
    async fetchFiles() {
      try {
        const response = await axios.get("http://localhost/file-management-system/get_files.php"); // Adjust your API endpoint
        // Check the response structure. If it's an object, access the array
        this.users = response.data.files || [];  // Assuming response.data.files contains the array of file objects
      } catch (error) {
        console.error("Error fetching files:", error);  // Handle any errors
      }
    },
    async deleteFile(fileId) {
      try {
        const response = await axios.post(
          "http://localhost/file-management-system/delete.php",
          {
            id: fileId,
          }
        );
        this.message = response.data.message;
        this.fetchFiles();
      } catch (error) {
        this.message = "Delete failed: " + error.message;
      }
    },
      sortBy(key) {
        if (this.sortKey === key) {
          this.sortAsc = !this.sortAsc;
        } else {
          this.sortKey = key;
          this.sortAsc = true;
        }
      },
      sortIcon(key) {
        if (this.sortKey !== key) return "";
        return this.sortAsc ? "↑" : "↓";
      },
  },
};
</script>
