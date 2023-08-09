import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import Icons from "@/Components/Icons";
import Pagination from "@/Components/Pagination";
import { shortenText } from "@/utils/functions";
import { router } from "@inertiajs/react";

export default function Index({ auth, posts }) {
    const {
        data,
        meta: { links },
    } = posts;

    function toggleFeatured(id) {
        router.patch(route("administration.posts.toggle-featured", id));
    }

    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Posts
                </h2>
            }
        >
            <Head>
                <title>List of Posts</title>
            </Head>
            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route("administration.posts.create")}
                    >
                        <span>Create</span>
                        <span className="hidden md:inline"> Post</span>
                    </Link>
                </div>
                <div className="overflow-x-auto bg-white rounded shadow">
                    <table className="w-full whitespace-nowrap">
                        <thead>
                            <tr className="font-bold text-left">
                                <th className="px-6 pt-5 pb-4">Is Featured</th>
                                <th className="px-6 pt-5 pb-4">Title</th>
                                <th className="px-6 pt-5 pb-4">Author</th>
                                <th className="px-6 pt-5 pb-4">Category</th>
                                <th className="px-6 pt-5 pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.map(
                                ({
                                    id,
                                    title,
                                    author,
                                    category,
                                    status,
                                    is_featured,
                                }) => (
                                    <tr
                                        key={id}
                                        className="hover:bg-gray-100 focus-within:bg-gray-100"
                                    >
                                        <td className="border-t p-6">
                                            <input
                                                type="checkbox"
                                                checked={is_featured}
                                                onChange={() =>
                                                    toggleFeatured(id)
                                                }
                                                id="is_featured"
                                            />
                                        </td>
                                        <td className="border-t">
                                            <Link
                                                tabIndex="-1"
                                                href={route(
                                                    "administration.posts.show",
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
                                                    "administration.posts.show",
                                                    id
                                                )}
                                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                            >
                                                {author.username}
                                            </Link>
                                        </td>
                                        <td className="border-t">
                                            <Link
                                                tabIndex="-1"
                                                href={route(
                                                    "administration.posts.show",
                                                    id
                                                )}
                                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                            >
                                                {category.title}
                                            </Link>
                                        </td>
                                        <td className="border-t">
                                            <Link
                                                tabIndex="-1"
                                                href={route(
                                                    "administration.posts.show",
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
                                                    "administration.posts.show",
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
                            {data.length === 0 && (
                                <tr>
                                    <td
                                        className="px-6 py-4 border-t"
                                        colSpan="4"
                                    >
                                        No posts found.
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
