import { initializeApp } from 'firebase/app';
import { getFirestore } from 'firebase/firestore';
import { getStorage } from 'firebase/storage';

const firebaseConfig = {
    apiKey: "AIzaSyCBj7g9fvqI0qZCnmyzBs64rf9c3ioVbrM",
    authDomain: "file-management-system-615ff.firebaseapp.com",
    projectId: "file-management-system-615ff",
    storageBucket: "file-management-system-615ff.firebasestorage.app",
    messagingSenderId: "648411747684",
    appId: "1:648411747684:web:ed07f1767f3b879f07d671",
    measurementId: "G-XW048NFB1W"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const storage = getStorage(app);

export { db, storage };