import { Link, useForm } from '@inertiajs/react';
import { FormActions, Field, TextArea } from '../../../components/FormControls';
import StaffLayout from '../../../components/StaffLayout';

export default function Form({ guest }) {
    const editing = Boolean(guest);
    const form = useForm({
        name: guest?.name || '',
        email: guest?.email || '',
        phone: guest?.phone || '',
        notes: guest?.notes || '',
    });

    function submit(event) {
        event.preventDefault();
        const data = Object.fromEntries(Object.entries(form.data).map(([key, value]) => [key, value === '' ? null : value]));
        form.transform(() => data);
        editing ? form.put(`/staff/guests/${guest.id}`) : form.post('/staff/guests');
    }

    return (
        <StaffLayout title={editing ? 'Edit guest' : 'Add guest'}>
            <form onSubmit={submit} className="mt-6 max-w-2xl space-y-5 rounded-2xl border border-stone-200 bg-white p-6 sm:p-8">
                <Field label="Full name" required value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} error={form.errors.name} />
                <Field label="Email" type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} error={form.errors.email} />
                <Field label="Phone" type="tel" value={form.data.phone} onChange={(event) => form.setData('phone', event.target.value)} error={form.errors.phone} />
                <TextArea label="Notes" value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} error={form.errors.notes} />
                <FormActions processing={form.processing} cancelHref="/staff/guests" submitLabel={editing ? 'Save changes' : 'Create guest'} />
                <p><Link href="/staff/guests" className="text-sm text-stone-500 underline underline-offset-4">Back to guests</Link></p>
            </form>
        </StaffLayout>
    );
}
