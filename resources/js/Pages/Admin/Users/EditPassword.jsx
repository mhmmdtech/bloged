import { useRef } from "react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default ({ auth, className = "", user: { data: user } }) => {
    const passwordInput = useRef();
    const { data, setData, errors, put, reset, processing } = useForm({
        password: "",
        password_confirmation: "",
    });
    const updatePassword = (e) => {
        e.preventDefault();
        put(route("administration.users.password.update", user.id), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (errors) => {
                if (errors.password) {
                    reset("password", "password_confirmation");
                    passwordInput.current.focus();
                }
            },
        });
    };
    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Users
                </h2>
            }
        >
            <Head>
                <title>{user.username}</title>
            </Head>
            <div className="max-w-5xl my-6 mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-hidden bg-white rounded shadow">
                <section className={className}>
                    <header className="flex flex-wrap gap-4 justify-between items-center">
                        <div className="">
                            <h2 className="text-lg font-medium text-gray-900">
                                Update Password
                            </h2>
                            <p className="mt-1 text-sm text-gray-600">
                                Ensure account is using a long, random password
                                to stay secure.
                            </p>
                        </div>
                        <Link
                            className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                            href={route("administration.users.edit", user.id)}
                        >
                            <span>Back</span>
                        </Link>
                    </header>
                    <form onSubmit={updatePassword} className="mt-6 space-y-6">
                        <div>
                            <InputLabel
                                htmlFor="password"
                                value="New Password"
                            />
                            <TextInput
                                id="password"
                                ref={passwordInput}
                                value={data.password}
                                onChange={(e) =>
                                    setData("password", e.target.value)
                                }
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />
                            <InputError
                                message={errors.password}
                                className="mt-2"
                            />
                        </div>
                        <div>
                            <InputLabel
                                htmlFor="password_confirmation"
                                value="Confirm Password"
                            />
                            <TextInput
                                id="password_confirmation"
                                value={data.password_confirmation}
                                onChange={(e) =>
                                    setData(
                                        "password_confirmation",
                                        e.target.value
                                    )
                                }
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />
                            <InputError
                                message={errors.password_confirmation}
                                className="mt-2"
                            />
                        </div>
                        <div className="flex items-center gap-4">
                            <PrimaryButton disabled={processing}>
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </section>
            </div>
        </AuthenticatedLayout>
    );
};
