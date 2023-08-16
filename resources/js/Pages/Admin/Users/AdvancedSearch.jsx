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

export default ({ auth, results = {}, creators }) => {
    const { data: dataResults, meta } = results;
    const links = meta?.links ?? [];
    const usersResults = dataResults ?? [];
    const queryParams = parseQueryString(window.location.search.substring(1));

    const { data, setData, processing, errors } = useForm({
        national_code: queryParams?.national_code || "",
        email: queryParams?.email || "",
        username: queryParams?.username || "",
        creator_id: queryParams?.creator_id || "",
    });

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
