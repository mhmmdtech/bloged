import { Link } from "@inertiajs/react";
import InstagramLogo from "../../app-assets/images/logos/instagram-logo.png";
import FacebookLogo from "../../app-assets/images/logos/facebook-logo.png";
import LinkedinLogo from "../../app-assets/images/logos/linkedin-logo.png";
import Icons from "./Icons";

export default ({ auth }) => {
    return (
        <>
            <footer className="mt-12 border-t border-t-black/25">
                <div className="container flex flex-col md:flex-row items-center md:items-start mx-auto p-12">
                    <div className="flex flex-wrap grow justify-between gap-20 md:ml-36 md:order-2">
                        <ul className="space-y-2">
                            <li className="font-semibold">
                                <Link>Catalogs</Link>
                            </li>
                            <li className="font-semibold">
                                <Link>Discounts</Link>
                            </li>
                            <li className="font-semibold">
                                <Link>Brands</Link>
                            </li>
                            <li className="font-semibold">
                                <Link>Personal Office</Link>
                            </li>
                        </ul>
                        <ul className="space-y-2">
                            <li className="font-semibold">Customer Service</li>
                            <li className="text-sm text-black/75">
                                <Link>Orders &amp; Delivery</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>Returns &amp; Refunds</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>FAQs</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>Privacy Policy</Link>
                            </li>
                        </ul>
                        <ul className="space-y-2">
                            <li className="font-semibold">About Us</li>
                            <li className="text-sm text-black/75">
                                <Link>Loyalty Programm</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>Blog</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>Vision</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>Our Team</Link>
                            </li>
                        </ul>
                        <ul className="space-y-2">
                            <li className="font-semibold">Contacts</li>
                            <li className="text-sm text-black/75">
                                <Link>+380 (97) 77-56-027</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>example@gmail.com</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>Sustainability</Link>
                            </li>
                            <li className="text-sm text-black/75">
                                <Link>FAQs</Link>
                            </li>
                        </ul>
                    </div>
                    <div className="flex flex-col gap-2 mt-12 md:mt-0 md:order-1">
                        <div className="flex items-center text-lg select-none gap-2">
                            <Icons name="logo" className="w-16 h-16" />
                            <span>Filecommerce</span>
                        </div>
                        <div className="flex gap-4">
                            <div className="rounded-full border flex items-center justify-center border-black/25 w-10 h-10">
                                <img
                                    src={InstagramLogo}
                                    alt=""
                                    className="w-1/2 h-1/2"
                                />
                            </div>
                            <div className="rounded-full border flex items-center justify-center border-black/25 w-10 h-10">
                                <img
                                    src={FacebookLogo}
                                    alt=""
                                    className="w-1/2 h-1/2"
                                />
                            </div>
                            <div className="rounded-full border flex items-center justify-center border-black/25 w-10 h-10">
                                <img
                                    src={LinkedinLogo}
                                    alt=""
                                    className="w-1/2 h-1/2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div className="bg-black/90 flex justify-center items-center p-2 text-white">
                    <p>
                        &copy; {new Date().getUTCFullYear()} - All Rights
                        Reserved
                    </p>
                </div>
            </footer>
        </>
    );
};
