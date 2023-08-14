import Icons from "@/Components/Icons";
import { Link } from "@inertiajs/react";

export default ({ auth }) => {
    return (
        <>
            <header className="container flex flex-wrap justify-between mx-auto p-2">
                <Link href={route("application.home")}>
                    <Icons name="logo" className="w-16 h-16" />
                </Link>
                <div className="flex items-center gap-4">
                    <Link
                        href={route("application.home")}
                        className="hover:text-[#f93f04] transition-colors"
                    >
                        Home
                    </Link>
                    <Link
                        href={route("application.categories.index")}
                        className="hover:text-[#f93f04] transition-colors"
                    >
                        Categories
                    </Link>
                    <Link
                        href={route("application.posts.index")}
                        className="hover:text-[#f93f04] transition-colors"
                    >
                        Posts
                    </Link>
                </div>
                <div className="flex items-center gap-2">
                    {!auth?.user?.data && (
                        <Link
                            href={route("login")}
                            className="hover:text-[#f93f04] transition-colors"
                        >
                            Login
                        </Link>
                    )}
                    {!auth?.user?.data && (
                        <Link
                            href={route("register")}
                            className="hover:text-[#f93f04] transition-colors"
                        >
                            Register
                        </Link>
                    )}

                    {auth?.user?.data && (
                        <Link
                            href={route("administration.dashboard")}
                            className="hover:text-[#f93f04] transition-colors"
                        >
                            <Icons
                                name="user"
                                className="w-9 h-9 hover:fill-[#f93f04] transition-colors"
                            />
                        </Link>
                    )}
                </div>
            </header>
        </>
    );
};
