import axios, { AxiosInstance } from 'axios';
import { applyInterceptors as applyAllInterceptors } from './interceptors/applyAllInterceptors';

const apiClient: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: false,
});

applyAllInterceptors(apiClient);

type ApiClient = typeof apiClient;

export { apiClient, type ApiClient };
