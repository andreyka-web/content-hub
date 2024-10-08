import { useAuthStore } from "@/store/authStore";

/**
 *  Wrapper for fetch api to handle headers and keep base api url in one place 
 */
export default function fetchData(path, data, method = "POST") {

  const authStore = useAuthStore()
  // todo: put url in env
  const url = `http://0.0.0.0:8080/api/${path}`;

  const headers = new Headers()
  headers.append("Content-Type", "application/json");
  headers.append("Accept", "application/json")

  if(authStore.token) {
    headers.append("Authorization", ` Bearer ${authStore.token}`)
  }
 
  return fetch(url, {
    method: method,
    headers: headers,
    body: method !== "GET" ? JSON.stringify(data) : null,
  })
  .then(res => res.json())
  .then(json => {
    return json
  })
  .catch(console.error)   
}