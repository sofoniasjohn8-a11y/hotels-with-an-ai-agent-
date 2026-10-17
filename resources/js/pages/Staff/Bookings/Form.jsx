import { Link, useForm } from '@inertiajs/react';
import { FormActions, Field, SelectField, TextArea } from '../../../components/FormControls';
import StaffLayout from '../../../components/StaffLayout';

function dateValue(value) {
    return value ? value.slice(0, 10) : '';
}

export default function Form({ booking, guests, roomTypes }) {
    const editing = Boolean(booking);
    const form = useForm({
        guest_id: booking?.guest_id || '',
        room_type_id: booking?.room_type_id || '',
        check_in: dateValue(booking?.check_in),
        check_out: dateValue(booking?.check_out),
        guest_count: booking?.guest_count || 1,
        notes: booking?.notes || '',
    });

    function submit(event) {
        event.preventDefault();
        const data = { ...form.data, guest_count: Number(form.data.guest_count) };
        form.transform(() => data);
        editing ? form.put(`/staff/bookings/${booking.id}`) : form.post('/staff/bookings');
    }

    return (
        <StaffLayout title={editing ? 'Edit booking' : 'Create booking'}>
            {guests.length === 0 && (
                <p className="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    Add a guest record before creating a booking. <Link href="/staff/guests/create" className="font-medium underline underline-offset-4">Create a guest</Link>
                </p>
            )}
            <form onSubmit={submit} className="mt-6 max-w-2xl space-y-5 rounded-2xl border border-stone-200 bg-white p-6 sm:p-8">
                <SelectField label="Guest" required value={form.data.guest_id} onChange={(event) => form.setData('guest_id', event.target.value)} error={form.errors.guest_id}>
                    <option value="">Select a guest</option>
                    {guests.map((guest) => <option key={guest.id} value={guest.id}>{guest.name}{guest.phone ? ` · ${guest.phone}` : ''}</option>)}
                </SelectField>
                <SelectField label="Room type" required value={form.data.room_type_id} onChange={(event) => form.setData('room_type_id', event.target.value)} error={form.errors.room_type_id}>
                    <option value="">Select a room type</option>
                    {roomTypes.map((type) => <option key={type.id} value={type.id}>{type.name} · up to {type.capacity} guests · rate {type.nightly_rate ?? 'not set'}</option>)}
                </SelectField>
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Check-in" type="date" required value={form.data.check_in} onChange={(event) => form.setData('check_in', event.target.value)} error={form.errors.check_in} />
                    <Field label="Check-out" type="date" required value={form.data.check_out} onChange={(event) => form.setData('check_out', event.target.value)} error={form.errors.check_out} />
                </div>
                <Field label="Number of guests" type="number" min="1" max="255" required value={form.data.guest_count} onChange={(event) => form.setData('guest_count', event.target.value)} error={form.errors.guest_count} />
                <TextArea label="Booking notes" value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} error={form.errors.notes} />
                <FormActions processing={form.processing || guests.length === 0 || roomTypes.length === 0} cancelHref="/staff/bookings" submitLabel={editing ? 'Save changes' : 'Create booking'} />
                <p><Link href="/staff/bookings" className="text-sm text-stone-500 underline underline-offset-4">Back to bookings</Link></p>
            </form>
        </StaffLayout>
    );
}
