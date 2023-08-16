import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import DeleteButton from "@/Components/DeleteButton";
import { useEffect } from "react";

export default function Show({ users: { data: users } }) {
    console.log(users);
    useEffect(() => {
        window.print();
        return () => {
            window.close();
        };
    });
    return (
        <table className="w-full whitespace-nowrap">
            <thead>
                <tr className="font-bold text-left">
                    <th className="px-6 pt-5 pb-4">Full Name</th>
                    <th className="px-6 pt-5 pb-4">Username</th>
                    <th className="px-6 pt-5 pb-4">Email</th>
                </tr>
            </thead>
            <tbody>
                {users.map(({ id, full_name, username, email }) => (
                    <tr
                        key={id}
                        className="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td className="border-t">
                            <Link
                                href={route("administration.users.show", id)}
                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                            >
                                {full_name}
                            </Link>
                        </td>
                        <td className="border-t">
                            <Link
                                tabIndex="-1"
                                href={route("administration.users.show", id)}
                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                            >
                                {username}
                            </Link>
                        </td>
                        <td className="border-t">
                            <Link
                                tabIndex="-1"
                                href={route("administration.users.show", id)}
                                className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                            >
                                {email}
                            </Link>
                        </td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
}
