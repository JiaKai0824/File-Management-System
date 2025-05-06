<template>
  <div class="content">
    <div class="md-layout">
      
      <div class="md-layout-item md-size-100">
        <md-card>
          <md-card-header data-background-color="blue">
            <h4 class="title">Managing Files</h4>
            <p class="category">A List of Uploaded Files</p>
          </md-card-header>
          <md-card-content>
            <simple-table table-header-color="blue" :files="filteredFiles"></simple-table>
          </md-card-content>
        </md-card>
      </div>
      
    </div>
  </div>
</template>


<script>
import { SimpleTable} from "@/components";
import axios from "axios";

export default {
  components: {
    SimpleTable,
  },
  data() {
    return {
      selectedFile: null,
      files: [],
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
    async fetchFiles() {
      try {
        const response = await axios.get(
          "http://localhost/file-management-system/get_files.php"
        );
        this.files = response.data.files;

      } catch (error) {
        this.$notify({
          message: "Failed to fetch files: " + error.message,
          type: "error",
          position: "bottom-center",
          timeout: 3000,
        });
      }
    },
  
  },
};
</script>

