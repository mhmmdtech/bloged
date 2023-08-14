import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";
import { router } from "@inertiajs/react";
import { formatDistance } from "date-fns";
import { convertUtcToLocalDate } from "@/utils/functions";

export default function Show({ auth, user: { data: userDetails } }) {
    function destroy() {
        router.delete(route("administration.users.destroy", userDetails.id), {
            onBefore: () =>
                confirm("Are you sure you want to delete this user?"),
        });
    }
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>{userDetails.username}</title>
            </Head>

            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between mb-6">
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.users.edit",
                            userDetails.id
                        )}
                    >
                        <span>Edit</span>
                        <span className="hidden md:inline"> User</span>
                    </Link>
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.users.roles",
                            userDetails.id
                        )}
                    >
                        <span>Roles</span>
                    </Link>
                    <Link
                        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                        href={route(
                            "administration.users.permissions",
                            userDetails.id
                        )}
                    >
                        <span>Permissions</span>
                    </Link>
                    {userDetails.deleted_at || (
                        <DeleteButton onDelete={destroy}>
                            Delete User
                        </DeleteButton>
                    )}
                </div>
                <h1 className="text-4xl">{userDetails.username}</h1>
                {userDetails.avatar && (
                    <div className="hidden xs:block w-80 h-80 my-4 rounded-full mx-auto shadow-lg shadow-neutral-500">
                        <img
                            className="w-80 h-80 object-cover rounded-full"
                            src={userDetails.avatar}
                            alt={`${userDetails.username}'s avatar`}
                        />
                    </div>
                )}
                <ul className="list-disc list-inside my-4 text-black/75">
                    <li>First Name: {userDetails.first_name ?? "Unknown"}</li>
                    <li>Last Name: {userDetails.last_name ?? "Unknown"}</li>
                    <li>Username: {userDetails.username ?? "Unknown"}</li>
                    <li>Email: {userDetails.email ?? "Unknown"}</li>
                    <li>
                        National Code: {userDetails.national_code ?? "Unknown"}
                    </li>
                    <li>
                        Mobile Number: {userDetails.mobile_number ?? "Unknown"}
                    </li>
                    <li>Gender: {userDetails.gender?.value ?? "Unknown"}</li>
                    <li>
                        Gender:{" "}
                        {userDetails.military_status?.value ??
                            "Female don't need to do military service"}
                    </li>
                    <li>
                        Province:{" "}
                        {userDetails?.province?.local_name || "Unknown"}
                    </li>
                    <li>City: {userDetails?.city?.local_name || "Unknown"}</li>
                    <li>
                        Birthday:{" "}
                        {new Date(userDetails.birthday).toDateString() ??
                            "Unknown"}
                    </li>
                    <li>
                        Created By:{" "}
                        {userDetails.creator?.username ?? "Registeration Form"}
                    </li>
                    <li>
                        Created At:{" "}
                        {formatDistance(
                            convertUtcToLocalDate(userDetails.created_at),
                            new Date(),
                            { addSuffix: true }
                        ) ?? "Unknown"}{" "}
                        at {userDetails.farsi_created_at_string}
                    </li>
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
