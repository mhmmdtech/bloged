export default ({
    name = "",
    accept = "*",
    onChange,
    progress,
    className = "",
    ...props
}) => {
    return (
        <div className="flex flex-col">
            <input
                type="file"
                name={name}
                accept={accept}
                onChange={onChange}
                className={
                    "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-indigo-500 shadow-sm " +
                    className
                }
                {...props}
            />
            {progress && (
                <progress
                    value={progress.percentage}
                    max="100"
                    className="mt-1"
                >
                    {progress.percentage}%
                </progress>
            )}
        </div>
    );
};
