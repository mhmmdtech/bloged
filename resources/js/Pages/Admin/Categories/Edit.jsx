import TextInput from "@/Components/TextInput";
import FileInput from "@/Components/FileInput";
import SelectInput from "@/Components/SelectInput";
import LoadingButton from "@/Components/LoadingButton";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useForm } from "@inertiajs/react";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";

export default function Edit({
    auth,
    category: { data: categoryDetails },
    statuses,
}) {
    const { data, setData, post, processing, errors, progress } = useForm({
        title: categoryDetails.title || "",
        seo_title: categoryDetails.seo_title || "",
        description: categoryDetails.description || "",
        seo_description: categoryDetails.seo_description || "",
        status: categoryDetails.status?.key || "",
        thumbnail: "",
        _method: "PUT",
    });

    function handleSubmit(e) {
        e.preventDefault();
        post(route("administration.categories.update", categoryDetails.id));
    }
    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Categories
                </h2>
            }
        >
            <Head>
                <title>{categoryDetails.seo_title}</title>
                <meta
                    name="description"
                    content={categoryDetails.seo_description}
                />
            </Head>

            <div className="max-w-5xl my-6 mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-hidden bg-white rounded shadow">
                <form onSubmit={handleSubmit}>
                    <div className="flex flex-wrap justify-evenly p-8 -mb-8 -mr-6 gap-4">
                        <div className="w-full">
                            <InputLabel htmlFor="title" value="Title *" />

                            <TextInput
                                type="text"
                                isFocused={true}
                                className="mt-1 block w-full"
                                name="title"
                                id="title"
                                value={data.title}
                                autoComplete="title"
                                onChange={(e) =>
                                    setData("title", e.target.value)
                                }
                                required
                                errors={errors.title}
                            />

                            <InputError
                                message={errors.title}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel
                                htmlFor="seo_title"
                                value="SEO Title *"
                            />

                            <TextInput
                                type="text"
                                isFocused={false}
                                className="mt-1 block w-full"
                                name="seo_title"
                                id="seo_title"
                                value={data.seo_title}
                                autoComplete="seo_title"
                                onChange={(e) =>
                                    setData("seo_title", e.target.value)
                                }
                                required
                                errors={errors.seo_title}
                            />

                            <InputError
                                message={errors.seo_title}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel
                                htmlFor="description"
                                value="Description *"
                            />

                            <TextInput
                                type="text"
                                isFocused={false}
                                className="mt-1 block w-full"
                                name="description"
                                id="description"
                                value={data.description}
                                autoComplete="description"
                                onChange={(e) =>
                                    setData("description", e.target.value)
                                }
                                required
                                errors={errors.description}
                            />

                            <InputError
                                message={errors.description}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel
                                htmlFor="seo_description"
                                value="SEO Description *"
                            />

                            <TextInput
                                type="text"
                                isFocused={false}
                                className="mt-1 block w-full"
                                name="seo_description"
                                id="seo_description"
                                value={data.seo_description}
                                autoComplete="seo_description"
                                onChange={(e) =>
                                    setData("seo_description", e.target.value)
                                }
                                required
                                errors={errors.seo_description}
                            />

                            <InputError
                                message={errors.seo_description}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel htmlFor="status" value="Status *" />

                            <SelectInput
                                name="status"
                                errors={errors.status}
                                value={data.status}
                                onChange={(e) =>
                                    setData("status", e.target.value)
                                }
                            >
                                <option value="">Choose</option>
                                {Object.entries(statuses).map(
                                    ([key, value]) => (
                                        <option key={key} value={key}>
                                            {value}
                                        </option>
                                    )
                                )}
                            </SelectInput>

                            <InputError
                                message={errors.status}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel
                                htmlFor="thumbnail"
                                value="Thumbnail *"
                            />

                            <FileInput
                                name="thumbnail"
                                accept=".jpg, .jpeg, .png"
                                onChange={(e) =>
                                    setData("thumbnail", e.target.files[0])
                                }
                                progress={progress}
                                className="my-1"
                            />

                            <InputError
                                message={errors.thumbnail}
                                className="mt-2"
                            />
                        </div>
                    </div>
                    <div className="flex flex-wrap justify-center">
                        <LoadingButton
                            loading={processing}
                            type="submit"
                            className="bg-indigo-500 p-2 rounded-md text-white"
                        >
                            Update Category
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
