import { useEffect, useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm, router } from "@inertiajs/react";
import Icons from "@/Components/Icons";
import Pagination from "@/Components/Pagination";
import LoadingButton from "@/Components/LoadingButton";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import SelectInput from "@/Components/SelectInput";
import { parseQueryString, removeNullFromArray } from "@/utils/functions";

export default ({
    auth,
    results = {},
    creators,
    genders,
    militaryStatuses,
    provinces,
}) => {
    const { data: dataResults, meta } = results;
    const links = meta?.links ?? [];
    const usersResults = dataResults ?? [];
    const queryParams = parseQueryString(window.location.search.substring(1));
    let [cities, setCities] = useState([]);

    const { data, setData, processing, errors, reset, progress } = useForm({
        first_name: queryParams?.first_name || "",
        last_name: queryParams?.last_name || "",
        national_code: queryParams?.national_code || "",
        mobile_number: queryParams?.mobile_number || "",
        email: queryParams?.email || "",
        username: queryParams?.username || "",
        creator_id: queryParams?.creator_id || "",
        birthday: queryParams?.birthday || "",
        gender: queryParams?.gender || "",
        military_status: queryParams?.military_status || "",
        province_id: queryParams?.province_id || "",
        city_id: queryParams?.city_id || "",
    });
    useEffect(() => {
        if (data.province_id === "" || data.province_id === null) return;

        const province = provinces.find(
            (province) => province.id === +data.province_id
        );

        setCities(province.cities);
    }, [data.province_id]);
    function handleSubmit(e) {
        e.preventDefault();
        router.get(
            route("administration.users.advanced-search"),
            removeNullFromArray(data),
            {
                preserveState: true,
            }
        );
    }
    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Advaced Users Search
                </h2>
            }
        >
            <Head>
                <title>List of Users</title>
            </Head>
            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <form onSubmit={handleSubmit}>
                    <div className="flex flex-wrap justify-between p-8 -mb-8 -mr-6 gap-4">
                        <div className="">
                            <InputLabel
                                htmlFor="first_name"
                                value="First Name"
                            />

                            <TextInput
                                id="first_name"
                                name="first_name"
                                value={data.first_name}
                                className="mt-1 block w-full"
                                autoComplete="first_name"
                                isFocused={true}
                                onChange={(e) =>
                                    setData("first_name", e.target.value)
                                }
                                errors={errors.first_name}
                            />

                            <InputError
                                message={errors.first_name}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
                            <InputLabel htmlFor="last_name" value="Last Name" />

                            <TextInput
                                id="last_name"
                                name="last_name"
                                value={data.last_name}
                                className="mt-1 block w-full"
                                autoComplete="last_name"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("last_name", e.target.value)
                                }
                            />

                            <InputError
                                message={errors.last_name}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
                            <InputLabel
                                htmlFor="national_code"
                                value="National Code"
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
                        <div className="">
                            <InputLabel
                                htmlFor="mobile_number"
                                value="Mobile Number"
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
                        <div className="">
                            <InputLabel htmlFor="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full"
                                autoComplete="email"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                            />

                            <InputError
                                message={errors.email}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
                            <InputLabel htmlFor="username" value="Username" />

                            <TextInput
                                id="username"
                                name="username"
                                value={data.username}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("username", e.target.value)
                                }
                            />

                            <InputError
                                message={errors.username}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
                            <InputLabel htmlFor="creator_id" value="Creator" />

                            <SelectInput
                                id="creator_id"
                                name="creator_id"
                                value={data.creator_id}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("creator_id", e.target.value)
                                }
                            >
                                <option value="">Choose</option>
                                {Object.values(creators).map((value) => (
                                    <option key={value.id} value={value.id}>
                                        {value.username}
                                    </option>
                                ))}
                            </SelectInput>

                            <InputError
                                message={errors.creator_id}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
                            <InputLabel htmlFor="gender" value="Gender" />

                            <SelectInput
                                id="gender"
                                name="gender"
                                value={data.gender}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("gender", e.target.value)
                                }
                            >
                                <option value="">Choose</option>
                                {Object.entries(genders).map(([key, value]) => (
                                    <option key={key} value={key}>
                                        {value}
                                    </option>
                                ))}
                            </SelectInput>

                            <InputError
                                message={errors.gender}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
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
                        <div className="">
                            <InputLabel htmlFor="birthday" value="Birthday" />
                            <TextInput
                                type="date"
                                id="birthday"
                                name="birthday"
                                value={data.birthday}
                                className="mt-1 block w-full"
                                autoComplete="birthday"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("birthday", e.target.value)
                                }
                                max={new Date(
                                    new Date().getUTCFullYear() - 10,
                                    new Date().getUTCMonth(),
                                    new Date().getUTCDate() + 1
                                )
                                    .toJSON()
                                    .slice(0, 10)}
                            />

                            <InputError
                                message={errors.birthday}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
                            <InputLabel
                                htmlFor="province_id"
                                value="Province"
                            />

                            <SelectInput
                                id="province_id"
                                name="province_id"
                                value={data.province_id}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("province_id", e.target.value)
                                }
                            >
                                <option value="">Choose</option>
                                {Object.values(provinces).map((province) => (
                                    <option
                                        key={province.id}
                                        value={province.id}
                                    >
                                        {province.local_name}
                                    </option>
                                ))}
                            </SelectInput>

                            <InputError
                                message={errors.province_id}
                                className="mt-2"
                            />
                        </div>
                        <div className="">
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

                            <InputError
                                message={errors.city_id}
                                className="mt-2"
                            />
                        </div>
                    </div>
                    <div className="flex flex-wrap justify-center mt-4">
                        <LoadingButton
                            loading={processing}
                            type="submit"
                            className="bg-indigo-500 p-2 rounded-md text-white"
                        >
                            Search
                        </LoadingButton>
                    </div>
                </form>
                <div className="overflow-x-auto bg-white rounded shadow mt-4">
                    <table className="w-full whitespace-nowrap">
                        <thead>
                            <tr className="font-bold text-left">
                                <th className="px-6 pt-5 pb-4">Full Name</th>
                                <th className="px-6 pt-5 pb-4">Username</th>
                                <th className="px-6 pt-5 pb-4">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            {usersResults.map(
                                ({ id, full_name, username, email }) => (
                                    <tr
                                        key={id}
                                        className="hover:bg-gray-100 focus-within:bg-gray-100"
                                    >
                                        <td className="border-t">
                                            <Link
                                                href={route(
                                                    "administration.users.show",
                                                    id
                                                )}
                                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                            >
                                                {full_name}
                                            </Link>
                                        </td>
                                        <td className="border-t">
                                            <Link
                                                tabIndex="-1"
                                                href={route(
                                                    "administration.users.show",
                                                    id
                                                )}
                                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                            >
                                                {username}
                                            </Link>
                                        </td>
                                        <td className="border-t">
                                            <Link
                                                tabIndex="-1"
                                                href={route(
                                                    "administration.users.show",
                                                    id
                                                )}
                                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                            >
                                                {email}
                                            </Link>
                                        </td>
                                        <td className="w-px border-t">
                                            <Link
                                                tabIndex="-1"
                                                href={route(
                                                    "administration.users.show",
                                                    id
                                                )}
                                                className="flex items-center px-4 focus:outline-none"
                                            >
                                                <Icons
                                                    name="cheveron-right"
                                                    className="block w-6 h-6 text-gray-400 fill-current"
                                                />
                                            </Link>
                                        </td>
                                    </tr>
                                )
                            )}
                            {usersResults.length === 0 && (
                                <tr>
                                    <td
                                        className="px-6 py-4 border-t"
                                        colSpan="4"
                                    >
                                        No users found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                <Pagination links={links} />
            </div>
        </AuthenticatedLayout>
    );
};
