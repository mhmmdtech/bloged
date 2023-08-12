    {{-- Intro Lines --}}
    @lang('Hey ' . $username . ' , Welcome to ' . config('app.name') . '!')
    @lang('Please click the buttonn below to verify your email address.')

    {{-- Action Button --}}
    <x-mail::button :url="$automaticAccountVerificationUrl" color="primary">
        @lang('Verify account')
    </x-mail::button>

    {{-- Outro Lines --}}
    @lang('Or use the following code to verify your account: ' . $token)

    {{-- outro Button --}}
    <x-mail::button :url="$manualAccountVerificationUrl" color="primary">
        @lang('Verify account with code')
    </x-mail::button>
