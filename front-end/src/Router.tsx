import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import { routes } from './utils/routes';

const Router = () => {
  const router = createBrowserRouter(routes);

  return <RouterProvider router={router} />;
};

export default Router;
