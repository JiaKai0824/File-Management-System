<template>
    <div>
      <a :href="file.url" target="_blank">{{ file.name }}</a>
      <button @click="deleteFile">Delete</button>
      <!-- Implement edit functionality as needed -->
    </div>
  </template>
  
  <script setup>
  import { db, storage } from '../firebase';
  import { doc, deleteDoc } from 'firebase/firestore';
  import { ref as storageRef, deleteObject } from 'firebase/storage';
  
  defineProps(['file']);
  const emit = defineEmits(['refresh']);
  
  const deleteFile = async () => {
    await deleteDoc(doc(db, 'files', file.id));
    const storageReference = storageRef(storage, `uploads/${file.name}`);
    await deleteObject(storageReference);
    alert('File deleted successfully!');
    emit('refresh');
  };
  </script>
  