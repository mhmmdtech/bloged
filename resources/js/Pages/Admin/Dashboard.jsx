import { useEffect, useRef } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import * as echarts from "echarts/core";
import { GridComponent } from "echarts/components";
import { BarChart } from "echarts/charts";
import { CanvasRenderer } from "echarts/renderers";

export default ({ auth, usersByProvince }) => {
    const user = auth?.user?.data;
    const chartEl = useRef(null);
    useEffect(() => {
        if (!auth.can.browse_analytic) {
            return;
        }
        echarts.use([GridComponent, BarChart, CanvasRenderer]);

        const provinces = usersByProvince.map((obj) => obj.province);
        const userCounts = usersByProvince.map((obj) => obj.users);

        // https://echarts.apache.org/
        // https://dev.to/manufac/using-apache-echarts-with-react-and-typescript-353k
        let chart = null;

        if (chartEl.current !== null) {
            chart = echarts.init(chartEl.current);
        }

        let option = {
            xAxis: {
                type: "category",
                data: [...provinces],
            },
            yAxis: {
                type: "value",
            },
            series: [
                {
                    data: [...userCounts],
                    type: "bar",
                    showBackground: true,
                    backgroundStyle: {
                        color: "rgba(180, 180, 180, 0.2)",
                    },
                },
            ],
        };

        option && chart.setOption(option);

        function resizeChart() {
            chart && chart.resize();
        }
        window.addEventListener("resize", resizeChart);

        return () => {
            chart && chart.dispose();

            window.removeEventListener("resize", resizeChart);
        };
    }, []);

    return (
        <AuthenticatedLayout
            user={user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="text-gray-900">
                            Hey {user.username}! Welcome to your panel.
                        </div>
                        {auth.can.browse_analytic && (
                            <div className="flex justify-center items-center w-full">
                                <div
                                    ref={chartEl}
                                    className="w-full max-w-screen-md"
                                    style={{ height: "400px" }}
                                ></div>
                            </div>
                        )}
                        {auth.can.browse_analytic && (
                            <div className="flex justify-center items-center w-full">
                                <Link
                                    className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
                                    href={route("administration.users.report")}
                                >
                                    <span>Get Users Report</span>
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
};
