import Firebase from 'firebase'

let config = {
  apiKey: "AIzaSyCW-yHDVzJT7IgK6exI1AElYZ85BKiKeBc",
  authDomain: "modern-jukebox.firebaseapp.com",
  databaseURL: "https://modern-jukebox.firebaseio.com",
  projectId: "modern-jukebox",
  storageBucket: "",
  messagingSenderId: "560034994179"
};

const app = Firebase.initializeApp(config)
export const db = app.database()
