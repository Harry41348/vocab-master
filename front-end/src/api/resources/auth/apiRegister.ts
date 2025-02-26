import { apiClient } from '@/api/apiClient';
import { ApiResponse, User } from '../types';
import { isAxiosError } from 'axios';
import { parseApiValidationError } from '@/api/parseApiValidationError';

export type ApiRegisterResponse = ApiResponse<{
  access_token: string;
  user: User;
}>;

export interface ApiRegisterRequest {
  name: string;
  email: string;
  password: string;
}

/**
 *
 * @param request
 * @throws ApiError
 * @returns ApiRegisterResponse
 */
export const apiRegister = async (
  request: ApiRegisterRequest,
): Promise<ApiRegisterResponse> => {
  try {
    const response = await apiClient.post('/register', request);

    return response.data;
  } catch (error: unknown) {
    if (isAxiosError(error)) {
      throw parseApiValidationError(error);
    } else {
      // Non-API request error
      throw error;
    }
  }
};
