import { loggedIn } from '@/utils/authUtil';
import { Navigate, Outlet } from 'react-router-dom';

export default function GuestRoute() {
  if (loggedIn()) return <Navigate to="/" />;

  return <Outlet />;
}
