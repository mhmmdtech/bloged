import { forwardRef, useEffect, useRef } from "react";

export default forwardRef(
    ({ name = "", className = "", isFocused = false, ...props }, ref) => {
        const input = ref ? ref : useRef();

        useEffect(() => {
            if (isFocused) {
                input.current.focus();
            }
        }, []);

        return (
            <textarea
                name={name}
                id={name}
                cols="30"
                rows="10"
                className={
                    "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm " +
                    className
                }
                ref={input}
                {...props}
            ></textarea>
        );
    }
);
