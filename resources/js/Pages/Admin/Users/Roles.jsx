import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import { syncArray } from "@/utils/functions";
import LoadingButton from "@/Components/LoadingButton";
import InputError from "@/Components/InputError";

export default ({ auth, user: { data: user }, roles, currentRoles }) => {
    const { data, setData, post, processing, errors } = useForm({
        currentRoles: currentRoles,
        _method: "PUT",
    });

    function handleSubmit(e) {
        e.preventDefault();
        post(route("administration.users.roles.update", user.id));
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>Edit Roles</title>
            </Head>
            <div className="max-w-5xl my-6 mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-hidden bg-white rounded shadow">
                <form onSubmit={handleSubmit}>
                    <div className="flex flex-col">
                        <div className="flex flex-wrap justify-evenly p-8 -mb-8 -mr-6 gap-4">
                            {roles.map((role) => (
                                <label
                                    key={role.id}
                                    className="flex items-center"
                                    htmlFor={`roles#${role.id}`}
                                >
                                    <input
                                        type="checkbox"
                                        className="mr-2"
                                        name="roles[]"
                                        value={role.name}
                                        id={`roles#${role.id}`}
                                        onChange={(e) => {
                                            syncArray(
                                                data.currentRoles,
                                                e.target.value
                                            );
                                            setData(
                                                "currentRoles",
                                                data.currentRoles
                                            );
                                        }}
                                        checked={data.currentRoles.includes(
                                            role.name
                                        )}
                                    />
                                    <span>{role.name}</span>
                                </label>
                            ))}
                        </div>

                        <div className="flex justify-center">
                            <InputError
                                message={errors.currentRoles}
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
                            Update Roles
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
};
