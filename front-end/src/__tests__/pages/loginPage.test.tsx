import { visitPath } from '../__utils__/visitPath';
import { screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { apiLoginMock } from '../__mocks__/apiServerMock';
import { loggedIn } from '@/utils/authUtil';

describe('visiting login route', () => {
  it('redirects to root when logged in', () => {
    visitPath('/login', true);

    expect(screen.getByText('Logout')).toBeInTheDocument();
  });

  it('guest attempts to login with correct credentials', async () => {
    // Mock login API
    apiLoginMock();

    // Setup user and visit login page
    const user = userEvent.setup();
    visitPath('/login');

    expect(screen.getByText('Login', { selector: 'h1' })).toBeInTheDocument();

    // Fill in the form
    const emailInput = screen.getByPlaceholderText('Email');
    const passwordInput = screen.getByPlaceholderText('Password');

    await user.type(emailInput, 'test@email.com');
    await user.type(passwordInput, 'password');

    // Submit the form
    const loginButton = screen.getByRole('button', { name: 'Login' });
    await user.click(loginButton);

    expect(loggedIn()).toBe(true);
    expect(
      screen.getByText('You have successfully logged in!'),
    ).toBeInTheDocument();
  });

  it('guest attempts to login with invalid credentials', async () => {
    // Mock login API
    apiLoginMock();

    // Setup user and visit login page
    const user = userEvent.setup();
    visitPath('/login');

    expect(screen.getByText('Login', { selector: 'h1' })).toBeInTheDocument();

    // Fill in the form
    const emailInput = screen.getByPlaceholderText('Email');
    const passwordInput = screen.getByPlaceholderText('Password');

    await user.type(emailInput, 'test@email.com');
    await user.type(passwordInput, 'incorrect-password');

    // Submit the form
    const loginButton = screen.getByRole('button', { name: 'Login' });
    await user.click(loginButton);

    expect(loggedIn()).toBe(false);
    expect(
      screen.getByText(
        'You entered an incorrect username or password, please try again.',
      ),
    ).toBeInTheDocument();
  });

  it('guest attempts to login, server is down', async () => {
    // Setup user and visit login page
    const user = userEvent.setup();
    visitPath('/login');

    expect(screen.getByText('Login', { selector: 'h1' })).toBeInTheDocument();

    // Fill in the form
    const emailInput = screen.getByPlaceholderText('Email');
    const passwordInput = screen.getByPlaceholderText('Password');

    await user.type(emailInput, 'test@email.com');
    await user.type(passwordInput, 'password');

    // Submit the form
    const loginButton = screen.getByRole('button', { name: 'Login' });
    await user.click(loginButton);

    expect(loggedIn()).toBe(false);
    expect(
      screen.getByText('Sorry, something went wrong. Please try again.'),
    ).toBeInTheDocument();
  });
});
