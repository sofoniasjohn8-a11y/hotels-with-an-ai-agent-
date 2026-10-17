import { Link } from '@inertiajs/react';

export function DataTable({ columns, rows, emptyMessage }) {
    return (
        <div className="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
            <table className="w-full min-w-[680px] text-left text-sm">
                <thead className="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        {columns.map((column) => (
                            <th key={column.label} scope="col" className="px-5 py-3 font-medium">
                                {column.label}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody className="divide-y divide-stone-100">
                    {rows.length > 0 ? rows.map((row) => (
                        <tr key={row.id} className="align-middle">
                            {columns.map((column) => (
                                <td key={column.label} className="px-5 py-4">
                                    {column.render ? column.render(row) : row[column.key]}
                                </td>
                            ))}
                        </tr>
                    )) : (
                        <tr>
                            <td colSpan={columns.length} className="px-5 py-12 text-center text-stone-500">
                                {emptyMessage}
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    );
}

export function Pagination({ links = [] }) {
    if (links.length < 2) return null;

    return (
        <nav aria-label="Pagination" className="mt-5 flex flex-wrap gap-2">
            {links.map((link, index) => (
                <Link
                    key={`${link.label}-${index}`}
                    href={link.url || '#'}
                    aria-current={link.active ? 'page' : undefined}
                    aria-disabled={!link.url}
                    className={`rounded-lg border px-3 py-2 text-sm ${link.active ? 'border-stone-900 bg-stone-900 text-white' : 'border-stone-200 bg-white text-stone-600 hover:bg-stone-50'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                >
                    {link.label.replace('&laquo;', '‹').replace('&raquo;', '›')}
                </Link>
            ))}
        </nav>
    );
}

export function ActionLink({ href, children }) {
    return (
        <Link href={href} className="font-medium text-stone-700 underline decoration-stone-300 underline-offset-4 hover:text-stone-950">
            {children}
        </Link>
    );
}

export function DeleteButton({ onClick }) {
    return (
        <button type="button" onClick={onClick} className="font-medium text-rose-700 underline decoration-rose-200 underline-offset-4 hover:text-rose-900">
            Delete
        </button>
    );
}
