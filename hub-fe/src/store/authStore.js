// stores/authStore.js
import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isLoggedIn: !!localStorage.getItem('authToken'), // Initialized from localStorage and convert to boolean 
    token: localStorage.getItem('authToken') || null,
    username: localStorage.getItem('username') || null,
  }),
  actions: {
    login(token, username) {
      this.token = token;
      this.username = username;
      this.isLoggedIn = true;
      localStorage.setItem('authToken', token);
      localStorage.setItem('username', username);
    },
    logout() {
      this.token = null;
      this.username = null;
      this.isLoggedIn = false;
      localStorage.removeItem('authToken');
    },
    checkLoginStatus() {
      const token = localStorage.getItem('authToken');
      const username = localStorage.getItem('username');
      if (token && username) {
        this.token = token;
        this.username = username;
        this.isLoggedIn = true;
      } else {
        this.isLoggedIn = false;
      }
    }
  }
});
