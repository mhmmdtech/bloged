import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import Icons from "@/Components/Icons";
import Pagination from "@/Components/Pagination";
import { shortenText } from "@/utils/functions";

export default function Index({ auth, categories }) {
    const {
        data,
        meta: { links },
    } = categories;
    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Categories
                </h2>
            }
        >
            <Head>
                <title>List of Categories</title>
            </Head>
            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route("administration.categories.create")}
                    >
                        <span>Create</span>
                        <span className="hidden md:inline"> Category</span>
                    </Link>
                </div>
                <div className="overflow-x-auto bg-white rounded shadow">
                    <table className="w-full whitespace-nowrap">
                        <thead>
                            <tr className="font-bold text-left">
                                <th className="px-6 pt-5 pb-4">Title</th>
                                <th className="px-6 pt-5 pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.map(({ id, title, status }) => (
                                <tr
                                    key={id}
                                    className="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
                                    <td className="border-t">
                                        <Link
                                            href={route(
                                                "administration.categories.show",
                                                id
                                            )}
                                            className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                        >
                                            {shortenText(title, 25)}
                                        </Link>
                                    </td>
                                    <td className="border-t">
                                        <Link
                                            tabIndex="-1"
                                            href={route(
                                                "administration.categories.show",
                                                id
                                            )}
                                            className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                        >
                                            {status?.value ?? "Unknown"}
                                        </Link>
                                    </td>
                                    <td className="w-px border-t">
                                        <Link
                                            tabIndex="-1"
                                            href={route(
                                                "administration.categories.show",
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
                            {data.length === 0 && (
                                <tr>
                                    <td
                                        className="px-6 py-4 border-t"
                                        colSpan="4"
                                    >
                                        No categories found.
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
}
