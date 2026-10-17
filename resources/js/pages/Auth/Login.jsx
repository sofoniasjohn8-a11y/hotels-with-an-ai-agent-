import { Head, useForm } from '@inertiajs/react';
import { Field } from '../../components/FormControls';

const loginPhoto = 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1800&q=85';

export default function Login() {
    const form = useForm({ email: '', password: '', remember: false });

    function submit(event) {
        event.preventDefault();
        form.post('/login');
    }

    return (
        <>
            <Head title="Staff sign in" />
            <main className="grid min-h-screen bg-white text-stone-900 lg:grid-cols-2">
                <div className="relative min-h-[28vh] overflow-hidden bg-stone-900 lg:min-h-screen">
                    <img
                        src={loginPhoto}
                        alt="A softly lit hotel room prepared for arriving guests"
                        fetchPriority="high"
                        className="absolute inset-0 size-full object-cover"
                    />
                    <div aria-hidden="true" className="absolute inset-0 bg-gradient-to-t from-stone-950/65 via-stone-950/10 to-transparent" />
                    <p className="absolute bottom-6 left-6 text-sm font-medium text-white sm:bottom-10 sm:left-10">Hotel operations</p>
                </div>
                <section className="flex items-center justify-center px-6 py-12 sm:px-10 lg:px-16">
                    <div className="w-full max-w-md">
                        <p className="text-sm font-medium text-emerald-800">Hotel operations</p>
                        <h1 className="mt-2 text-3xl font-semibold tracking-tight">Staff sign in</h1>
                        <p className="mt-2 text-sm leading-6 text-stone-600">Sign in with your staff account to manage hotel operations.</p>
                        <form onSubmit={submit} className="mt-8 space-y-5">
                            <Field
                                label="Email"
                                type="email"
                                name="email"
                                autoComplete="username"
                                autoFocus
                                required
                                value={form.data.email}
                                onChange={(event) => form.setData('email', event.target.value)}
                                error={form.errors.email}
                            />
                            <Field
                                label="Password"
                                type="password"
                                name="password"
                                autoComplete="current-password"
                                required
                                value={form.data.password}
                                onChange={(event) => form.setData('password', event.target.value)}
                                error={form.errors.password}
                            />
                            <label className="flex items-center gap-2 text-sm text-stone-600">
                                <input
                                    type="checkbox"
                                    checked={form.data.remember}
                                    onChange={(event) => form.setData('remember', event.target.checked)}
                                    className="size-4 rounded border-stone-300 accent-stone-900"
                                />
                                Remember me
                            </label>
                            {form.errors.throttle && <p role="alert" className="text-sm text-rose-700">{form.errors.throttle}</p>}
                            <button
                                type="submit"
                                disabled={form.processing}
                                className="w-full rounded-md bg-stone-900 px-5 py-3 text-sm font-medium text-white hover:bg-stone-700 disabled:opacity-50"
                            >
                                {form.processing ? 'Signing in…' : 'Sign in'}
                            </button>
                        </form>
                    </div>
                </section>
            </main>
        </>
    );
}
