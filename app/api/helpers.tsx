import axios from 'axios';

const axiosInstance = axios.create({
  //baseURL: 'http://192.168.1.75:8000',
  //baseURL: 'http://192.168.1.113:8000/api/',
  //baseURL: 'http://10.0.0.15:8000',
  //baseURL: 'http://10.0.0.38:8000',
  //baseURL: 'http://ratatouille.japi.space/',
  baseURL: 'https://marabunta.japi.space/api/',
  withCredentials: true,
});

async function getToken() {
  try {
    const response = await axiosInstance.get('token/session/get');
    return response.data;
  } catch (error) {
    console.error('Error al realizar la solicitud:', error);
    throw error;
  }
}

export {axiosInstance, getToken};

