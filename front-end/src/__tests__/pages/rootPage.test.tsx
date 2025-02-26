import userEvent from '@testing-library/user-event';
import { visitPath } from '../__utils__/visitPath';
import { screen } from '@testing-library/react';
import { loggedIn } from '@/utils/authUtil';
import { apiLogoutMock } from '../__mocks__/apiServerMock';

describe('visiting root', () => {
  it('renders homepage as guest', () => {
    visitPath('/');

    expect(screen.getByText('This is a template project')).toBeInTheDocument();
    expect(
      screen.getByText(
        'This project utilizes Laravel for the back-end API, and React for the front-end. It comes equipped with a simple authentication system for a user to login, register and logout.',
      ),
    ).toBeInTheDocument();
    expect(screen.getByText('Login')).toBeInTheDocument();
    expect(screen.getByText('Register')).toBeInTheDocument();
  });

  it('renders homepage as authenticated user', () => {
    visitPath('/', true);

    expect(screen.getByText('This is a template project')).toBeInTheDocument();
    expect(
      screen.getByText(
        'This project utilizes Laravel for the back-end API, and React for the front-end. It comes equipped with a simple authentication system for a user to login, register and logout.',
      ),
    ).toBeInTheDocument();
    expect(screen.getByText('Logout')).toBeInTheDocument();
  });

  it('authenticated user can log out', async () => {
    // Mock logout API
    apiLogoutMock();

    // Setup the user and visit the homepage
    const user = userEvent.setup();
    visitPath('/', true);

    // Check that the user is logged in
    expect(screen.getByText('This is a template project')).toBeInTheDocument();
    expect(loggedIn()).toBe(true);

    // Click the logout link
    const logoutLink = screen.getByText('Logout');
    await user.click(logoutLink);

    // Check that the user is logged out
    expect(loggedIn()).toBe(false);
  });
});
