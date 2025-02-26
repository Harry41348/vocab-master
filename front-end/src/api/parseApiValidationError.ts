import { AxiosError } from 'axios';
import { ValidationErrors } from './resources/types';

interface ApiValidationErrorResponse {
  status: 'error';
  message: string | null;
  errors: ValidationErrors | null;
}

export class ApiValidationError implements ApiValidationErrorResponse {
  public statusCode: number;
  public status: 'error';
  public message: string | null;
  public errors: ValidationErrors | null;
  public originalError: Error;

  constructor(
    statusCode: number,
    message: string | null,
    errors: ValidationErrors | null,
    originalError: Error,
  ) {
    this.statusCode = statusCode;
    this.status = 'error';
    this.message = message;
    this.errors = errors;
    this.originalError = originalError;
  }
}

export const parseApiValidationError = (
  error: AxiosError,
): ApiValidationErrorResponse => {
  const statusCode = error.response?.status || 422;

  // Client error
  const response = error.response?.data as ApiValidationErrorResponse;

  return new ApiValidationError(
    statusCode,
    response.message,
    response.errors,
    error,
  );
};
