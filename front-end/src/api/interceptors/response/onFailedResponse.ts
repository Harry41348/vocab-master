/**
 * Intercepts 401 errors to discard tokens.
 */

import { AxiosError, HttpStatusCode } from 'axios';

export const onFailedResponse = async (error: AxiosError) => {
  if (error.status === HttpStatusCode.Unauthorized) {
    localStorage.removeItem('accessToken');
  }

  return Promise.reject(error);
};
