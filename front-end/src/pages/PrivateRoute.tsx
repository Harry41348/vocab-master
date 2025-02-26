import { guest } from '@/utils/authUtil';
import { Navigate, Outlet } from 'react-router-dom';

export default function PrivateRoute() {
  if (guest()) return <Navigate to="/login" />;

  return <Outlet />;
}
