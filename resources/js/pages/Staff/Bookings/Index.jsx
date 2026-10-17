import { Link, router } from '@inertiajs/react';
import { ActionLink, DataTable, Pagination } from '../../../components/DataTable';
import StaffLayout from '../../../components/StaffLayout';

function changeStatus(id, action, confirmation) {
    if (window.confirm(confirmation)) router.patch(`/staff/bookings/${id}/${action}`);
}

export default function Index({ bookings }) {
    return (
        <StaffLayout title="Bookings">
            <div className="mb-5 mt-5 flex flex-wrap items-center justify-between gap-3">
                <p className="text-sm text-stone-500">Create, update, and manage guest stays.</p>
                <Link href="/staff/bookings/create" className="rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-700">Create booking</Link>
            </div>
            <DataTable
                rows={bookings.data}
                emptyMessage="No bookings yet. Add a guest, then create a booking."
                columns={[
                    { label: 'Booking', render: (booking) => <><span className="font-mono text-xs">{booking.booking_reference}</span><span className="mt-1 block text-xs text-stone-500">{booking.guest.name}</span></> },
                    { label: 'Room type', render: (booking) => booking.room_type.name },
                    { label: 'Stay', render: (booking) => <span className="whitespace-nowrap">{booking.check_in} – {booking.check_out}</span> },
                    { label: 'Status', render: (booking) => <span className="capitalize">{booking.status.replace('_', ' ')}</span> },
                    { label: 'Actions', render: (booking) => (
                        <div className="flex min-w-52 flex-wrap items-center gap-x-4 gap-y-2">
                            {[ 'pending', 'confirmed' ].includes(booking.status) && <ActionLink href={`/staff/bookings/${booking.id}/edit`}>Edit</ActionLink>}
                            {booking.status === 'confirmed' && <button type="button" className="font-medium text-emerald-700 underline underline-offset-4" onClick={() => changeStatus(booking.id, 'check-in', 'Check this guest in?')}>Check in</button>}
                            {booking.status === 'checked_in' && <button type="button" className="font-medium text-emerald-700 underline underline-offset-4" onClick={() => changeStatus(booking.id, 'check-out', 'Check this guest out?')}>Check out</button>}
                            {[ 'pending', 'confirmed' ].includes(booking.status) && <button type="button" className="font-medium text-rose-700 underline underline-offset-4" onClick={() => changeStatus(booking.id, 'cancel', 'Cancel this booking?')}>Cancel</button>}
                            {booking.room && <span className="text-xs text-stone-500">Room {booking.room.room_number}</span>}
                        </div>
                    ) },
                ]}
            />
            <Pagination links={bookings.links} />
        </StaffLayout>
    );
}
