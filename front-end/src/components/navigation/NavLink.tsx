import classNames from 'classnames';
import React from 'react';

type NavLinkProps = {
  link?: string;
  onClick?: (e: React.MouseEvent<HTMLElement>) => void;
  active: boolean;
  children: React.ReactNode;
};

export default function NavLink({
  link,
  active,
  children,
  onClick,
}: NavLinkProps) {
  return (
    <li
      className={classNames(
        {
          'bg-gradient-to-tr from-indigo-500 to-indigo-400 text-white': active,
        },
        'mx-1 cursor-pointer rounded-full px-4 py-1.5 text-gray-700 transition-all duration-300 hover:bg-indigo-200',
      )}
    >
      <a href={link} onClick={onClick}>
        {children}
      </a>
    </li>
  );
}
