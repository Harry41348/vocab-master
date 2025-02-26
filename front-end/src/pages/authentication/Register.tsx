import Card from '@components/common/Card';
import Input from '@components/common/forms/Input';
import Button from '@/components/common/forms/Button';
import { useState } from 'react';
import ErrorMessage from '@/components/common/forms/ErrorMessage';
import { ApiValidationError } from '@/api/parseApiValidationError';
import { ValidationErrors } from '@/api/resources/types';
import toast from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';

export default function Register() {
  const [input, setInput] = useState({
    name: '',
    email: '',
    password: '',
  });
  const [errors, setErrors] = useState<ValidationErrors | null>(null);

  const authCtx = useAuth();
  const nav = useNavigate();

  const handleRegister = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    setErrors(null);

    const response = await authCtx?.registerAction(input);

    if (response === 201) {
      toast.success('You have successfully registered!');
      nav('/');
    }
    if (response instanceof ApiValidationError) {
      setErrors(response.errors);
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
        <h1 className="text-center text-2xl">Register</h1>
        <hr className="mx-auto w-8/12" />
        <form onSubmit={handleRegister} className="flex w-80 flex-col gap-5">
          <ErrorMessage errors={errors ? errors['general'] : null} />
          <Input
            type="text"
            placeholder="Name"
            name="name"
            aria-describedby="user-name"
            aria-invalid="false"
            onChange={handleInput}
            errors={errors ? errors['name'] : null}
          />
          <Input
            type="email"
            placeholder="Email"
            name="email"
            aria-describedby="user-email"
            aria-invalid="false"
            onChange={handleInput}
            errors={errors ? errors['email'] : null}
          />
          <Input
            type="password"
            placeholder="Password"
            name="password"
            aria-describedby="user-password"
            aria-invalid="false"
            onChange={handleInput}
            errors={errors ? errors['password'] : null}
          />
          {/* <Input
            placeholder="Confirm Password"
            type="password"
            name="passwordConfirmation"
          /> */}
          <Button text="Register" />
        </form>
        <a
          className="text-center text-blue-500 transition-all hover:text-blue-800"
          href="/login"
        >
          Back to login
        </a>
      </Card>
    </div>
  );
}
