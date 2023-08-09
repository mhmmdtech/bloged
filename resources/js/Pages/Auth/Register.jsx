import { useEffect } from "react";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Head, Link, useForm } from "@inertiajs/react";
import SelectInput from "@/Components/SelectInput";
import FileInput from "@/Components/FileInput";

export default function Register({ genders }) {
    const { data, setData, post, processing, errors, reset, progress } =
        useForm({
            first_name: "",
            last_name: "",
            national_code: "",
            mobile_number: "",
            gender: "",
            email: "",
            username: "",
            password: "",
            password_confirmation: "",
            avatar: "",
            birthday: "",
        });

    useEffect(() => {
        return () => {
            reset("password", "password_confirmation");
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route("register"));
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="first_name" value="First Name *" />

                    <TextInput
                        id="first_name"
                        name="first_name"
                        value={data.first_name}
                        className="mt-1 block w-full"
                        autoComplete="first_name"
                        isFocused={true}
                        onChange={(e) => setData("first_name", e.target.value)}
                    />

                    <InputError message={errors.first_name} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="last_name" value="Last Name *" />

                    <TextInput
                        id="last_name"
                        name="last_name"
                        value={data.last_name}
                        className="mt-1 block w-full"
                        autoComplete="last_name"
                        isFocused={false}
                        onChange={(e) => setData("last_name", e.target.value)}
                    />

                    <InputError message={errors.last_name} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel
                        htmlFor="national_code"
                        value="National Code *"
                    />

                    <TextInput
                        id="national_code"
                        name="national_code"
                        value={data.national_code}
                        className="mt-1 block w-full"
                        autoComplete="national_code"
                        isFocused={false}
                        onChange={(e) =>
                            setData("national_code", e.target.value)
                        }
                    />

                    <InputError
                        message={errors.national_code}
                        className="mt-2"
                    />
                </div>

                <div className="mt-4">
                    <InputLabel
                        htmlFor="mobile_number"
                        value="Mobile Number *"
                    />

                    <TextInput
                        id="mobile_number"
                        name="mobile_number"
                        value={data.mobile_number}
                        className="mt-1 block w-full"
                        autoComplete="mobile_number"
                        isFocused={false}
                        onChange={(e) =>
                            setData("mobile_number", e.target.value)
                        }
                    />

                    <InputError
                        message={errors.mobile_number}
                        className="mt-2"
                    />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="email" value="Email *" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={false}
                        onChange={(e) => setData("email", e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="gender" value="Gender *" />

                    <SelectInput
                        id="gender"
                        name="gender"
                        value={data.gender}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={false}
                        onChange={(e) => setData("gender", e.target.value)}
                    >
                        <option value="">Choose</option>
                        {Object.entries(genders).map(([key, value]) => (
                            <option key={key} value={key}>
                                {value}
                            </option>
                        ))}
                    </SelectInput>

                    <InputError message={errors.gender} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="username" value="Username *" />

                    <TextInput
                        id="username"
                        name="username"
                        value={data.username}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={false}
                        onChange={(e) => setData("username", e.target.value)}
                    />

                    <InputError message={errors.username} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password *" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        isFocused={false}
                        onChange={(e) => setData("password", e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        isFocused={false}
                        onChange={(e) =>
                            setData("password_confirmation", e.target.value)
                        }
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="avatar" value="Avatar" />

                    <FileInput
                        name="avatar"
                        accept=".jpg, .jpeg, .png"
                        onChange={(e) => setData("avatar", e.target.files[0])}
                        progress={progress}
                        className="my-1"
                    />

                    <InputError message={errors.avatar} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="birthday" value="Birthday" />
                    <TextInput
                        type="date"
                        id="birthday"
                        name="birthday"
                        value={data.birthday}
                        className="mt-1 block w-full"
                        autoComplete="birthday"
                        isFocused={false}
                        onChange={(e) => setData("birthday", e.target.value)}
                        max={new Date(
                            new Date().getUTCFullYear() - 10,
                            new Date().getUTCMonth(),
                            new Date().getUTCDate() + 1
                        )
                            .toJSON()
                            .slice(0, 10)}
                    />

                    <InputError message={errors.birthday} className="mt-2" />
                </div>

                <div className="flex items-center justify-end mt-4">
                    <Link
                        href={route("login")}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Already registered?
                    </Link>

                    <PrimaryButton className="ml-4" disabled={processing}>
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
