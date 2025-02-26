import { getToken } from '@/utils/authUtil';
import { AxiosRequestHeaders, InternalAxiosRequestConfig } from 'axios';

/**
 * Attempts to fetch and inject the access token for the currently authenticated user.
 */
export const applyAccessTokenToRequest = async (
  config: InternalAxiosRequestConfig,
): Promise<InternalAxiosRequestConfig> => {
  const accessToken = getToken();

  if (accessToken) {
    if (!config.headers) {
      config.headers = {} as AxiosRequestHeaders;
    }

    config.headers.Authorization = `Bearer ${accessToken}`;
  }

  return config;
};
