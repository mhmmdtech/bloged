import { useEffect, useRef } from "react";
import Icons from "@/Components/Icons";
import AppLayout from "@/Layouts/AppLayout";
import { Link, Head } from "@inertiajs/react";
import { register } from "swiper/element/bundle";
import AcerLogo from "../../../app-assets/images/logos/acer-logo.png";
import AppleLogo from "../../../app-assets/images/logos/apple-logo.png";
import AsusLogo from "../../../app-assets/images/logos/asus-logo.png";
import DellLogo from "../../../app-assets/images/logos/dell-logo.png";
import HpLogo from "../../../app-assets/images/logos/hp-logo.png";
import LenovoLogo from "../../../app-assets/images/logos/lenovo-logo.png";
import MicrosoftLogo from "../../../app-assets/images/logos/microsoft-logo.png";
import SamsungLogo from "../../../app-assets/images/logos/samsung-logo.png";
import SonyLogo from "../../../app-assets/images/logos/sony-logo.png";
import { formatDistance } from "date-fns";
import { convertUtcToLocalDate } from "@/utils/functions";

register();

export default function Welcome({
    auth,
    featuredPost,
    latestPosts: { data: latestPosts },
    categories: { data: categories },
}) {
    const latestPostsSlider = useRef(null);
    useEffect(() => {
        const latestPostsSliderParams = {
            // Default parameters
            slidesPerView: 1,
            spaceBetween: 10,
            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                // when window width is >= 640
                640: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                // when window width is >= 1024
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                // when window width is >= 1250
                1250: {
                    slidesPerView: 5,
                    spaceBetween: 30,
                },
                // when window width is >= 1560
                1250: {
                    slidesPerView: 6,
                    spaceBetween: 40,
                },
            },
        };
        // assign all parameters to Swiper element
        Object.assign(latestPostsSlider.current, latestPostsSliderParams);
        // initialize it
        latestPostsSlider.current.initialize();
    }, []);
    return (
        <AppLayout auth={auth}>
            <Head>
                <title>Home</title>
                <meta
                    name="description"
                    content="We are a testish RILT based blog"
                />
            </Head>
            <div className="container flex flex-col items-center justify-center mx-auto p-2">
                {featuredPost.data && (
                    <Link
                        href={route(
                            "application.posts.show",
                            featuredPost.data.id
                        )}
                        className="relative rounded-md"
                    >
                        <img
                            src={featuredPost.data.thumbnail["medium"]}
                            alt={featuredPost.data.seo_title}
                            className="rounded-md"
                        />
                        <div className="flex justify-between items-end absolute w-full h-full inset-0 p-10 text-white bg-gradient-to-t from-black/50 rounded-md">
                            <h1 className="hidden 2xs:block max-w-md text-lg">
                                {featuredPost.data.title}
                            </h1>
                            <div className="hidden xs:flex flex-col">
                                <span>
                                    {formatDistance(
                                        convertUtcToLocalDate(
                                            featuredPost.data.created_at
                                        ),
                                        new Date(),
                                        { addSuffix: true }
                                    ) ?? "Unknown"}
                                </span>
                            </div>
                        </div>
                    </Link>
                )}
            </div>
            <div className="max-w-screen-xl flex flex-wrap justify-between mx-auto gap-2 mt-4 px-4">
                <img src={AcerLogo} alt="" className="w-14 h-14" />
                <img src={AppleLogo} alt="" className="w-14 h-14" />
                <img src={AsusLogo} alt="" className="w-14 h-14" />
                <img src={DellLogo} alt="" className="w-14 h-14" />
                <img src={HpLogo} alt="" className="w-14 h-14" />
                <img src={LenovoLogo} alt="" className="w-14 h-14" />
                <img src={MicrosoftLogo} alt="" className="w-14 h-14" />
                <img src={SamsungLogo} alt="" className="w-14 h-14" />
                <img src={SonyLogo} alt="" className="w-14 h-14" />
            </div>
            <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                <div className="w-full flex flex-wrap items-center justify-between">
                    <h2 className="text-2xl text-black/90 sm:text-5xl font-semibold">
                        Latest Posts
                    </h2>
                    <Link
                        href={route("application.posts.index")}
                        className="group flex items-center gap-2 border border-black/90 text-black/90 p-2 focus:outline-[#f93f04] hover:border-[#f93f04] transition-colors"
                    >
                        <Icons
                            name="diagonal-arrow"
                            className="w-4 h-4 fill-black/90 group-hover:fill-[#f93f04] transition-colors"
                        />
                        <span className="group-hover:text-[#f93f04] transition-colors">
                            View All
                        </span>
                    </Link>
                </div>
                <div className="w-full mt-6 ">
                    <swiper-container init="false" ref={latestPostsSlider}>
                        {latestPosts.map((latestPost) => (
                            <swiper-slide key={latestPost.id}>
                                <Link
                                    href={route(
                                        "application.posts.show",
                                        latestPost.id
                                    )}
                                    className="rounded-md "
                                >
                                    <img
                                        src={latestPost.thumbnail["small"]}
                                        className="object-cover h-72 rounded-md"
                                        alt={latestPost.seo_title}
                                    />
                                    <div className="my-2">
                                        <h3 className="">{latestPost.title}</h3>
                                        <span>
                                            was written by{" "}
                                            {latestPost?.author.username}
                                        </span>
                                    </div>
                                    <span className="font-semibold">
                                        in {latestPost?.category?.title} section
                                    </span>
                                </Link>
                            </swiper-slide>
                        ))}
                    </swiper-container>
                </div>
            </div>
            <div className="container flex flex-col items-center justify-center mx-auto mt-12 px-4">
                <div className="w-full flex flex-wrap items-center justify-between">
                    <h2 className="text-2xl text-black/90 sm:text-5xl font-semibold">
                        Latest Categories
                    </h2>
                    <Link
                        href={route("application.categories.index")}
                        className="group flex items-center gap-2 border border-black/90 text-black/90 p-2 focus:outline-[#f93f04] hover:border-[#f93f04] transition-colors"
                    >
                        <Icons
                            name="diagonal-arrow"
                            className="w-4 h-4 fill-black/90 group-hover:fill-[#f93f04] transition-colors"
                        />
                        <span className="group-hover:text-[#f93f04] transition-colors">
                            View All
                        </span>
                    </Link>
                </div>
                <div className="w-full mt-6 flex flex-wrap justify-between gap-2">
                    {categories.map((category) => (
                        <Link
                            key={category.id}
                            href={route(
                                "application.categories.show",
                                category.id
                            )}
                            className="rounded-md "
                        >
                            <img
                                src={category.thumbnail["small"]}
                                className="object-cover h-72 rounded-md"
                                alt={category.seo_title}
                            />
                            <div className="my-2">
                                <h3 className="">{category.title}</h3>
                                <span>
                                    was created by {category?.creator?.username}
                                </span>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
