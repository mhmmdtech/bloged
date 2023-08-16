import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";
import { router } from "@inertiajs/react";
import { formatDistance } from "date-fns";
import { convertUtcToLocalDate } from "@/utils/functions";

export default function Show({ auth, category: { data: categoryDetails } }) {
    function destroy() {
        router.delete(
            route(
                "administration.categories.destroy",
                categoryDetails.unique_id
            ),
            {
                onBefore: () =>
                    confirm("Are you sure you want to delete this category?"),
            }
        );
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>{categoryDetails.seo_title}</title>
                <meta
                    name="description"
                    content={categoryDetails.seo_description}
                />
            </Head>

            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.categories.edit",
                            categoryDetails.unique_id
                        )}
                    >
                        <span>Edit</span>
                        <span className="hidden md:inline"> Category</span>
                    </Link>
                    {categoryDetails.deleted_at || (
                        <DeleteButton onDelete={destroy}>
                            Delete Category
                        </DeleteButton>
                    )}
                </div>
                <h1 className="text-4xl">{categoryDetails.title}</h1>
                <div className="w-full my-4 rounded-md shadow-lg shadow-neutral-500">
                    <img
                        className="w-full object-cover rounded-md"
                        src={categoryDetails.thumbnail["medium"]}
                        alt={categoryDetails.title}
                    />
                </div>
                <div className="">{categoryDetails.description}</div>
                <ul className="list-disc list-inside my-4 text-black/75">
                    <li>Slug: {categoryDetails.slug ?? "Unknown"}</li>
                    <li>
                        User: {categoryDetails.creator?.username ?? "Unknown"}
                    </li>
                    <li>
                        Status: {categoryDetails.status?.value ?? "Unknown"}
                    </li>
                    <li>
                        Created:{" "}
                        {formatDistance(
                            convertUtcToLocalDate(categoryDetails.created_at),
                            new Date(),
                            { addSuffix: true }
                        ) ?? "Unknown"}
                    </li>
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
