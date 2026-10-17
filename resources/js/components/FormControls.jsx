import { Link } from '@inertiajs/react';

export function Field({ label, error, className = '', ...props }) {
    return (
        <label className={`block ${className}`}>
            <span className="mb-1.5 block text-sm font-medium text-stone-700">{label}</span>
            <input
                {...props}
                className="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm outline-none transition placeholder:text-stone-400 focus:border-stone-700 focus:ring-2 focus:ring-stone-200"
            />
            {error && <span role="alert" className="mt-1.5 block text-sm text-rose-700">{error}</span>}
        </label>
    );
}

export function TextArea({ label, error, ...props }) {
    return (
        <label className="block">
            <span className="mb-1.5 block text-sm font-medium text-stone-700">{label}</span>
            <textarea
                {...props}
                rows={4}
                className="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm outline-none transition placeholder:text-stone-400 focus:border-stone-700 focus:ring-2 focus:ring-stone-200"
            />
            {error && <span role="alert" className="mt-1.5 block text-sm text-rose-700">{error}</span>}
        </label>
    );
}

export function SelectField({ label, error, children, ...props }) {
    return (
        <label className="block">
            <span className="mb-1.5 block text-sm font-medium text-stone-700">{label}</span>
            <select
                {...props}
                className="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-stone-700 focus:ring-2 focus:ring-stone-200"
            >
                {children}
            </select>
            {error && <span role="alert" className="mt-1.5 block text-sm text-rose-700">{error}</span>}
        </label>
    );
}

export function FormActions({ processing, cancelHref, submitLabel }) {
    return (
        <div className="flex flex-wrap items-center gap-3 border-t border-stone-100 pt-5">
            <button
                type="submit"
                disabled={processing}
                className="rounded-xl bg-stone-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-stone-700 disabled:opacity-50"
            >
                {processing ? 'Saving…' : submitLabel}
            </button>
            <Link href={cancelHref} className="rounded-xl border border-stone-300 px-5 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50">
                Cancel
            </Link>
        </div>
    );
}
