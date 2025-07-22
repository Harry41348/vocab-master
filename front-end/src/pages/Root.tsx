import Card from '@/components/common/Card';

export default function Root() {
  return (
    <main className="flex h-full w-screen flex-1 items-center justify-center">
      <Card className="flex flex-col gap-6">
        <h1 className="text-center text-2xl">Welcome to Vocab Master!</h1>
        <p className="w-64 text-center">
          This website lets you practice vocabulary translations.
        </p>
      </Card>
    </main>
  );
}
