import { useAuthStore } from "@/store/authStore";

const api_url = `http://localhost:8080/api/`;
/**
 *  Wrapper for fetch api to handle headers and keep base api url in one place 
 */
export default function fetchData(path, data = null, method = "POST", formData = false) {
  return fetch(`${api_url}${path}`, {
    method: method,
    headers: getHeaders(formData ? null : "application/json"),
    body: !(["GET", "DELETE"].includes(method)) ? JSON.stringify(data) : null,
  })
  .then(res => res.json()) 
  .catch(console.error)
}

// return object url 
export async function fetchFile(path) {
  return await fetch(`${api_url}${path}`, {
    headers: getHeaders()
  })
    .then(res => res.blob())
    .then(blob => URL.createObjectURL(blob))
    .catch(console.log);
}


export function getHeaders(contentType = null) {
  const authStore = useAuthStore()
  const headers = new Headers()

  // form data should not be set manually
  if(contentType){
    headers.append("Content-Type", contentType);
  }
  // all API responses are expected to be in json format
  headers.append("Accept", "application/json");

  if(authStore.token) {
    headers.append("Authorization", ` Bearer ${authStore.token}`)
  } 

  return headers;
}
 