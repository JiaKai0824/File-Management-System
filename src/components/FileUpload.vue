<template>
    <div>
      <h2>Upload File</h2>
      <input type="file" @change="handleFileChange" />
      <input v-model="fileName" placeholder="Enter file name" />
      <button @click="uploadFile">Upload</button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { db, storage } from '../firebase';
  import { collection, addDoc, serverTimestamp } from 'firebase/firestore';
  import { ref as storageRef, uploadBytes, getDownloadURL } from 'firebase/storage';
  
  const file = ref(null);
  const fileName = ref('');
  
  const handleFileChange = (e) => {
    file.value = e.target.files[0];
  };
  
  const uploadFile = async () => {
    if (!file.value || !fileName.value) return;
  
    const storageReference = storageRef(storage, `uploads/${file.value.name}`);
    await uploadBytes(storageReference, file.value);
    const url = await getDownloadURL(storageReference);
  
    await addDoc(collection(db, 'files'), {
      name: fileName.value,
      url,
      createdAt: serverTimestamp()
    });
  
    file.value = null;
    fileName.value = '';
    alert('File uploaded successfully!');
    emit('fileUploaded');
  };
  </script>
  