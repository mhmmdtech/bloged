import Header from "@/Components/Header";
import Footer from "@/Components/Footer";
import { Head } from "@inertiajs/react";

export default function Welcome({ user, children }) {
    return (
        <>
            <Header user={user} />
            <main>{children}</main>
            <Footer />
        </>
    );
}
