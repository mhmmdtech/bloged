import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm, router } from "@inertiajs/react";
import Icons from "@/Components/Icons";
import LoadingButton from "@/Components/LoadingButton";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import SelectInput from "@/Components/SelectInput";
import { parseQueryString, removeNullFromArray } from "@/utils/functions";
import Pagination from "@/Components/Pagination";

export default ({ auth, results = {}, genders }) => {
    const { data: dataResults, meta } = results;
    const links = meta?.links ?? [];
    const usersResults = dataResults ?? [];
    const queryParams = parseQueryString(window.location.search.substring(1));
    const { data, setData, processing, errors } = useForm({
        gender: queryParams?.gender || "",
        province: queryParams?.province || "",
        city: queryParams?.city || "",
    });

    function handleSubmit(e) {
        e.preventDefault();
        router.get(
            route("administration.users.report"),
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
                    Users Report
                </h2>
            }
        >
            <Head>
                <title>List of Users</title>
            </Head>
            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <form onSubmit={handleSubmit} className="print:hidden">
                    <div className="flex flex-wrap justify-between p-8 -mb-8 -mr-6 gap-4">
                        <div className="w-full">
                            <InputLabel htmlFor="province" value="Province" />

                            <TextInput
                                id="province"
                                name="province"
                                value={data.province}
                                className="mt-1 block w-full"
                                autoComplete="province"
                                isFocused={true}
                                onChange={(e) =>
                                    setData("province", e.target.value)
                                }
                                errors={errors.province}
                            />

                            <InputError
                                message={errors.province}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel htmlFor="city" value="City" />

                            <TextInput
                                id="city"
                                name="city"
                                value={data.city}
                                className="mt-1 block w-full"
                                autoComplete="city"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("city", e.target.value)
                                }
                            />

                            <InputError
                                message={errors.city}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full mt-4">
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
                    </div>
                    <div className="flex flex-wrap justify-center mt-4 gap-4">
                        <LoadingButton
                            loading={processing}
                            type="submit"
                            className="bg-indigo-500 p-2 rounded-md text-white"
                        >
                            Show Result
                        </LoadingButton>
                    </div>
                </form>
                <div className="flex flex-col gap-4 overflow-x-auto rounded shadow mt-4">
                    {usersResults.length > 0 && (
                        <div className="flex justify-between items-center gap-4">
                            <a
                                href={route(
                                    "administration.users.report.print",
                                    removeNullFromArray(data)
                                )}
                                target="_blank"
                                className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                            >
                                Print
                            </a>

                            <a
                                href={route(
                                    "administration.users.report.excel",
                                    removeNullFromArray(data)
                                )}
                                className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                            >
                                Excel
                            </a>
                            <a
                                href={route(
                                    "administration.users.report.csv",
                                    removeNullFromArray(data)
                                )}
                                className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                            >
                                CSV
                            </a>
                        </div>
                    )}
                    <table className="w-full whitespace-nowrap">
                        <thead>
                            <tr className="font-bold text-left">
                                <th className="px-6 pt-5 pb-4">Username</th>
                                <th className="px-6 pt-5 pb-4">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            {usersResults.map(({ id, username, email }) => (
                                <tr
                                    key={id}
                                    className="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
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
                                    <td className="w-px border-t print:hidden">
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
                            ))}
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
