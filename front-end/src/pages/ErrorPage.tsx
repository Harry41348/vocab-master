import { useRouteError } from 'react-router-dom';

const ErrorPage: React.FC = () => {
  const error: unknown = useRouteError();

  return (
    <div
      id="error-page"
      className="flex h-screen flex-col items-center justify-center gap-8"
    >
      <h1 className="text-4xl font-bold">Oops!</h1>
      <p>Sorry, an unexpected error has occurred.</p>
      <p className="text-slate-400">
        <i>
          {(error as Error)?.message ||
            (error as { statusText?: string })?.statusText}
        </i>
      </p>
    </div>
  );
};

export default ErrorPage;
