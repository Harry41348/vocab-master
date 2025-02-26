import { InputHTMLAttributes } from 'react';

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
  label?: string;
  errors?: string[] | null;
  containerClassName?: string;
}

export default function Input({
  label,
  errors,
  containerClassName = '',
  ...props
}: InputProps) {
  return (
    <div className={`flex flex-col ${containerClassName}`}>
      {label && (
        <label className="mb-2 text-sm font-medium text-gray-700">
          {label}
        </label>
      )}
      <input
        {...props}
        className={`w-full rounded-lg bg-gray-100 px-4 py-3 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-300 ${
          errors ? 'border-red-500' : 'border-gray-300'
        } ${props.className}`}
      />
      {errors?.map((error, index) => (
        <p
          key={`${label}-error-${index}`}
          className="mt-1 text-sm text-red-500"
        >
          {error}
        </p>
      ))}
    </div>
  );
}
