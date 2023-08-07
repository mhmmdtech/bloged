import { forwardRef, useEffect, useRef } from "react";

export default forwardRef(
    ({ className = "", isFocused = false, children, ...props }, ref) => {
        const input = ref ? ref : useRef();

        useEffect(() => {
            if (isFocused) {
                input.current.focus();
            }
        }, []);

        return (
            <select
                {...props}
                className="w-full text-black/75 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm my-1"
                ref={input}
            >
                {children}
            </select>
        );
    }
);
