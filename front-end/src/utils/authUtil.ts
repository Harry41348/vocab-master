export function guest(): boolean {
  return localStorage.getItem('accessToken') === null;
}

export function loggedIn(): boolean {
  return !guest();
}

export function getToken() {
  return localStorage.getItem('accessToken') || null;
}

export function setToken(token: string) {
  localStorage.setItem('accessToken', token);
}

export function removeToken() {
  localStorage.removeItem('accessToken');
}
