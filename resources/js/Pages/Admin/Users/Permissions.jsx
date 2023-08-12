import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import { syncArray } from "@/utils/functions";
import LoadingButton from "@/Components/LoadingButton";
import InputError from "@/Components/InputError";

export default ({
    auth,
    user: { data: user },
    permissions,
    currentPermissions,
}) => {
    const { data, setData, post, processing, errors } = useForm({
        currentPermissions: currentPermissions,
        _method: "PUT",
    });

    function handleSubmit(e) {
        e.preventDefault();
        post(route("administration.users.permissions.update", user.id));
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>Edit Direct Permissions</title>
            </Head>
            <div className="max-w-5xl my-6 mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-hidden bg-white rounded shadow">
                <form onSubmit={handleSubmit}>
                    <div className="flex flex-col">
                        <div className="flex flex-wrap justify-evenly p-8 -mb-8 -mr-6 gap-4">
                            {permissions.map((permission) => (
                                <label
                                    key={permission.id}
                                    className="flex items-center"
                                    htmlFor={`permissions#${permission.id}`}
                                >
                                    <input
                                        type="checkbox"
                                        className="mr-2"
                                        name="permissions[]"
                                        value={permission.name}
                                        id={`permissions#${permission.id}`}
                                        onChange={(e) => {
                                            syncArray(
                                                data.currentPermissions,
                                                e.target.value
                                            );
                                            setData(
                                                "currentPermissions",
                                                data.currentPermissions
                                            );
                                        }}
                                        checked={data.currentPermissions.includes(
                                            permission.name
                                        )}
                                    />
                                    <span>{permission.name}</span>
                                </label>
                            ))}
                        </div>

                        <div className="flex justify-center">
                            <InputError
                                message={errors.currentPermissions}
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
                            Update Permissions
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
};
