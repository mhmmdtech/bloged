import classnames from "classnames";

export default ({ loading, className, children, ...props }) => {
    const classNames = classnames(
        "flex items-center",
        "focus:outline-none",
        {
            "pointer-events-none bg-opacity-75 select-none": loading,
        },
        className
    );
    return (
        <button disabled={loading} className={classNames} {...props}>
            {loading && <span>Loading...</span>}
            {loading || children}
        </button>
    );
};
