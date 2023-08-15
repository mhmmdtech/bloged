import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";
import { shortenText } from "@/utils/functions";
import { router } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";

export default ({ auth, categories }) => {
    const {
        data,
        meta: { links },
    } = categories;

    function destroyAll() {
        router.delete(route("administration.categories.force-delete", null), {
            onBefore: () =>
                confirm(
                    "Are you sure you want to delete all these categories?"
                ),
        });
    }

    function destroy(unique_id) {
        router.delete(
            route("administration.categories.force-delete", unique_id),
            {
                onBefore: () =>
                    confirm("Are you sure you want to delete this category?"),
            }
        );
    }

    function restore(unique_id) {
        router.delete(route("administration.categories.restore", unique_id), {
            onBefore: () =>
                confirm("Are you sure you want to restore this category?"),
        });
    }
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
                    <DeleteButton onDelete={destroyAll}>
                        Delete Categories
                    </DeleteButton>
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
                            {data.map(({ id, title, status, unique_id }) => (
                                <tr
                                    key={id}
                                    className="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
                                    <td className="border-t">
                                        <Link
                                            href={route(
                                                "administration.categories.show",
                                                unique_id
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
                                                unique_id
                                            )}
                                            className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                        >
                                            {status?.value ?? "Unknown"}
                                        </Link>
                                    </td>
                                    <td className="w-px border-t">
                                        <div
                                            tabIndex="-1"
                                            className="flex items-center px-4 gap-2 focus:outline-none"
                                        >
                                            <DeleteButton
                                                onDelete={(e) =>
                                                    destroy(unique_id)
                                                }
                                            >
                                                Delete
                                            </DeleteButton>
                                            <DeleteButton
                                                onDelete={(e) =>
                                                    restore(unique_id)
                                                }
                                            >
                                                Restore
                                            </DeleteButton>
                                        </div>
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
};
