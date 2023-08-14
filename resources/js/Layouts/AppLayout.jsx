import Header from "@/Components/Header";
import Footer from "@/Components/Footer";

export default ({ auth, children }) => {
    return (
        <>
            <Header auth={auth} />
            <main>{children}</main>
            <Footer />
        </>
    );
};
