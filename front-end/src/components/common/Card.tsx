import { PropsWithChildren } from 'react';

export default function Card({
  children,
  ...props
}: PropsWithChildren<React.HTMLAttributes<HTMLDivElement>>) {
  return (
    <div
      {...props}
      className={`rounded-md bg-gray-100 px-20 py-16 shadow-lg ${props.className}`}
    >
      {children}
    </div>
  );
}
