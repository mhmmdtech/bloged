import { useEffect, useState } from "react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm, usePage } from "@inertiajs/react";
import { Transition } from "@headlessui/react";
import SelectInput from "@/Components/SelectInput";
import FileInput from "@/Components/FileInput";

export default function UpdateProfileInformation({
    mustVerifyEmail,
    status,
    className = "",
}) {
    let [cities, setCities] = useState([]);

    const user = usePage().props.auth.user?.data;
    const genders = usePage().props.genders;
    const militaryStatuses = usePage().props.militaryStatuses;
    const provinces = usePage().props.provinces;
    const {
        data,
        setData,
        post,
        errors,
        processing,
        recentlySuccessful,
        progress,
    } = useForm({
        first_name: user.first_name,
        last_name: user.last_name,
        national_code: user.national_code,
        mobile_number: user.mobile_number,
        gender: user.gender?.key || "",
        email: user.email,
        username: user.username,
        avatar: "",
        birthday: new Date(user.birthday).toJSON().slice(0, 10) || "",
        military_status: user.military_status?.key || "",
        province_id: user?.province?.id || "",
        city_id: user?.city?.id || "",
        _method: "PATCH",
    });

    const submit = (e) => {
        e.preventDefault();
        post(route("profile.update"));
    };

    useEffect(() => {
        if (data.province_id === "" || data.province_id === null) return;

        const province = provinces.find(
            (province) => province.id === +data.province_id
        );

        setCities(province.cities);
    }, [data.province_id]);

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Profile Information
                </h2>

                <p className="mt-1 text-sm text-gray-600">
                    Update your account's profile information.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="first_name" value="First name *" />

                    <TextInput
                        id="first_name"
                        className="mt-1 block w-full"
                        value={data.first_name}
                        onChange={(e) => setData("first_name", e.target.value)}
                        required
                        isFocused={true}
                        autoComplete="first_name"
                    />

                    <InputError className="mt-2" message={errors.first_name} />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="last_name" value="Last Name *" />

                    <TextInput
                        id="last_name"
                        className="mt-1 block w-full"
                        value={data.last_name}
                        onChange={(e) => setData("last_name", e.target.value)}
                        required
                        isFocused={false}
                        autoComplete="last_name"
                    />

                    <InputError className="mt-2" message={errors.last_name} />
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
                        required
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
                        required
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
                        required
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
                        required
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
                        required
                    />

                    <InputError message={errors.username} className="mt-2" />
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

                <div className="mt-4">
                    <InputLabel
                        htmlFor="military_status"
                        value="Military Status"
                    />

                    <SelectInput
                        id="military_status"
                        name="military_status"
                        value={data.military_status}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={false}
                        onChange={(e) =>
                            setData("military_status", e.target.value)
                        }
                        disabled={data.gender != 1}
                    >
                        <option value="">Choose</option>
                        {Object.entries(militaryStatuses).map(
                            ([key, value]) => (
                                <option key={key} value={key}>
                                    {value}
                                </option>
                            )
                        )}
                    </SelectInput>

                    <InputError
                        message={errors.military_status}
                        className="mt-2"
                    />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="province_id" value="Province" />

                    <SelectInput
                        id="province_id"
                        name="province_id"
                        value={data.province_id}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={false}
                        onChange={(e) => setData("province_id", e.target.value)}
                    >
                        <option value="">Choose</option>
                        {Object.values(provinces).map((province) => (
                            <option key={province.id} value={province.id}>
                                {province.local_name}
                            </option>
                        ))}
                    </SelectInput>

                    <InputError message={errors.province_id} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="city_id" value="City" />

                    <SelectInput
                        id="city_id"
                        name="city_id"
                        value={data.city_id}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={false}
                        onChange={(e) => {
                            setData("city_id", e.target.value);
                        }}
                        disabled={data.province_id === ""}
                    >
                        <option value="">Choose</option>
                        {Object.values(cities).map((city) => (
                            <option key={city.id} value={city.id}>
                                {city.local_name}
                            </option>
                        ))}
                    </SelectInput>

                    <InputError message={errors.city_id} className="mt-2" />
                </div>

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div>
                        <p className="text-sm mt-2 text-gray-800">
                            Your email address is unverified.
                            <Link
                                href={route("verification.send")}
                                method="post"
                                as="button"
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        {status === "verification-link-sent" && (
                            <div className="mt-2 font-medium text-sm text-green-600">
                                A new verification link has been sent to your
                                email address.
                            </div>
                        )}
                    </div>
                )}

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Save</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
