import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";
import { router } from "@inertiajs/react";
import { formatDistance } from "date-fns";

export default function Show({ auth, province: { data: provinceDetails } }) {
    function destroy() {
        router.delete(
            route("administration.provinces.destroy", provinceDetails.id),
            {
                onBefore: () =>
                    confirm("Are you sure you want to delete this province?"),
            }
        );
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>{provinceDetails.seo_title}</title>
                <meta
                    name="description"
                    content={provinceDetails.seo_description}
                />
            </Head>

            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.provinces.edit",
                            provinceDetails.id
                        )}
                    >
                        <span>Edit</span>
                        <span className="hidden md:inline"> Province</span>
                    </Link>
                    {provinceDetails.deleted_at || (
                        <DeleteButton onDelete={destroy}>
                            Delete Province
                        </DeleteButton>
                    )}
                </div>
                <ul className="list-disc list-inside my-4 text-black/75">
                    <li>
                        Local Name: {provinceDetails.local_name ?? "Unknown"}
                    </li>
                    <li>
                        Latin Name: {provinceDetails.latin_name ?? "Unknown"}
                    </li>
                    <li>
                        User: {provinceDetails.creator?.username ?? "Unknown"}
                    </li>
                    <li>
                        Status: {provinceDetails.status?.value ?? "Unknown"}
                    </li>
                    <li>
                        Created:{" "}
                        {formatDistance(
                            new Date(provinceDetails.created_at),
                            new Date(),
                            { addSuffix: true }
                        ) ?? "Unknown"}
                    </li>
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
