import { AxiosInstance } from 'axios';
import { applyAccessTokenToRequest } from './request/applyAccessTokenToRequest';
import { onFailedResponse } from './response/onFailedResponse';

export const applyInterceptors = (apiClient: AxiosInstance) => {
  // Request interceptors
  apiClient.interceptors.request.use(applyAccessTokenToRequest, Promise.reject);

  // Response interceptors
  apiClient.interceptors.response.use((response) => response, onFailedResponse);
};
