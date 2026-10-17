import { Link, useForm } from '@inertiajs/react';
import { FormActions, Field, SelectField, TextArea } from '../../../components/FormControls';
import StaffLayout from '../../../components/StaffLayout';

export default function Form({ room, roomTypes }) {
    const editing = Boolean(room);
    const form = useForm({
        room_type_id: room?.room_type_id || '',
        room_number: room?.room_number || '',
        floor: room?.floor ?? '',
        status: room?.status || 'available',
        notes: room?.notes || '',
    });

    function submit(event) {
        event.preventDefault();
        const data = { ...form.data, floor: form.data.floor === '' ? null : Number(form.data.floor) };
        form.transform(() => data);
        editing ? form.put(`/staff/rooms/${room.id}`) : form.post('/staff/rooms');
    }

    return (
        <StaffLayout title={editing ? 'Edit room' : 'Add room'}>
            <form onSubmit={submit} className="mt-6 max-w-2xl space-y-5 rounded-2xl border border-stone-200 bg-white p-6 sm:p-8">
                <Field label="Room number" required value={form.data.room_number} onChange={(event) => form.setData('room_number', event.target.value)} error={form.errors.room_number} />
                <SelectField label="Room type" required value={form.data.room_type_id} onChange={(event) => form.setData('room_type_id', event.target.value)} error={form.errors.room_type_id}>
                    <option value="">Select a room type</option>
                    {roomTypes.map((type) => <option key={type.id} value={type.id}>{type.name}</option>)}
                </SelectField>
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Floor" type="number" min="0" value={form.data.floor} onChange={(event) => form.setData('floor', event.target.value)} error={form.errors.floor} />
                    <SelectField label="Status" value={form.data.status} onChange={(event) => form.setData('status', event.target.value)} error={form.errors.status}>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </SelectField>
                </div>
                <TextArea label="Notes" value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} error={form.errors.notes} />
                <FormActions processing={form.processing} cancelHref="/staff/rooms" submitLabel={editing ? 'Save changes' : 'Create room'} />
                <p><Link href="/staff/rooms" className="text-sm text-stone-500 underline underline-offset-4">Back to rooms</Link></p>
            </form>
        </StaffLayout>
    );
}
