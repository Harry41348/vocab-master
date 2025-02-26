import Card from '@components/common/Card';
import Input from '@components/common/forms/Input';
import Button from '@/components/common/forms/Button';
import { useState } from 'react';
import ErrorMessage from '@/components/common/forms/ErrorMessage';
import { useNavigate } from 'react-router-dom';
import toast from 'react-hot-toast';
import { useAuth } from '@/hooks/useAuth';

export default function Login() {
  const [input, setInput] = useState({
    email: '',
    password: '',
  });
  const [error, setError] = useState<string | null>(null);

  const authCtx = useAuth();
  const nav = useNavigate();

  const handleLogin = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    setError(null);

    const response = await authCtx?.loginAction(input);

    if (response === 200) {
      toast.success('You have successfully logged in!');
      nav('/');
    } else if (response === 401) {
      setError(
        'You entered an incorrect username or password, please try again.',
      );
    } else {
      toast.error('Sorry, something went wrong. Please try again.');
    }
  };

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setInput((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  return (
    <div className="flex h-screen w-screen items-center justify-center">
      <Card className="flex flex-col gap-6">
        <h1 className="text-center text-2xl">Login</h1>
        <hr className="mx-auto w-8/12" />
        <form onSubmit={handleLogin} className="flex w-80 flex-col gap-5">
          <ErrorMessage errors={error ? [error] : null} />
          <Input
            type="email"
            placeholder="Email"
            name="email"
            aria-describedby="user-email"
            aria-invalid="false"
            onChange={handleInput}
          />
          <Input
            placeholder="Password"
            type="password"
            name="password"
            aria-describedby="user-password"
            aria-invalid="false"
            onChange={handleInput}
          />
          <Button text="Login" />
        </form>
        <a
          className="text-center text-blue-500 transition-all hover:text-blue-800"
          href="/register"
        >
          Register here
        </a>
      </Card>
    </div>
  );
}
