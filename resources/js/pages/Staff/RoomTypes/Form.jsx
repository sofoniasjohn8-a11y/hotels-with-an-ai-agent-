import { Link, useForm } from '@inertiajs/react';
import { FormActions, Field, SelectField, TextArea } from '../../../components/FormControls';
import StaffLayout from '../../../components/StaffLayout';

function csv(value) {
    return (value || []).join(', ');
}

export default function Form({ roomType }) {
    const editing = Boolean(roomType);
    const form = useForm({
        name: roomType?.name || '',
        slug: roomType?.slug || '',
        description: roomType?.description || '',
        capacity: roomType?.capacity || 2,
        nightly_rate: roomType?.nightly_rate ?? '',
        amenities: csv(roomType?.amenities),
        photos: csv(roomType?.photos),
        is_active: roomType?.is_active ?? true,
    });

    function submit(event) {
        event.preventDefault();
        const data = {
            ...form.data,
            capacity: Number(form.data.capacity),
            nightly_rate: form.data.nightly_rate === '' ? null : Number(form.data.nightly_rate),
            amenities: form.data.amenities.split(',').map((item) => item.trim()).filter(Boolean),
            photos: form.data.photos.split(',').map((item) => item.trim()).filter(Boolean),
        };
        form.transform(() => data);
        editing ? form.put(`/staff/room-types/${roomType.id}`) : form.post('/staff/room-types');
    }

    return (
        <StaffLayout title={editing ? 'Edit room type' : 'Add room type'}>
            <form onSubmit={submit} className="mt-6 max-w-2xl space-y-5 rounded-2xl border border-stone-200 bg-white p-6 sm:p-8">
                <Field label="Name" required value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} error={form.errors.name} />
                <Field label="URL slug" required value={form.data.slug} onChange={(event) => form.setData('slug', event.target.value)} error={form.errors.slug} />
                <TextArea label="Description" value={form.data.description} onChange={(event) => form.setData('description', event.target.value)} error={form.errors.description} />
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Maximum guests" type="number" min="1" required value={form.data.capacity} onChange={(event) => form.setData('capacity', event.target.value)} error={form.errors.capacity} />
                    <Field label="Nightly rate (leave blank until confirmed)" type="number" min="0" step="0.01" value={form.data.nightly_rate} onChange={(event) => form.setData('nightly_rate', event.target.value)} error={form.errors.nightly_rate} />
                </div>
                <Field label="Amenities (comma separated)" value={form.data.amenities} onChange={(event) => form.setData('amenities', event.target.value)} error={form.errors.amenities || form.errors['amenities.0']} />
                <Field label="Photo URLs (comma separated)" value={form.data.photos} onChange={(event) => form.setData('photos', event.target.value)} error={form.errors.photos || form.errors['photos.0']} />
                <SelectField label="Visibility" value={form.data.is_active ? '1' : '0'} onChange={(event) => form.setData('is_active', event.target.value === '1')}>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </SelectField>
                <FormActions processing={form.processing} cancelHref="/staff/room-types" submitLabel={editing ? 'Save changes' : 'Create room type'} />
                <p><Link href="/staff/room-types" className="text-sm text-stone-500 underline underline-offset-4">Back to room types</Link></p>
            </form>
        </StaffLayout>
    );
}
