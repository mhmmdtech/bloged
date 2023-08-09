import Pagination from "@/Components/Pagination";
import AppLayout from "@/Layouts/AppLayout";
import { Link, Head } from "@inertiajs/react";

export default ({ auth, category: { data: category }, posts }) => {
    console.log(posts);
    const { data, links } = posts;
    return (
        <AppLayout user={auth?.user?.data}>
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
                                <span>
                                    was written by {post?.author.username}
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
