import { AxiosError } from 'axios';

interface ApiErrorResponse {
  success: false;
  data: object | null;
  message: string | null;
}

export class ApiError implements ApiErrorResponse {
  public statusCode: number;
  public success: false;
  public data: object | null;
  public message: string | null;
  public originalError: Error;

  constructor(
    statusCode: number,
    data: object | null,
    message: string | null,
    originalError: Error,
  ) {
    this.statusCode = statusCode;
    this.success = false;
    this.data = data;
    this.message = message;
    this.originalError = originalError;
  }
}

export const parseApiError = (error: AxiosError): ApiErrorResponse => {
  const statusCode = error.response?.status || 500;

  if (statusCode < 500) {
    // Client error
    const response = error.response?.data as ApiErrorResponse;

    return new ApiError(statusCode, response.data, response.message, error);
  }

  // Internal Server Error
  return new ApiError(statusCode, null, error.message, error);
};
