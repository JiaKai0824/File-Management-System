<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-100">
        <md-card>
          <md-card-header data-background-color="blue">
            <h4 class="title">File Management</h4>
            <p class="category">Upload and manage files</p>
            </md-card-header>
            <md-card-content>
            <div class="md-layout md-gutter file-upload-wrapper">
              <div class="md-layout-item md-size-100 md-small-size-100">
                <div class="upload-area" @click="$refs.fileInput.click()">
                  <md-icon class="upload-icon">cloud_upload</md-icon>
                  <p class="upload-text">
                    {{ selectedFile ? selectedFile.name : "Click to choose a file" }}
                  </p>
                  <input
                    type="file"
                    @change="handleFileUpload"
                    ref="fileInput"
                    class="hidden-file-input"
                  />
                </div>
              </div>
              <div class="md-layout-item md-size-100 md-small-size-100 button-wrapper" >
                <md-button
                  class="md-raised md-primary upload-btn"
                  @click="uploadFile"
                  :disabled="!selectedFile"
                >
                  Upload
                </md-button>
              </div>
            </div>
          </md-card-content>
        </md-card>
      </div>

      <!-- Stats Cards -->
      <div
        class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25"
        v-for="(count, index) in statCards"
        :key="index"
      >
        <stats-card :data-background-color="count.color">
          <template slot="header">
            <md-icon>{{ count.icon }}</md-icon>
          </template>
          <template slot="content">
            <p class="category">{{ count.label }}</p>
            <h3 class="title">{{ count.value }}</h3>
          </template>
        </stats-card>
      </div>


    </div>
  </div>
</template>

<script>
import { StatsCard } from "@/components";
import axios from "axios";

export default {
  components: {
    StatsCard,

  },
  data() {
    return {
      selectedFile: null,
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
    statCards() {
      return [
        {
          label: "Images",
          value: this.imageCount,
          color: "green",
          icon: "photo_camera",
        },
        {
          label: "Videos",
          value: this.videoCount,
          color: "blue",
          icon: "video_library",
        },
        {
          label: "Documents",
          value: this.documentCount,
          color: "orange",
          icon: "description",
        },
        {
          label: "Others",
          value: this.othersCount,
          color: "red",
          icon: "folder",
        },
      ];
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
            headers: { "Content-Type": "multipart/form-data" },
          }
        );

        this.$notify({
          message: "Upload Success",
          type: "success",
          position: "bottom-center",
          timeout: 3000,
        });

        this.fetchFiles();
      } catch (error) {
        this.$notify({
          message: "Upload failed: " + error.message,
          type: "error",
          position: "bottom-center",
          timeout: 3000,
        });
      } finally {
        // Clear file input and selection
        this.selectedFile = null;
        this.$refs.fileInput.value = null;
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
        this.$notify({
          message: "Failed to fetch files: " + error.message,
          type: "error",
          position: "bottom-center",
          timeout: 3000,
        });
      }
    },
    updateFileTypeCounts() {
      this.imageCount = 0;
      this.videoCount = 0;
      this.documentCount = 0;
      this.othersCount = 0;

      this.files.forEach((file) => {
        const fileType = file.type.toLowerCase();
        if (fileType.includes("image")) {
          this.imageCount++;
        } else if (fileType.includes("video")) {
          this.videoCount++;
        } else if (
          fileType === "application/pdf" ||
          fileType === "application/msword" ||
          fileType ===
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ||
          fileType ===
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        ) {
          this.documentCount++;
        } else {
          this.othersCount++;
        }
      });
    },
  },
};
</script>
<style scoped>
.file-upload-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.upload-area {
  width: 100%;
  padding: 1.5rem;
  border: 2px dashed #2196f3;
  border-radius: 8px;
  background-color: #f5faff;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.upload-area:hover {
  background-color: #e3f2fd;
}

.upload-icon {
  font-size: 36px;
  color: #2196f3;
}

.upload-text {
  font-size: 1rem;
  margin-top: 0.5rem;
  color: #555;
  word-break: break-word;
}

.hidden-file-input {
  display: none;
}

.button-wrapper {
  display: flex;
  justify-content: flex-end;
}

.upload-btn {
  min-width: 100%;
}
</style>
