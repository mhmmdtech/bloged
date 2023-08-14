import Pagination from "@/Components/Pagination";
import AppLayout from "@/Layouts/AppLayout";
import { Link, Head } from "@inertiajs/react";

export default ({ auth, posts }) => {
    const {
        data,
        meta: { links },
    } = posts;
    return (
        <AppLayout auth={auth}>
            <Head>
                <title>Posts</title>
                <meta name="description" content="List of all our posts" />
            </Head>
            <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                <div className="w-full flex flex-wrap items-center justify-between">
                    <h2 className="text-2xl text-black/90 sm:text-5xl font-semibold">
                        All Posts
                    </h2>
                </div>
                <div className="w-full mt-6 flex flex-wrap justify-between gap-2">
                    {data.map((post) => (
                        <Link
                            key={post.id}
                            href={route("application.posts.show", post.id)}
                            className="rounded-md "
                        >
                            <img
                                src={post.thumbnail}
                                className="object-cover h-72 rounded-md"
                                alt={post.seo_title}
                            />
                            <div className="my-2">
                                <h3 className="">{post.title}</h3>
                                <span>written by {post?.author.username}</span>
                            </div>
                            <div className="flex flex-wrap justify-between">
                                <span>in {post?.category?.title} section</span>
                                <span>
                                    {post.reading_time}{" "}
                                    {post.reading_time === 1 ? "min" : "mins"}{" "}
                                    to read
                                </span>
                            </div>
                        </Link>
                    ))}
                </div>
                <Pagination links={links} />
            </div>
        </AppLayout>
    );
};
