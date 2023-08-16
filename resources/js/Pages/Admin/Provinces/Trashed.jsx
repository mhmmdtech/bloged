import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";
import { router } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";

export default ({ auth, provinces }) => {
    const {
        data,
        meta: { links },
    } = provinces;

    function destroyAll() {
        if (data.length === 0) {
            alert("There is not any trashed province right now.");
            return;
        }
        router.delete(route("administration.provinces.force-delete", null), {
            onBefore: () =>
                confirm("Are you sure you want to delete all these provinces?"),
        });
    }

    function destroy(id) {
        router.delete(route("administration.provinces.force-delete", id), {
            onBefore: () =>
                confirm("Are you sure you want to delete this province?"),
        });
    }

    function restore(id) {
        router.patch(route("administration.provinces.restore", id), {
            onBefore: () =>
                confirm("Are you sure you want to restore this province?"),
        });
    }

    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Trashed Provinces
                </h2>
            }
        >
            <Head>
                <title>List of Provinces</title>
            </Head>
            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <DeleteButton onDelete={destroyAll}>
                        Delete Provinces
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
                            {data.map(({ id, local_name, status }) => (
                                <tr
                                    key={id}
                                    className="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
                                    <td className="border-t">
                                        <Link
                                            href={route(
                                                "administration.provinces.show",
                                                id
                                            )}
                                            className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                        >
                                            {local_name}
                                        </Link>
                                    </td>
                                    <td className="border-t">
                                        <Link
                                            tabIndex="-1"
                                            href={route(
                                                "administration.provinces.show",
                                                id
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
                                                onDelete={(e) => destroy(id)}
                                            >
                                                Delete
                                            </DeleteButton>
                                            <DeleteButton
                                                onDelete={(e) => restore(id)}
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
                                        No provinces found.
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
