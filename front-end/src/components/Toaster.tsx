import { Toaster as HotToaster } from 'react-hot-toast';

export default function Toaster() {
  const options = {
    duration: 5000,
  };
  const toaster = <HotToaster toastOptions={options} />;

  return toaster;
}
