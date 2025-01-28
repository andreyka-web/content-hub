import { useAuthStore } from "@/store/authStore";

export const isAuthenticated = () => {
  const authStore = useAuthStore();
  return authStore.isLoggedIn;
}