import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <h2 className="text-2xl font-bold text-center text-white mb-6">Welcome Back</h2>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-400 text-center">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <InputLabel htmlFor="email" value="Email" className="text-white" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-white/20 border-transparent text-white placeholder-gray-300 focus:border-white focus:ring-white rounded-lg"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="Enter your email"
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
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                         placeholder="Enter your password"
                    />

                    <InputError message={errors.password} className="mt-2 text-red-300" />
                </div>

                <div className="block">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
                            className="text-[#C41E3A] border-white/50 bg-transparent focus:ring-[#C41E3A]"
                        />
                        <span className="ms-2 text-sm text-gray-200">
                            Remember me
                        </span>
                    </label>
                </div>

                <div className="flex items-center justify-between mt-6">
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-gray-200 underline hover:text-white"
                        >
                            Forgot password?
                        </Link>
                    )}

                    <PrimaryButton className="ms-4 bg-[#C41E3A] hover:bg-[#a01830] border-none px-6 py-2 shadow-lg" disabled={processing}>
                        Log in
                    </PrimaryButton>
                </div>
                
                <div className="text-center mt-6">
                     <span className="text-gray-300 text-sm">Don't have an account? </span>
                     <Link href={route('register')} className="text-white font-bold hover:underline">Sign up</Link>
                </div>
            </form>
        </GuestLayout>
    );
}
