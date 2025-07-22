import classNames from 'classnames';
import React from 'react';
import { Link } from 'react-router-dom';

type NavLinkProps = {
  link: string;
  active: boolean;
  children: React.ReactNode;
};

export default function NavLink({ link, active, children }: NavLinkProps) {
  return (
    <li
      className={classNames(
        {
          'bg-gradient-to-tr from-indigo-500 to-indigo-400 text-white': active,
        },
        'mx-1 cursor-pointer rounded-full px-4 py-1.5 text-gray-700 transition-all duration-300 hover:bg-indigo-200',
      )}
    >
      <Link to={link}>{children}</Link>
    </li>
  );
}
