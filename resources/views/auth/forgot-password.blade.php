<x-guest-layout>

    <div class="row" style="width: 600px;">
        <div class="col-md-12 text-center mb-3">
            <a href="/">
                <x-application-logo/>
            </a>
        </div>

        <div class="col-md-12">
            <x-auth-card>

                <div class="pb-4">
                    {{ __('Forgot your password?') }} <br/>
                    {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')"/>

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>

                <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                    <div>
                        <x-label for="email" :value="__('Email')"/>

                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus/>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button>
                            {{ __('Email Password Reset Link') }}
                        </x-button>
                    </div>
                </form>
            </x-auth-card>

        </div>
    </div>

</x-guest-layout>
