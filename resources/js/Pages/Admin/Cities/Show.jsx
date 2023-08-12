import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";
import { router } from "@inertiajs/react";
import { formatDistance } from "date-fns";

export default function Show({ auth, city: { data: cityDetails } }) {
    function destroy() {
        router.delete(route("administration.cities.destroy", cityDetails.id), {
            onBefore: () =>
                confirm("Are you sure you want to delete this city?"),
        });
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>{cityDetails.local_name}</title>
            </Head>
            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.cities.edit",
                            cityDetails.id
                        )}
                    >
                        <span>Edit</span>
                        <span className="hidden md:inline"> City</span>
                    </Link>

                    {cityDetails.deleted_at || (
                        <DeleteButton onDelete={destroy}>
                            Delete City
                        </DeleteButton>
                    )}
                </div>
                <ul className="list-disc list-inside my-4 text-black/75">
                    <li>Local Name: {cityDetails.local_name ?? "Unknown"}</li>
                    <li>Latin Name: {cityDetails.latin_name ?? "Unknown"}</li>
                    <li>User: {cityDetails.creator?.username ?? "Unknown"}</li>
                    <li>
                        Province Local Name:{" "}
                        {cityDetails.province?.local_name ?? "Unknown"}
                    </li>
                    <li>
                        Province Latin Name:{" "}
                        {cityDetails.province?.latin_name ?? "Unknown"}
                    </li>
                    <li>Status: {cityDetails.status?.value ?? "Unknown"}</li>
                    <li>
                        Created:{" "}
                        {formatDistance(
                            new Date(cityDetails.created_at),
                            new Date(),
                            { addSuffix: true }
                        ) ?? "Unknown"}
                    </li>
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
