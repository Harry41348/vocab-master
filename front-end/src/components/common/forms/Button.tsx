interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  text: string;
}

export default function Button({ text, ...props }: ButtonProps) {
  return (
    <button
      {...props}
      className={`w-full rounded-lg bg-gray-700 px-4 py-3 text-gray-100 shadow-md transition-all hover:scale-105 focus:outline-none active:scale-100 ${props.className}`}
    >
      {text}
    </button>
  );
}
