import { visitPath } from '../__utils__/visitPath';
import { screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { apiRegisterMock, apiServerMock } from '../__mocks__/apiServerMock';
import { loggedIn } from '@/utils/authUtil';
import { http, HttpResponse } from 'msw';

describe('visiting login route', () => {
  it('redirects to root when logged in', () => {
    visitPath('/register', true);

    expect(screen.getByText('Logout')).toBeInTheDocument();
  });

  it('guest attempts to register with valid details', async () => {
    // Mock register API
    apiRegisterMock();

    // Setup user and visit register page
    const user = userEvent.setup();
    visitPath('/register');

    expect(
      screen.getByText('Register', { selector: 'h1' }),
    ).toBeInTheDocument();

    // Fill in the form
    const nameInput = screen.getByPlaceholderText('Name');
    const emailInput = screen.getByPlaceholderText('Email');
    const passwordInput = screen.getByPlaceholderText('Password');

    await user.type(nameInput, 'Test User');
    await user.type(emailInput, 'test@email.com');
    await user.type(passwordInput, 'password');

    // Submit the form
    const registerButton = screen.getByRole('button', { name: 'Register' });
    await user.click(registerButton);

    expect(loggedIn()).toBe(true);
    expect(
      screen.getByText('You have successfully registered!'),
    ).toBeInTheDocument();
  });

  it('guest attempts to register with invalid details', async () => {
    // Mock register API
    apiRegisterMock();

    // Setup user and visit login page
    const user = userEvent.setup();
    visitPath('/register');

    expect(
      screen.getByText('Register', { selector: 'h1' }),
    ).toBeInTheDocument();

    // Fill in the form
    const nameInput = screen.getByPlaceholderText('Name');
    const emailInput = screen.getByPlaceholderText('Email');
    const passwordInput = screen.getByPlaceholderText('Password');

    await user.type(nameInput, 'Te');
    await user.type(emailInput, 'taken-email@email.com');
    await user.type(passwordInput, 'pass');

    // Submit the form
    const registerButton = screen.getByRole('button', { name: 'Register' });
    await user.click(registerButton);

    expect(loggedIn()).toBe(false);
    expect(
      screen.getByText('The name needs to be at least 3 characters.'),
    ).toBeInTheDocument();
    expect(
      screen.getByText('The email has already been taken.'),
    ).toBeInTheDocument();
    expect(
      screen.getByText('The password needs to be at least 6 characters.'),
    ).toBeInTheDocument();
  });

  it('guest attempts to register, server is down', async () => {
    apiServerMock.use(
      http.post('*/register', async () => {
        return HttpResponse.error();
      }),
    );

    // Setup user and visit register page
    const user = userEvent.setup();
    visitPath('/register');

    expect(
      screen.getByText('Register', { selector: 'h1' }),
    ).toBeInTheDocument();

    // Fill in the form
    const nameInput = screen.getByPlaceholderText('Name');
    const emailInput = screen.getByPlaceholderText('Email');
    const passwordInput = screen.getByPlaceholderText('Password');

    await user.type(nameInput, 'Test User');
    await user.type(emailInput, 'test@email.com');
    await user.type(passwordInput, 'password');

    // Submit the form
    const registerButton = screen.getByRole('button', { name: 'Register' });
    await user.click(registerButton);

    expect(loggedIn()).toBe(false);
    expect(
      screen.getByText('Sorry, something went wrong. Please try again.'),
    ).toBeInTheDocument();
  });
});
