import AppLayout from "@/Layouts/AppLayout";
import { Head } from "@inertiajs/react";
import { formatDistance } from "date-fns";

export default ({ auth, post: { data: post } }) => {
    return (
        <AppLayout auth={auth}>
            <Head>
                <title>{post.seo_title}</title>
                <meta name="description" content={post.seo_description} />
            </Head>
            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 className="text-4xl">{post.title}</h1>

                <div className="mt-4 text-black/75">{post.description}</div>

                <div className="w-full my-4 rounded-md shadow-lg shadow-neutral-500">
                    <img
                        className="w-full object-cover rounded-md"
                        src={post.thumbnail}
                        alt={post.seo_title}
                    />
                </div>

                <ul className="list-disc list-inside text-black/75">
                    <li>Category: {post.category?.title ?? "Unknown"}</li>
                    <li>User: {post.author?.username ?? "Unknown"}</li>
                    <li>Status: {post.status?.value ?? "Unknown"}</li>
                    <li>
                        Reading Time: It takes {post.reading_time}{" "}
                        {post.reading_time === 1 ? "min" : "mins"} to read
                    </li>
                    <li>
                        Created:{" "}
                        {formatDistance(new Date(post.created_at), new Date(), {
                            addSuffix: true,
                        }) ?? "Unknown"}
                    </li>
                </ul>

                <div className="mt-4">{post.body}</div>
            </div>
        </AppLayout>
    );
};
