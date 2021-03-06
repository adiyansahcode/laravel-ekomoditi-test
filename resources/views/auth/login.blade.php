<x-guest-layout :title="__('Sign in to your account')">
  <x-jet-authentication-card>
    <x-slot name="logo">
      <x-jet-authentication-card-logo />
    </x-slot>

    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
      {{ session('status') }}
    </div>
    @endif

    <x-form id="form-login" action="{{ route('login') }}" novalidate autocomplete="off">

      <!-- username -->
      <div>
        <x-jet-label for="username" value="{{ __('Username') }}" />
        @php
        $borderColor = $errors->has('username')
        ? 'border-red-300 focus:border-red-300 focus:ring-red-300'
        : 'border-gray-300 focus:border-jets-300 focus:ring-jets-300';
        @endphp
        <x-input id="username" name="username" required autofocus
          class="mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 {{ $borderColor }}" />
        <x-error field="username" class="relative py-3 px-3 leading-normal text-red-700 bg-red-100 rounded-lg" />
      </div>

      <!-- password -->
      <div class="mt-4">
        <x-jet-label for="password" value="{{ __('Password') }}" />
        @php
        $borderColor = $errors->has('password')
        ? 'border-red-300 focus:border-red-300 focus:ring-red-300'
        : 'border-gray-300 focus:border-jets-300 focus:ring-jets-300';
        @endphp
        <x-form.password-show id="password" name="password"
          class="mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 {{ $borderColor }}" />
        <x-error field="password" class="relative py-3 px-3 leading-normal text-red-700 bg-red-100 rounded-lg" />
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
        <x-checkbox name="remember" id="remember"
          class="rounded border-jets-300 text-jets-600 shadow-sm focus:ring focus:border-jets-300 focus:ring-jets-200 focus:ring-opacity-50" />
        <x-label for="remember" class="ml-2 text-sm text-gray-700" />
      </div>

      <div class="block mt-4">
        <button type="submit"
          class="w-full px-2 py-2 items-center bg-jets-500 border border-transparent rounded-md font-medium text-base text-white uppercase tracking-widest hover:bg-jets-700 active:bg-jets-900 focus:outline-none focus:border-jets-900 focus:ring ring-jets-300 disabled:opacity-25 transition ease-in-out duration-150 flex-auto">
          Sign in
        </button>
      </div>
    </x-form>
  </x-jet-authentication-card>
</x-guest-layout>
