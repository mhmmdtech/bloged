import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";
import { router } from "@inertiajs/react";
import { formatDistance } from "date-fns";

export default function Show({ auth, post: { data: postDetails } }) {
    function destroy() {
        router.delete(route("administration.posts.destroy", postDetails.id), {
            onBefore: () =>
                confirm("Are you sure you want to delete this post?"),
        });
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>{postDetails.seo_title}</title>
                <meta
                    name="description"
                    content={postDetails.seo_description}
                />
            </Head>

            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.posts.edit",
                            postDetails.id
                        )}
                    >
                        <span>Edit</span>
                        <span className="hidden md:inline"> post</span>
                    </Link>
                    {postDetails.deleted_at || (
                        <DeleteButton onDelete={destroy}>
                            Delete post
                        </DeleteButton>
                    )}
                </div>
                <h1 className="text-4xl">{postDetails.title}</h1>
                <div className="w-full my-4 rounded-md shadow-lg shadow-neutral-500">
                    <img
                        className="w-full object-cover rounded-md"
                        src={postDetails.thumbnail}
                        alt={postDetails.seo_title}
                    />
                </div>
                <div className="">{postDetails.description}</div>
                <ul className="list-disc list-inside my-4 text-black/75">
                    <li>
                        Category: {postDetails.category?.title ?? "Unknown"}
                    </li>
                    <li>User: {postDetails.author?.username ?? "Unknown"}</li>
                    <li>Status: {postDetails.status?.value ?? "Unknown"}</li>
                    <li>
                        Reading Time: It takes {postDetails.reading_time} mins
                        to read
                    </li>
                    <li>
                        Created:{" "}
                        {formatDistance(
                            new Date(postDetails.created_at),
                            new Date(),
                            { addSuffix: true }
                        ) ?? "Unknown"}
                    </li>
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
