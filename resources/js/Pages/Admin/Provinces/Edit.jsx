import TextInput from "@/Components/TextInput";
import SelectInput from "@/Components/SelectInput";
import LoadingButton from "@/Components/LoadingButton";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useForm } from "@inertiajs/react";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";

export default function Edit({
    auth,
    province: { data: provinceDetails },
    statuses,
}) {
    const { data, setData, put, processing, errors } = useForm({
        local_name: provinceDetails.local_name || "",
        latin_name: provinceDetails.latin_name || "",
        status: provinceDetails.status?.key || "",
    });

    function handleSubmit(e) {
        e.preventDefault();
        put(route("administration.provinces.update", provinceDetails.id));
    }
    return (
        <AuthenticatedLayout
            user={auth?.user?.data}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Province
                </h2>
            }
        >
            <Head>
                <title>{provinceDetails.local_name}</title>
            </Head>

            <div className="max-w-5xl my-6 mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-hidden bg-white rounded shadow">
                <form onSubmit={handleSubmit}>
                    <div className="flex flex-wrap justify-evenly p-8 -mb-8 -mr-6 gap-4">
                        <div className="w-full">
                            <InputLabel
                                htmlFor="local_name"
                                value="Local Name *"
                            />

                            <TextInput
                                type="text"
                                isFocused={true}
                                className="mt-1 block w-full"
                                name="local_name"
                                id="local_name"
                                value={data.local_name}
                                autoComplete="local_name"
                                onChange={(e) =>
                                    setData("local_name", e.target.value)
                                }
                                required
                                errors={errors.local_name}
                            />

                            <InputError
                                message={errors.local_name}
                                className="mt-2"
                            />
                        </div>
                        <div className="w-full">
                            <InputLabel
                                htmlFor="latin_name"
                                value="Latin Name"
                            />

                            <TextInput
                                type="text"
                                className="mt-1 block w-full"
                                name="latin_name"
                                id="latin_name"
                                value={data.latin_name}
                                autoComplete="latin_name"
                                onChange={(e) =>
                                    setData("latin_name", e.target.value)
                                }
                                required
                                errors={errors.latin_name}
                            />

                            <InputError
                                message={errors.latin_name}
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
                    </div>
                    <div className="flex flex-wrap justify-center mt-4">
                        <LoadingButton
                            loading={processing}
                            type="submit"
                            className="bg-indigo-500 p-2 rounded-md text-white"
                        >
                            Update Province
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
