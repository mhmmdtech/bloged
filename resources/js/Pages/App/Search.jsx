import InputError from "@/Components/InputError";
import LoadingButton from "@/Components/LoadingButton";
import Pagination from "@/Components/Pagination";
import TextInput from "@/Components/TextInput";
import AppLayout from "@/Layouts/AppLayout";
import { parseQueryString, removeNullFromArray } from "@/utils/functions";
import { Link, Head, useForm, router } from "@inertiajs/react";

export default ({ auth, posts = {}, query = "" }) => {
    const { data: dataResults, meta } = posts;
    const links = meta?.links ?? [];
    const results = dataResults ?? [];
    const queryParams = parseQueryString(window.location.search.substring(1));
    const { data, setData, processing, errors } = useForm({
        query: queryParams?.query || "",
    });
    function handleSubmit(e) {
        e.preventDefault();

        if (data.query.length < 5) {
            alert("Please tell us more");
            return;
        }

        router.get(route("application.search"), removeNullFromArray(data), {
            preserveState: true,
        });
    }
    return (
        <AppLayout auth={auth}>
            <Head>
                <title>Search for posts</title>
                <meta name="description" content="List of all search posts" />
            </Head>
            <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                    <form onSubmit={handleSubmit} className="w-full ">
                        <div className="flex flex-wrap justify-between p-8 -mb-8 -mr-6 gap-4">
                            <TextInput
                                id="query"
                                name="query"
                                value={data.query}
                                className="mt-1 block w-full"
                                autoComplete="query"
                                isFocused={false}
                                onChange={(e) =>
                                    setData("query", e.target.value)
                                }
                                placeholder="Search through published posts"
                            />

                            <InputError
                                message={errors.query}
                                className="mt-2"
                            />
                        </div>
                        <div className="flex flex-wrap justify-center mt-4">
                            <LoadingButton
                                loading={processing}
                                type="submit"
                                className="bg-indigo-500 p-2 rounded-md text-white"
                                disabled={data.query.length < 5}
                            >
                                Search
                            </LoadingButton>
                        </div>
                    </form>
                </div>
                {query.length > 5 && (
                    <div className="w-full flex flex-wrap items-center justify-between mt-12">
                        <h2 className="text-2xl text-black/90 sm:text-5xl font-semibold">
                            Result for {query}
                        </h2>
                    </div>
                )}
                <div className="w-full mt-6 flex flex-wrap justify-between gap-2">
                    {results.map((post) => (
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
