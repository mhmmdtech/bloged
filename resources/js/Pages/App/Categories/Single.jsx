import Pagination from "@/Components/Pagination";
import AppLayout from "@/Layouts/AppLayout";
import { Link, Head } from "@inertiajs/react";

export default ({ auth, category: { data: category }, posts }) => {
    const {
        data,
        meta: { links },
    } = posts;
    return (
        <AppLayout auth={auth}>
            <Head>
                <title>{category.seo_title}</title>
                <meta name="description" content={category.seo_description} />
            </Head>
            <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                <div className="w-full flex flex-wrap items-center justify-between">
                    <h2 className="text-2xl text-black/90 sm:text-5xl font-semibold">
                        All {category.title}'s posts
                    </h2>
                </div>
                <div className="w-full mt-6 flex flex-wrap justify-between gap-2">
                    {data.map((post) => (
                        <Link
                            key={post.id}
                            href={route("application.posts.show", {
                                post: post.unique_id,
                                slug: post.slug,
                            })}
                            className="rounded-md "
                        >
                            <img
                                src={post.thumbnail["small"]}
                                className="object-cover h-72 rounded-md"
                                alt={post.seo_title}
                            />
                            <div className="my-2">
                                <h3 className="">{post.title}</h3>
                                <div className="flex flex-wrap justify-between">
                                    <span>
                                        written by {post?.author.username}
                                    </span>
                                    <span>
                                        {post.reading_time}{" "}
                                        {post.reading_time === 1
                                            ? "min"
                                            : "mins"}{" "}
                                        to read
                                    </span>
                                </div>
                            </div>
                        </Link>
                    ))}
                </div>
                <Pagination links={links} />
            </div>
        </AppLayout>
    );
};
