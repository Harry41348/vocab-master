import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import '@/index.css';
import Router from '@/Router.tsx';
import AuthProvider from '@/context/AuthContext';
import Toaster from '@/components/Toaster';

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <AuthProvider>
      <Toaster />
      <Router />
    </AuthProvider>
  </StrictMode>,
);
