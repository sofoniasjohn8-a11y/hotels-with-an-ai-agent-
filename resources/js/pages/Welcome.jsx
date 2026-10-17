import { Head, Link } from '@inertiajs/react';

const welcomePhoto = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2200&q=88';

export default function Welcome() {
    return (
        <>
            <Head title="Hotel Management System" />
            <main className="grid min-h-screen bg-stone-950 text-white lg:grid-cols-[0.85fr_1.15fr]">
                <section className="flex min-h-[52vh] flex-col justify-center px-7 py-16 sm:px-12 lg:min-h-screen lg:px-20">
                    <p className="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Hotel operations</p>
                    <h1 className="mt-5 max-w-xl text-4xl font-semibold leading-tight sm:text-5xl">Digitize Your Hotel Experience</h1>
                    <p className="mt-5 max-w-md text-base leading-7 text-stone-300">A considered welcome begins with a well-run stay.</p>
                    <Link href="/login" className="mt-9 inline-flex w-fit items-center rounded-md bg-emerald-300 px-5 py-3 text-sm font-semibold text-stone-950 transition hover:bg-emerald-200">
                        Staff sign in
                    </Link>
                </section>
                <div className="relative min-h-[48vh] overflow-hidden lg:min-h-screen">
                    <img
                        src={welcomePhoto}
                        alt="A hotel entrance framed by a pool and tropical gardens"
                        fetchPriority="high"
                        className="absolute inset-0 size-full object-cover"
                    />
                    <div aria-hidden="true" className="absolute inset-0 bg-gradient-to-t from-stone-950/30 via-transparent to-transparent" />
                </div>
            </main>
        </>
    );
}
