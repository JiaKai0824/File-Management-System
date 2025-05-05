<template>
    <div>
      <h2>Uploaded Files</h2>
      <ul>
        <li v-for="file in files" :key="file.id">
          <FileItem :file="file" @refresh="emit('refresh')" />
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import FileItem from './FileItem.vue';
  defineProps(['files']);
  </script>
  