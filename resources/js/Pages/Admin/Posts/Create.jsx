import TextInput from "@/Components/TextInput";
import SelectInput from "@/Components/SelectInput";
import FileInput from "@/Components/FileInput";
import Textarea from "@/Components/Textarea";
import LoadingButton from "@/Components/LoadingButton";
import InputLabel from "@/Components/InputLabel";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useForm } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default function Create({ auth, statuses, activeCategories }) {
    const { data, setData, post, processing, errors, progress } = useForm({
        title: "",
        seo_title: "",
        description: "",
        seo_description: "",
        status: "",
        thumbnail: "",
        body: "",
        htmlContent: "",
        category_id: "",
    });

    function handleSubmit(e) {
        e.preventDefault();
        data.body = new DOMParser().parseFromString(
            data.htmlContent,
            "text/html"
        ).documentElement.textContent;
        post(route("administration.posts.store"));
    }

    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Create Post
                </h2>
            }
        >
            <Head>
                <title>Create new Post</title>
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
                                htmlFor="category_id"
                                value="category *"
                            />

                            <SelectInput
                                name="category_id"
                                errors={errors.category_id}
                                value={data.category_id}
                                onChange={(e) =>
                                    setData("category_id", e.target.value)
                                }
                            >
                                <option value="">Choose</option>
                                {Object.values(activeCategories).map(
                                    ({ id: key, title: value }) => (
                                        <option key={key} value={key}>
                                            {value}
                                        </option>
                                    )
                                )}
                            </SelectInput>

                            <InputError
                                message={errors.category_id}
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
                        <div className="w-full">
                            <InputLabel htmlFor="body" value="Body *" />

                            <CKEditor
                                editor={ClassicEditor}
                                data={data.htmlContent}
                                onChange={(event, editor) => {
                                    const data = editor.getData();
                                    setData("htmlContent", data);
                                }}
                            />

                            <InputError
                                message={errors.body}
                                className="mt-2"
                            />
                        </div>
                    </div>
                    <div className="flex flex-wrap justify-center mt-4">
                        <LoadingButton
                            loading={processing}
                            type="submit"
                            className="bg-indigo-500 p-2 rounded-md text-white"
                        >
                            Create Post
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
