import { useAuthStore } from "@/store/authStore";

export const api_url = `http://localhost:8080/api/`;
/**
 *  Wrapper for fetch api to handle headers and keep base api url in one place 
 */
export default function fetchData(path, data = null, method = "POST", formData = false) {
  return fetch(`${api_url}${path}`, {
    method: method,
    headers: getHeaders(formData ? null : "application/json"),
    body: !(["GET", "DELETE"].includes(method)) ? JSON.stringify(data) : null,
  })
    .then(res => {
      if (!res.ok) {
        throw new Error(res.statusText)
      }
      const contentType = res.headers.get("content-type");
      if (contentType && contentType.includes("application/json")) {
        return res.json()
      } else {
        throw new Error("Response is not in JSON format")
      }
    })
    .catch((error) => {
      console.error('Fetch API Error:', error);
      throw error;
    });
}

export function fetchData2(path, data = null, method = "POST", formData = false) {

  const headers = getHeaders()

  fetch(`${api_url}${path}`, {
    headers: headers,
    body: data,
    method: method,
    // mode: 'no-cors',
  })
    .then(res => res.json())
    .then(json => {
      if (json.hasOwnProperty('errors')) {
        this.message = json.message
      }
      else {
        this.folder = null;
        this.message = null;
        this.$emit('file-saved');
      }
    })
    .catch(e => console.log);



 
  // //  const headers = getHeaders(formData ? null : "application/json")
  // //  console.info('Headers: ', JSON.stringify(Object.fromEntries([...headers])));
  // console.log('Data: ', data)
  // return fetch(`${api_url}${path}`, {
  //   method: method,

  //   headers: headers,
  //   //    headers: getHeaders(formData ? "multipart/form-data" : "application/json"),
  //   body: !(["GET", "DELETE"].includes(method)) ? JSON.stringify(data) : null,
  // })
  //   .then(res => {
  //     if (!res.ok) {
  //       throw new Error(res.statusText)
  //     }
  //     const contentType = res.headers.get("content-type");
  //     if (contentType && contentType.includes("application/json")) {
  //       return res.json()
  //     } else {
  //       throw new Error("Response is not in JSON format")
  //     }
  //   })
  //   .catch((error) => {
  //     console.error('Fetch API Error:', error);
  //     throw error;
  //   });
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
  if (contentType) {
    headers.append("Content-Type", contentType);
  }
  // all API responses are expected to be in json format
  headers.append("Accept", "application/json");

  if (authStore.token) {
    headers.append("Authorization", ` Bearer ${authStore.token}`)
  }

  return headers;
}
