interface ErrorMessageProps {
  errors: string[] | null;
}

export default function ErrorMessage({ errors }: ErrorMessageProps) {
  return (
    <>
      {errors?.map((error, index) => (
        <p
          key={`error-${index}`}
          className="rounded-lg bg-linear-to-br from-red-300 to-red-200 p-3 text-center text-gray-700 shadow-md"
        >
          {error}
        </p>
      ))}
    </>
  );
}
