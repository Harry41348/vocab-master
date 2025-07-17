import Card from '@/components/common/Card';
import LinkButton from '@/components/common/LinkButton';
import { useAuth } from '@/hooks/useAuth';
import { guest, loggedIn } from '@/utils/authUtil';
import toast from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';

export default function Root() {
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
    <main className="flex h-screen w-screen items-center justify-center">
      <Card className="flex flex-col gap-6">
        <h1 className="text-center text-2xl">This is a template project</h1>
        {authCtx?.user && (
          <p className="w-64 text-center">Welcome {authCtx.user.name}!</p>
        )}
        <p className="w-64 text-center">
          This project utilizes Laravel for the back-end API, and React for the
          front-end. It comes equipped with a simple authentication system for a
          user to login, register and logout.
        </p>
        <div className="flex justify-center gap-3">
          {loggedIn() && (
            <LinkButton className="hover:cursor-pointer" onClick={handleLogout}>
              Logout
            </LinkButton>
          )}
          {guest() && (
            <>
              <LinkButton href="/login">Login</LinkButton>
              <LinkButton href="/register">Register</LinkButton>
            </>
          )}
        </div>
      </Card>
    </main>
  );
}
