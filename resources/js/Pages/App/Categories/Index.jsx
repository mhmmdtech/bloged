import Pagination from "@/Components/Pagination";
import AppLayout from "@/Layouts/AppLayout";
import { Link, Head } from "@inertiajs/react";

export default ({ auth, categories: { data: categories } }) => {
    return (
        <AppLayout auth={auth}>
            <Head>
                <title>Categories</title>
                <meta name="description" content="List of all our categories" />
            </Head>
            <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                <div className="w-full flex flex-wrap items-center justify-between">
                    <h2 className="text-2xl text-black/90 sm:text-5xl font-semibold">
                        All Categories
                    </h2>
                </div>
                <div className="w-full mt-6 flex flex-wrap justify-between gap-2">
                    {categories.map((category) => (
                        <Link
                            key={category.id}
                            href={route("application.categories.show", {
                                category: category.unique_id,
                                slug: category.slug,
                            })}
                            className="rounded-md "
                        >
                            <img
                                src={category.thumbnail["small"]}
                                className="object-cover h-72 rounded-md"
                                alt={category.seo_title}
                            />
                            <div className="my-2">
                                <h3 className="">{category.title}</h3>
                                <span>
                                    created by {category?.creator?.username}
                                </span>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
};
