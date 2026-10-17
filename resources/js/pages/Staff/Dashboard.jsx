import { Link } from '@inertiajs/react';
import StaffLayout from '../../components/StaffLayout';

const cards = [
    { key: 'bookings', label: 'Active bookings', href: '/staff/bookings' },
    { key: 'guests', label: 'Guests', href: '/staff/guests' },
    { key: 'rooms', label: 'Rooms', href: '/staff/rooms' },
    { key: 'roomTypes', label: 'Room types', href: '/staff/room-types' },
];

export default function Dashboard({ summary, upcomingBookings }) {
    return (
        <StaffLayout title="Dashboard">
            <p className="mt-2 text-sm text-stone-500">A quick overview of current hotel operations.</p>
            <section aria-label="Hotel overview" className="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                {cards.map((card) => (
                    <Link key={card.key} href={card.href} className="rounded-2xl border border-stone-200 bg-white p-5 transition hover:border-stone-400">
                        <p className="text-sm text-stone-500">{card.label}</p>
                        <p className="mt-2 text-3xl font-semibold">{summary[card.key]}</p>
                    </Link>
                ))}
            </section>
            <section className="mt-8">
                <div className="mb-4 flex items-center justify-between gap-3">
                    <h2 className="text-lg font-semibold">Upcoming arrivals</h2>
                    <Link href="/staff/bookings" className="text-sm font-medium text-stone-600 underline decoration-stone-300 underline-offset-4">View bookings</Link>
                </div>
                {upcomingBookings.length > 0 ? (
                    <div className="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
                        <table className="w-full min-w-[620px] text-left text-sm">
                            <thead className="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                                <tr>
                                    <th className="px-5 py-3 font-medium">Reference</th>
                                    <th className="px-5 py-3 font-medium">Guest</th>
                                    <th className="px-5 py-3 font-medium">Room type</th>
                                    <th className="px-5 py-3 font-medium">Check-in</th>
                                    <th className="px-5 py-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-stone-100">
                                {upcomingBookings.map((booking) => (
                                    <tr key={booking.id}>
                                        <td className="px-5 py-4 font-mono text-xs">{booking.booking_reference}</td>
                                        <td className="px-5 py-4">{booking.guest.name}</td>
                                        <td className="px-5 py-4">{booking.room_type.name}</td>
                                        <td className="px-5 py-4">{booking.check_in}</td>
                                        <td className="px-5 py-4 capitalize">{booking.status.replace('_', ' ')}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                ) : (
                    <p className="rounded-2xl border border-dashed border-stone-300 bg-white px-5 py-12 text-center text-sm text-stone-500">No upcoming arrivals yet.</p>
                )}
            </section>
        </StaffLayout>
    );
}
