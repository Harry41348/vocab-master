import { createRoutesFromElements, Outlet, Route } from 'react-router-dom';
import Login from '@/pages/authentication/Login';
import ErrorPage from '@/pages/ErrorPage';
import Register from '@/pages/authentication/Register';
import Root from '@/pages/Root';
import PrivateRoute from '@/pages/PrivateRoute';
import GuestRoute from '@/pages/GuestRoute';
import Navbar from '@/components/navigation/Navbar';

export const routes = createRoutesFromElements(
  <Route
    element={
      <>
        <Navbar />
        <Outlet />
      </>
    }
  >
    <Route path="/" element={<Root></Root>} errorElement={<ErrorPage />} />,
    <Route element={<GuestRoute />}>
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
    </Route>
    <Route element={<PrivateRoute />}></Route>
  </Route>,
);
