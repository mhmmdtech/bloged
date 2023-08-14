import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { convertUtcToLocalDate } from "@/utils/functions";
import { Head } from "@inertiajs/react";
import { formatDistance } from "date-fns";

export default function Show({ auth, log: { data: logDetails } }) {
    return (
        <AuthenticatedLayout user={auth?.user?.data}>
            <Head>
                <title>Read Log</title>
            </Head>
            <div className="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <ul className="list-disc list-inside my-4 text-black/75">
                    <li>
                        Actioner: {logDetails.actioner?.username ?? "Unknown"}
                    </li>
                    <li>Action: {logDetails.action ?? "Unknown"}</li>
                    <li>Model Type: {logDetails.model_type ?? "Unknown"}</li>
                    <li>Model ID: {logDetails.model_id ?? "Unknown"}</li>
                    <li>
                        Happend At:{" "}
                        {convertUtcToLocalDate(
                            logDetails.created_at
                        ).toString() ?? "Unknown"}
                    </li>
                    <li>
                        Old Model Structure:{" "}
                        {JSON.stringify(logDetails.old_model, null, 2) ?? "{}"}
                    </li>
                    <li>
                        New Model Structure:{" "}
                        {JSON.stringify(logDetails.new_model, null, 2) ?? "{}"}
                    </li>
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
