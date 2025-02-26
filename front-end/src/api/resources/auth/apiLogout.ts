import { apiClient } from '@/api/apiClient';
import { ApiResponse } from '../types';
import { isAxiosError } from 'axios';
import { parseApiError } from '@/api/parseApiClientError';

export type ApiLogoutResponse = ApiResponse<string>;

/**
 *
 * @param request
 * @throws ApiError
 * @returns ApiLogoutResponse
 */
export const apiLogout = async (): Promise<ApiLogoutResponse> => {
  try {
    const response = await apiClient.post('/logout');

    return response.data;
  } catch (error: unknown) {
    if (isAxiosError(error)) {
      throw parseApiError(error);
    } else {
      // Non-API request error
      throw error;
    }
  }
};
