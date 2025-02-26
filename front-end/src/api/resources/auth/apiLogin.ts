import { apiClient } from '@/api/apiClient';
import { ApiResponse, User } from '../types';
import { isAxiosError } from 'axios';
import { parseApiError } from '@/api/parseApiClientError';

export type ApiLoginResponse = ApiResponse<{
  access_token: string;
  user: User;
}>;

export interface ApiLoginRequest {
  email: string;
  password: string;
}

/**
 *
 * @param request
 * @throws ApiError
 * @returns ApiLoginResponse
 */
export const apiLogin = async (
  request: ApiLoginRequest,
): Promise<ApiLoginResponse> => {
  try {
    const response = await apiClient.post('/login', request);

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
