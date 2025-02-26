import { render, RenderResult } from '@testing-library/react';
import { createMemoryRouter, RouterProvider } from 'react-router-dom';
import type { Router } from '@remix-run/router';
import { routes } from '@/utils/routes';
import { mockAuth } from '../__mocks__/mockAuth';
import AuthProvider from '@/context/AuthContext';
import Toaster from '@/components/Toaster';

type RenderWithRouterResult = {
  router: Router;
  result: RenderResult;
};

/**
 *
 * @param path The path to visit
 * @param auth Whether to mock authentication, defaults to false
 * @returns
 */
export const visitPath = (
  path: string,
  auth: boolean = false,
): RenderWithRouterResult => {
  if (auth) {
    mockAuth();
  }

  const router = createMemoryRouter(routes, {
    initialEntries: [path],
  });

  return {
    router: router,
    result: render(
      <AuthProvider>
        <Toaster />
        <RouterProvider router={router} />
      </AuthProvider>,
    ),
  };
};
