import { Link, router, usePage } from '@inertiajs/react';
import { ActionLink, DataTable, DeleteButton, Pagination } from '../../../components/DataTable';
import StaffLayout from '../../../components/StaffLayout';

const roomTypePhotos = [
    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=900&q=80',
];

export default function Index({ roomTypes }) {
    const { auth } = usePage().props;
    const canManage = auth.user.role === 'admin';

    return (
        <StaffLayout title="Room types">
            <div className="mb-5 mt-5 flex flex-wrap items-center justify-between gap-3">
                <p className="text-sm text-stone-500">Manage room categories, guest capacity, and published rates.</p>
                {canManage && <Link href="/staff/room-types/create" className="rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-700">Add room type</Link>}
            </div>
            {roomTypes.data.length > 0 ? (
                <section aria-label="Room type inventory" className="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    {roomTypes.data.map((type, index) => {
                        const fallbackPhoto = roomTypePhotos[index % roomTypePhotos.length];
                        const photo = type.photos?.[0] || fallbackPhoto;

                        return (
                            <article key={type.id} className="overflow-hidden rounded-md border border-stone-200 bg-white">
                                <div className="aspect-[16/10] overflow-hidden bg-stone-100">
                                    <img
                                        src={photo}
                                        alt={`${type.name} room`}
                                        loading="lazy"
                                        onError={(event) => event.currentTarget.remove()}
                                        className="size-full object-cover"
                                    />
                                </div>
                                <div className="p-5">
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <h2 className="font-semibold">{type.name}</h2>
                                            <p className="mt-1 text-xs text-stone-500">{type.slug}</p>
                                        </div>
                                        <span className={`shrink-0 text-xs font-medium ${type.is_active ? 'text-emerald-800' : 'text-stone-500'}`}>
                                            {type.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </div>
                                    <div className="mt-5 grid grid-cols-3 gap-3 border-t border-stone-100 pt-4 text-sm">
                                        <div>
                                            <p className="text-xs text-stone-500">Capacity</p>
                                            <p className="mt-1 font-medium">{type.capacity} guests</p>
                                        </div>
                                        <div>
                                            <p className="text-xs text-stone-500">Nightly</p>
                                            <p className="mt-1 font-medium">{type.nightly_rate === null ? 'Not set' : type.nightly_rate}</p>
                                        </div>
                                        <div>
                                            <p className="text-xs text-stone-500">Rooms</p>
                                            <p className="mt-1 font-medium">{type.rooms_count}</p>
                                        </div>
                                    </div>
                                    <div className="mt-5 flex items-center gap-4 border-t border-stone-100 pt-4">
                                        {canManage ? (
                                            <>
                                                <ActionLink href={`/staff/room-types/${type.id}/edit`}>Edit</ActionLink>
                                                <DeleteButton onClick={() => window.confirm(`Delete ${type.name}?`) && router.delete(`/staff/room-types/${type.id}`)} />
                                            </>
                                        ) : (
                                            <span className="text-sm text-stone-400">View only</span>
                                        )}
                                    </div>
                                </div>
                            </article>
                        );
                    })}
                </section>
            ) : (
                <p className="rounded-md border border-dashed border-stone-300 bg-white px-5 py-12 text-center text-sm text-stone-500">No room types added yet.</p>
            )}
            <Pagination links={roomTypes.links} />
        </StaffLayout>
    );
}
