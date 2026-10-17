import { Head, Link, router, usePage } from '@inertiajs/react';

const navigation = [
    { label: 'Dashboard', href: '/staff/dashboard' },
    { label: 'Bookings', href: '/staff/bookings' },
    { label: 'Guests', href: '/staff/guests' },
    { label: 'Rooms', href: '/staff/rooms' },
    { label: 'Room types', href: '/staff/room-types' },
];

export default function StaffLayout({ title, children }) {
    const { auth, errors = {}, flash } = usePage().props;
    const errorMessages = Object.values(errors).flat();

    return (
        <>
            <Head title={`${title} · Hotel staff`} />
            <div className="min-h-screen bg-stone-50 text-stone-900">
                <header className="border-b border-stone-200 bg-white">
                    <div className="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-4">
                        <Link href="/staff/dashboard" className="text-lg font-semibold tracking-tight">
                            Hotel operations
                        </Link>
                        <nav className="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                            {navigation.map((item) => (
                                <Link key={item.href} href={item.href} className="text-stone-600 transition hover:text-stone-950">
                                    {item.label}
                                </Link>
                            ))}
                        </nav>
                        <div className="flex items-center gap-3 text-sm">
                            <span className="hidden text-stone-500 sm:inline">{auth.user.name}</span>
                            <button
                                type="button"
                                onClick={() => router.post('/staff/logout')}
                                className="rounded-lg border border-stone-300 px-3 py-2 font-medium hover:bg-stone-50"
                            >
                                Sign out
                            </button>
                        </div>
                    </div>
                </header>
                <main className="mx-auto max-w-7xl px-5 py-8">
                    <h1 className="text-2xl font-semibold tracking-tight">{title}</h1>
                    {flash.success && (
                        <div role="status" className="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {flash.success}
                        </div>
                    )}
                    {errorMessages.length > 0 && (
                        <div role="alert" className="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                            {errorMessages.map((message, index) => <p key={`${message}-${index}`}>{message}</p>)}
                        </div>
                    )}
                    {children}
                </main>
            </div>
        </>
    );
}
