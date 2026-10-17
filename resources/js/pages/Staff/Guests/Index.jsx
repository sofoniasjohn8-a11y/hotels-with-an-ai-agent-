import { Link, router } from '@inertiajs/react';
import { ActionLink, DataTable, DeleteButton, Pagination } from '../../../components/DataTable';
import StaffLayout from '../../../components/StaffLayout';

export default function Index({ guests }) {
    return (
        <StaffLayout title="Guests">
            <div className="mb-5 mt-5 flex flex-wrap items-center justify-between gap-3">
                <p className="text-sm text-stone-500">Guest contact details and booking history.</p>
                <Link href="/staff/guests/create" className="rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-700">Add guest</Link>
            </div>
            <DataTable
                rows={guests.data}
                emptyMessage="No guests added yet."
                columns={[
                    { label: 'Guest', render: (guest) => <span className="font-medium">{guest.name}</span> },
                    { label: 'Email', render: (guest) => guest.email || '—' },
                    { label: 'Phone', render: (guest) => guest.phone || '—' },
                    { label: 'Bookings', key: 'bookings_count' },
                    { label: 'Actions', render: (guest) => <div className="flex items-center gap-4"><ActionLink href={`/staff/guests/${guest.id}/edit`}>Edit</ActionLink><DeleteButton onClick={() => window.confirm(`Delete guest ${guest.name}?`) && router.delete(`/staff/guests/${guest.id}`)} /></div> },
                ]}
            />
            <Pagination links={guests.links} />
        </StaffLayout>
    );
}
