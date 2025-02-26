import { expect, afterEach } from 'vitest';
import { cleanup } from '@testing-library/react';
import * as matchers from '@testing-library/jest-dom/matchers';
import { apiServerMock } from './__mocks__/apiServerMock';
import { removeToken } from '@/utils/authUtil';
import toast from 'react-hot-toast';

// Mock matchMedia for react-hot-toast
Object.defineProperty(window, 'matchMedia', {
  writable: true,
  value: vi.fn().mockImplementation((query) => ({
    matches: false,
    media: query,
    onchange: null,
    addListener: vi.fn(), // deprecated
    removeListener: vi.fn(), // deprecated
    addEventListener: vi.fn(),
    removeEventListener: vi.fn(),
    dispatchEvent: vi.fn(),
  })),
});

expect.extend(matchers);

beforeAll(() => {
  apiServerMock.listen();
});

afterEach(() => {
  cleanup();
  removeToken();
  apiServerMock.resetHandlers();
  toast.remove();
});

afterAll(() => {
  apiServerMock.close();
});
