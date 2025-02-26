import { setupServer } from 'msw/node';
import {
  DefaultBodyType,
  http,
  HttpResponse,
  HttpResponseResolver,
  PathParams,
} from 'msw';
import { ApiLoginRequest } from '@/api/resources/auth/apiLogin';
import { userFactory } from '../__factories__/userFactory';
import { ApiRegisterRequest } from '@/api/resources/auth/apiRegister';

type TestResponseResolverFn = HttpResponseResolver<
  PathParams,
  DefaultBodyType,
  undefined
>;

const defaultTestResponseResolver: TestResponseResolverFn = () => {
  throw new Error('Making call to non-mocked URL'); // Throw an error if the URL is not mocked
};

export const apiServerMock = setupServer(
  http.get('*', defaultTestResponseResolver),
  http.post('*', defaultTestResponseResolver),
  http.put('*', defaultTestResponseResolver),
  http.patch('*', defaultTestResponseResolver),
  http.delete('*', defaultTestResponseResolver),
);

export const apiLoginMock = () => {
  apiServerMock.use(
    http.post('*/login', async ({ request }) => {
      const { email, password } = (await request.json()) as ApiLoginRequest;

      // Verify request details
      if (email === 'test@email.com' && password === 'password') {
        return HttpResponse.json({
          success: true,
          data: {
            access_token: 'test_token',
            user: userFactory.build(),
          },
        });
      } else {
        return HttpResponse.json(
          {
            success: false,
            data: null,
            message: 'Invalid credentials',
          },
          { status: 401 },
        );
      }
    }),
  );
};

export const apiRegisterMock = () => {
  apiServerMock.use(
    http.post('*/register', async ({ request }) => {
      const { name, email, password } =
        (await request.json()) as ApiRegisterRequest;

      // Verify request details
      if (
        email === 'test@email.com' &&
        password === 'password' &&
        name === 'Test User'
      ) {
        return HttpResponse.json({
          success: true,
          data: {
            access_token: 'test_token',
            user: userFactory.build(),
          },
        });
      } else {
        return HttpResponse.json(
          {
            status: 'error',
            message: 'Validation failed',
            errors: {
              name: ['The name needs to be at least 3 characters.'],
              email: ['The email has already been taken.'],
              password: ['The password needs to be at least 6 characters.'],
            },
          },
          { status: 422 },
        );
      }
    }),
  );
};

export const apiLogoutMock = () => {
  apiServerMock.use(
    http.post('*/logout', async () => {
      return HttpResponse.json({
        success: true,
        data: 'Successfully logged out.',
      });
    }),
  );
};
