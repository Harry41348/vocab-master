import { loggedIn } from '@/utils/authUtil';
import NavLink from './NavLink';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import toast from 'react-hot-toast';

export default function Navbar() {
  const path = window.location.pathname;

  const navigate = useNavigate();
  const authCtx = useAuth();

  const handleLogout = async (e: React.MouseEvent<HTMLElement>) => {
    e.preventDefault();

    try {
      await authCtx?.logout();

      toast.success('You have successfully logged out!');
      navigate('/');
    } catch {
      toast.error('Sorry, something went wrong. Please try again.');
    }
  };

  return (
    <nav>
      <ul className="flex w-full justify-center rounded-lg bg-gray-50 p-5 shadow-lg">
        <NavLink link="/" active={path === '/'}>
          Dashboard
        </NavLink>
        {loggedIn() && (
          <>
            <NavLink link={'#'} onClick={handleLogout} active={false}>
              Logout
            </NavLink>
          </>
        )}
        {!loggedIn() && (
          <>
            <NavLink link="/login" active={path === '/login'}>
              Login
            </NavLink>
            <NavLink link="/register" active={path === '/register'}>
              Register
            </NavLink>
          </>
        )}
      </ul>
    </nav>
  );
}
