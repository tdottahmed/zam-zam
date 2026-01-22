import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <h2 className="text-2xl font-bold text-center text-white mb-6">Create Account</h2>

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <InputLabel htmlFor="name" value="Name" className="text-white" />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full bg-white/20 border-transparent text-white placeholder-gray-300 focus:border-white focus:ring-white rounded-lg"
                        autoComplete="name"
                        isFocused={true}
                        onChange={(e) => setData('name', e.target.value)}
                        required
                        placeholder="Full Name"
                    />

                    <InputError message={errors.name} className="mt-2 text-red-300" />
                </div>

                <div>
                    <InputLabel htmlFor="email" value="Email" className="text-white" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-white/20 border-transparent text-white placeholder-gray-300 focus:border-white focus:ring-white rounded-lg"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                        placeholder="Email Address"
                    />

                    <InputError message={errors.email} className="mt-2 text-red-300" />
                </div>

                <div>
                    <InputLabel htmlFor="password" value="Password" className="text-white" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full bg-white/20 border-transparent text-white placeholder-gray-300 focus:border-white focus:ring-white rounded-lg"
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                        placeholder="Password"
                    />

                    <InputError message={errors.password} className="mt-2 text-red-300" />
                </div>

                <div>
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                        className="text-white"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full bg-white/20 border-transparent text-white placeholder-gray-300 focus:border-white focus:ring-white rounded-lg"
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                        required
                        placeholder="Confirm Password"
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2 text-red-300"
                    />
                </div>

                <div className="flex items-center justify-between mt-6">
                    <Link
                        href={route('login')}
                        className="text-sm text-gray-200 underline hover:text-white"
                    >
                        Already registered?
                    </Link>

                    <PrimaryButton className="ms-4 bg-[#C41E3A] hover:bg-[#a01830] border-none px-6 py-2 shadow-lg" disabled={processing}>
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
