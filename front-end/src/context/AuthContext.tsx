import { User } from '@/api/resources/types';
import { createContext, PropsWithChildren, useState } from 'react';
import { removeToken, setToken as setLocalToken } from '@/utils/authUtil';
import { apiLogin, ApiLoginRequest } from '@/api/resources/auth/apiLogin';
import { ApiError } from '@/api/parseApiClientError';
import {
  apiRegister,
  ApiRegisterRequest,
} from '@/api/resources/auth/apiRegister';
import { ApiValidationError } from '@/api/parseApiValidationError';
import { apiLogout } from '@/api/resources/auth/apiLogout';

interface AuthContextType {
  token: string | null;
  user: User | null;
  loginAction: (input: ApiLoginRequest) => Promise<number>;
  registerAction: (
    input: ApiRegisterRequest,
  ) => Promise<number | ApiValidationError>;
  logout: () => void;
}

export const AuthContext = createContext<AuthContextType | undefined>(
  undefined,
);

const AuthProvider = ({ children }: PropsWithChildren) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState(localStorage.getItem('accessToken'));

  // Attempts to login, returns the status code
  const loginAction = async (input: ApiLoginRequest): Promise<number> => {
    try {
      const response = await apiLogin(input);

      setUser(response.data.user);
      setToken(response.data['access_token']);
      setLocalToken(response.data['access_token']);

      return 200;
    } catch (error: unknown) {
      if (error instanceof ApiError) {
        return error.statusCode;
      }

      return 500;
    }
  };

  const registerAction = async (
    input: ApiRegisterRequest,
  ): Promise<number | ApiValidationError> => {
    try {
      const response = await apiRegister(input);

      setUser(response.data.user);
      setToken(response.data['access_token']);
      setLocalToken(response.data['access_token']);

      return 201;
    } catch (error: unknown) {
      if (error instanceof ApiValidationError) {
        return error;
      }

      return 500;
    }
  };

  const logout = async () => {
    const response = await apiLogout();

    if (response.success === true) {
      setUser(null);
      setToken('');
      removeToken();
    }
  };

  const authContextValue: AuthContextType = {
    token: token,
    user: user,
    loginAction: loginAction,
    registerAction: registerAction,
    logout: logout,
  };

  return (
    <AuthContext.Provider value={authContextValue}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthProvider;
