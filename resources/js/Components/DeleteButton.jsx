export default ({ onDelete, children }) => (
    <button
        className="bg-indigo-500 p-2 rounded-md text-white focus:outline-none"
        tabIndex="-1"
        type="button"
        onClick={onDelete}
    >
        {children}
    </button>
);
