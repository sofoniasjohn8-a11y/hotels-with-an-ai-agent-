import { Link, router, usePage } from '@inertiajs/react';
import { ActionLink, DataTable, DeleteButton, Pagination } from '../../../components/DataTable';
import StaffLayout from '../../../components/StaffLayout';

export default function Index({ rooms }) {
    const { auth } = usePage().props;
    const canManage = auth.user.role === 'admin';

    return (
        <StaffLayout title="Rooms">
            <div className="mb-5 mt-5 flex flex-wrap items-center justify-between gap-3">
                <p className="text-sm text-stone-500">Room inventory and current housekeeping status.</p>
                {canManage && <Link href="/staff/rooms/create" className="rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-700">Add room</Link>}
            </div>
            <DataTable
                rows={rooms.data}
                emptyMessage="No rooms added yet."
                columns={[
                    { label: 'Room', render: (room) => <span className="font-medium">{room.room_number}</span> },
                    { label: 'Room type', render: (room) => room.room_type.name },
                    { label: 'Floor', render: (room) => room.floor ?? '—' },
                    { label: 'Status', render: (room) => <span className="capitalize">{room.status}</span> },
                    { label: 'Actions', render: (room) => canManage ? <div className="flex items-center gap-4"><ActionLink href={`/staff/rooms/${room.id}/edit`}>Edit</ActionLink>{room.bookings_count === 0 && <DeleteButton onClick={() => window.confirm(`Delete room ${room.room_number}?`) && router.delete(`/staff/rooms/${room.id}`)} />}</div> : <span className="text-stone-400">View only</span> },
                ]}
            />
            <Pagination links={rooms.links} />
        </StaffLayout>
    );
}
