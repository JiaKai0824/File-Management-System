<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-100">
        <md-card>
          <md-card-header data-background-color="blue">
            <h4 class="title">File Management</h4>
            <p class="category">Upload and manage files using Firebase</p>
          </md-card-header>

          <md-card-content>
            <div class="md-layout md-gutter">
              <div class="md-layout-item md-size-50">
                <input type="file" @change="handleFileUpload" />
              </div>
              <div class="md-layout-item md-size-50">
                <md-button class="md-raised md-primary" @click="uploadFile">Upload</md-button>
              </div>
            </div>
          </md-card-content>
        </md-card>
      </div>

      <!-- Stats Cards showing file type counts -->
      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25">
        <stats-card data-background-color="green">
          <template slot="header">
            <md-icon>photo_camera</md-icon>
          </template>

          <template slot="content">
            <p class="category">Images</p>
            <h3 class="title">{{ imageCount }}</h3>
          </template>
        </stats-card>
      </div>
      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25">
        <stats-card data-background-color="blue">
          <template slot="header">
            <md-icon>video_library</md-icon>
          </template>

          <template slot="content">
            <p class="category">Videos</p>
            <h3 class="title">{{ videoCount }}</h3>
          </template>
        </stats-card>
      </div>
      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25">
        <stats-card data-background-color="orange">
          <template slot="header">
            <md-icon>description</md-icon>
          </template>

          <template slot="content">
            <p class="category">Documents</p>
            <h3 class="title">{{ documentCount }}</h3>
          </template>
        </stats-card>
      </div>
      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25">
        <stats-card data-background-color="red">
          <template slot="header">
            <md-icon>folder</md-icon>
          </template>

          <template slot="content">
            <p class="category">Others</p>
            <h3 class="title">{{ othersCount }}</h3>
          </template>
        </stats-card>
      </div>

      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="orange">
            <h4 class="title">Managing Files</h4>
            <p class="category">A List of Uploaded Files</p>
          </md-card-header>
          <md-card-content>
            <simple-table table-header-color="orange" :files="filteredFiles"></simple-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import { StatsCard, SimpleTable } from "@/components";
import axios from "axios";

export default {
  components: {
    StatsCard,
    SimpleTable,
  },
  data() {
    return {
      selectedFile: null,
      message: "",
      files: [],
      imageCount: 0,
      videoCount: 0,
      documentCount: 0,
      othersCount: 0,
      searchQuery: "",
      sortKey: "",
      sortAsc: true,
    };
  },
  mounted() {
    this.fetchFiles();
  },
  computed: {
    filteredFiles() {
      let filtered = this.files.filter((file) => {
        const q = this.searchQuery.toLowerCase();
        return (
          file.name.toLowerCase().includes(q) ||
          file.type.toLowerCase().includes(q)
        );
      });

      if (this.sortKey) {
        filtered.sort((a, b) => {
          const aVal = a[this.sortKey];
          const bVal = b[this.sortKey];

          if (aVal < bVal) return this.sortAsc ? -1 : 1;
          if (aVal > bVal) return this.sortAsc ? 1 : -1;
          return 0;
        });
      }

      return filtered;
    },
  },
  methods: {
    handleFileUpload(event) {
      this.selectedFile = event.target.files[0];
    },
    async uploadFile() {
      if (!this.selectedFile) return;
      const formData = new FormData();
      formData.append("file", this.selectedFile);
      try {
        const response = await axios.post(
          "http://localhost/file-management-system/upload.php",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        );
        this.message = response.data.message;
        this.fetchFiles();
        this.selectedFile = null;
      } catch (error) {
        this.message = "Upload failed: " + error.message;
      }
    },
    
    async fetchFiles() {
      try {
        const response = await axios.get(
          "http://localhost/file-management-system/get_files.php"
        );
        this.files = response.data.files;
        this.updateFileTypeCounts();
      } catch (error) {
        this.message = "Failed to fetch files: " + error.message;
      }
    },

    updateFileTypeCounts() {
      // Reset the counts before updating
      this.imageCount = 0;
      this.videoCount = 0;
      this.documentCount = 0;
      this.othersCount = 0;

      // Count files by category
      this.files.forEach(file => {
        const fileType = file.type.toLowerCase();

        // Image Types
        if (fileType.includes("image")) {
          this.imageCount++;
        }
        // Video Types
        else if (fileType.includes("video")) {
          this.videoCount++;
        }
        // Document Types
        else if (fileType === "application/pdf" || fileType === "application/msword" || fileType === "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || fileType === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
          this.documentCount++;
        }
        // Others
        else {
          this.othersCount++;
        }
      });
    },
  },
};
</script>
