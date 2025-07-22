import { loggedIn } from '@/utils/authUtil';
import NavLink from './NavLink';
import { useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import toast from 'react-hot-toast';

export default function Navbar() {
  const { pathname } = useLocation();

  const navigate = useNavigate();
  const authCtx = useAuth();

  const handleLogout = async (e: React.MouseEvent<HTMLElement>) => {
    e.preventDefault();

    try {
      await authCtx?.logout();

      toast.success('You have successfully logged out!');
      navigate('/login');
    } catch {
      toast.error('Sorry, something went wrong. Please try again.');
    }
  };

  return (
    <nav>
      <ul className="flex w-full justify-center rounded-lg bg-gray-50 p-5 shadow-lg">
        <NavLink link="/" active={pathname === '/'}>
          Dashboard
        </NavLink>
        {loggedIn() && (
          <li className="mx-1 cursor-pointer rounded-full px-4 py-1.5 text-gray-700 transition-all duration-300 hover:bg-indigo-200">
            <button onClick={handleLogout} className="cursor-pointer">
              Logout
            </button>
          </li>
        )}
        {!loggedIn() && (
          <>
            <NavLink link="/login" active={pathname === '/login'}>
              Login
            </NavLink>
            <NavLink link="/register" active={pathname === '/register'}>
              Register
            </NavLink>
          </>
        )}
      </ul>
    </nav>
  );
}
