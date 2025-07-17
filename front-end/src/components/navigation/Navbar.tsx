import { loggedIn } from '@/utils/authUtil';
import './Navbar.scss';
import classNames from 'classnames';

export default function Navbar() {
  const path = window.location.pathname;

  return (
    <nav>
      <ul className="navbar">
        <li className={classNames({ active: path === '/' })}>
          <a href="/">Dashboard</a>
        </li>
        {!loggedIn() && (
          <>
            <li className={classNames({ active: path === '/login' })}>
              <a href="/login">Login</a>
            </li>
            <li className={classNames({ active: path === '/register' })}>
              <a href="/register">Register</a>
            </li>
          </>
        )}
      </ul>
    </nav>
  );
}
