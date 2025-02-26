import { PropsWithChildren } from 'react';

export default function linkButton({
  children,
  ...props
}: PropsWithChildren<React.AnchorHTMLAttributes<HTMLAnchorElement>>) {
  return (
    <a
      {...props}
      className={`rounded-full bg-white px-6 py-2 shadow-md transition-all hover:bg-slate-600 hover:text-white ${props.className}`}
    >
      {children}
    </a>
  );
}
