import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";

export default () => {
    const { data, setData, post, processing, errors } = useForm({
        token: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("verification-code.verify"));
    };

    return (
        <form onSubmit={submit} className="mt-4">
            <div>
                <InputLabel htmlFor="token" value="Token" />
                <TextInput
                    id="token"
                    name="token"
                    value={data.token}
                    className="mt-1 block w-full"
                    autoComplete="token"
                    isFocused={false}
                    onChange={(e) => setData("token", e.target.value)}
                />
                <InputError message={errors.token} className="mt-2" />{" "}
            </div>
            <div className="flex items-center justify-center mt-4">
                <PrimaryButton className="ml-4" disabled={processing}>
                    Verify Account
                </PrimaryButton>
            </div>
        </form>
    );
};
